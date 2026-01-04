<?php

namespace App\Livewire\AboutUs;

use App\Forms\AboutUsForm;
use App\Models\AboutUs;
use App\Repositories\Contracts\AboutUsRepositoryInterface;
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
#[Title('About Us')]
class AboutUsIndex extends Component implements HasForms
{
    use InteractsWithForms;
    use WithPagination;

    #[Url]
    public $search = '';

    #[Url]
    public $perPage = 10;

    public ?array $data = [];

    public ?AboutUs $record = null;

    public $showModal = false;

    protected AboutUsRepositoryInterface $aboutUsRepository;

    public function boot(AboutUsRepositoryInterface $aboutUsRepository): void
    {
        $this->aboutUsRepository = $aboutUsRepository;
    }

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema(AboutUsForm::schema())
            ->statePath('data')
            ->model($this->record ?? AboutUs::class)
            ->columns(2);
    }

    public function create(): void
    {
        $this->record = null;
        $this->form->fill();
        $this->showModal = true;
    }

    public function edit(AboutUs $aboutUs): void
    {
        $this->record = $aboutUs;
        $this->form->fill($aboutUs->attributesToArray());
        $this->showModal = true;
    }

    public function save(): void
    {
        $data = $this->form->getState();
        $data['updated_by'] = Auth::id();

        DB::beginTransaction();

        try {
            if ($this->record) {
                $this->aboutUsRepository->update($this->record->id, $data);

                DB::commit();

                $this->dispatch('notify', text: 'About Us updated successfully.', variant: 'success');
            } else {
                $data['created_by'] = Auth::id();
                $this->aboutUsRepository->create($data);

                DB::commit();

                $this->dispatch('notify', text: 'About Us created successfully.', variant: 'success');
            }

            $this->showModal = false;
            $this->dispatch('refresh');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('notify', text: 'Error: '.$e->getMessage(), variant: 'danger');
        }
    }

    public function delete(AboutUs $aboutUs): void
    {
        DB::beginTransaction();

        try {
            // Delete logo file
            if ($aboutUs->logo) {
                Storage::disk('public')->delete($aboutUs->logo);
            }

            $this->aboutUsRepository->delete($aboutUs->id);

            DB::commit();

            $this->dispatch('notify', text: 'About Us deleted successfully.', variant: 'success');
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
        return view('livewire.about-us.index', [
            'items' => $this->aboutUsRepository->searchByTerm($this->search, $this->perPage),
        ]);
    }
}
