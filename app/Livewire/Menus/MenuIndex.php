<?php

namespace App\Livewire\Menus;

use App\Forms\MenuForm;
use App\Models\Menu;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.app')]
#[Title('Menus')]
class MenuIndex extends Component implements HasForms
{
    use InteractsWithForms;
    use WithPagination;

    #[Url]
    public $search = '';

    #[Url]
    public $perPage = 50;

    public ?array $data = [];

    public ?Menu $record = null;

    public $showModal = false;

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema(MenuForm::schema())
            ->statePath('data')
            ->model($this->record ?? Menu::class)
            ->columns(1);
    }

    public function create(): void
    {
        $this->record = null;
        $this->form->fill([
            'order' => Menu::max('order') + 1, // Auto-set next order
        ]);
        $this->showModal = true;
    }

    public function edit(Menu $menu): void
    {
        $this->record = $menu;
        $this->form->fill($menu->attributesToArray());
        $this->showModal = true;
    }

    public function save(): void
    {
        try {
            $data = $this->form->getState();

            if ($this->record) {
                $this->record->update($data);
                $this->dispatch('notify', text: 'Menu updated successfully.', variant: 'success');
            } else {
                Menu::create($data);
                $this->dispatch('notify', text: 'Menu created successfully.', variant: 'success');
            }

            $this->showModal = false;
            $this->dispatch('refresh');
        } catch (\Exception $e) {
            $this->dispatch('notify', text: 'Error: '.$e->getMessage(), variant: 'danger');
        }
    }

    public function delete(Menu $menu): void
    {
        try {
            $menu->delete();
            $this->dispatch('notify', text: 'Menu deleted successfully.', variant: 'success');
        } catch (\Exception $e) {
            $this->dispatch('notify', text: 'Error deleting menu: '.$e->getMessage(), variant: 'danger');
        }
    }

    /**
     * Update menu order from drag and drop
     */
    public function updateOrder(array $orderedIds): void
    {
        try {
            foreach ($orderedIds as $index => $id) {
                Menu::where('id', $id)->update(['order' => $index]);
            }
            $this->dispatch('notify', text: 'Menu order updated successfully.', variant: 'success');
        } catch (\Exception $e) {
            $this->dispatch('notify', text: 'Error updating order: '.$e->getMessage(), variant: 'danger');
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
        return view('livewire.menus.index', [
            'menus' => Menu::with('parent')
                ->where(function ($query) {
                    $query->where('name', 'like', '%'.$this->search.'%')
                        ->orWhere('slug', 'like', '%'.$this->search.'%');
                })
                ->orderByRaw('COALESCE(parent_id, id), parent_id IS NOT NULL, `order`')
                ->paginate($this->perPage), // Support custom page size
        ]);
    }
}
