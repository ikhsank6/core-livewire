<?php

namespace App\Livewire\News;

use App\Forms\NewsForm;
use App\Models\News;
use App\Repositories\Contracts\NewsRepositoryInterface;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.app')]
#[Title('News')]
class NewsIndex extends Component implements HasForms
{
    use InteractsWithForms;
    use WithPagination;

    #[Url]
    public $search = '';

    #[Url]
    public $perPage = 10;

    public ?array $data = [];

    public ?News $record = null;

    public $showModal = false;

    protected NewsRepositoryInterface $newsRepository;

    public function boot(NewsRepositoryInterface $newsRepository): void
    {
        $this->newsRepository = $newsRepository;
    }

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema(NewsForm::schema())
            ->statePath('data')
            ->model($this->record ?? News::class)
            ->columns(2);
    }

    public function create(): void
    {
        $this->record = null;
        $this->resetValidation();
        $this->form->fill(['published_at' => now()]);
        $this->showModal = true;
    }

    public function edit(News $news): void
    {
        $this->record = $news;
        $this->resetValidation();
        $this->form->fill($news->attributesToArray());
        $this->showModal = true;
    }

    public function updatedShowModal($value): void
    {
        if (! $value) {
            $this->resetValidation();
            $this->record = null;
            $this->form->fill();
        }
    }

    public function save(): void
    {
        $data = $this->form->getState();
        $data['updated_by'] = Auth::id();

        DB::beginTransaction();

        try {
            if ($this->record) {
                $this->newsRepository->update($this->record->id, $data);

                DB::commit();

                $this->dispatch('notify', text: 'News updated successfully.', variant: 'success');
            } else {
                $data['created_by'] = Auth::id();
                $this->newsRepository->create($data);

                DB::commit();

                $this->dispatch('notify', text: 'News created successfully.', variant: 'success');
            }

            $this->showModal = false;
            $this->dispatch('refresh');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('notify', text: 'Error: '.$e->getMessage(), variant: 'danger');
        }
    }

    public function delete(News $news): void
    {
        DB::beginTransaction();

        try {
            // Delete image file
            if ($news->image) {
                Storage::disk('public')->delete($news->image);
            }

            $this->newsRepository->delete($news->id);

            DB::commit();

            $this->dispatch('notify', text: 'News deleted successfully.', variant: 'success');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('notify', text: 'Error: '.$e->getMessage(), variant: 'danger');
        }
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedPerPage()
    {
        $this->resetPage();
    }

    public function render()
    {
        return view('livewire.news.index', [
            'news' => $this->newsRepository->searchByTerm($this->search, $this->perPage),
        ]);
    }
}
