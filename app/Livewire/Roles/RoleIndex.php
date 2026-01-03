<?php

namespace App\Livewire\Roles;

use App\Forms\RoleForm;
use App\Models\Role;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.app')]
#[Title('Roles')]
class RoleIndex extends Component implements HasForms
{
    use InteractsWithForms;
    use WithPagination;

    #[Url]
    public $search = '';

    #[Url]
    public $perPage = 10;

    public ?array $data = [];

    public ?Role $record = null;

    public $showModal = false;

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema(RoleForm::schema())
            ->statePath('data')
            ->model($this->record ?? Role::class)
            ->columns(1);
    }

    public function create(): void
    {
        $this->record = null;
        $this->form->fill();
        $this->showModal = true;
    }

    public function edit(Role $role): void
    {
        $this->record = $role;
        $this->form->fill($role->attributesToArray());
        $this->showModal = true;
    }

    public function save(): void
    {
        DB::beginTransaction();

        try {
            $data = $this->form->getState();

            if ($this->record) {
                $this->record->update($data);
                $this->dispatch('notify', text: 'Role updated successfully.', variant: 'success');
            } else {
                Role::create($data);
                $this->dispatch('notify', text: 'Role created successfully.', variant: 'success');
            }

            DB::commit();

            $this->showModal = false;
            $this->dispatch('refresh');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('notify', text: 'Error: '.$e->getMessage(), variant: 'danger');
        }
    }

    public function delete(Role $role): void
    {
        DB::beginTransaction();

        try {
            $role->delete();

            DB::commit();

            $this->dispatch('notify', text: 'Role deleted successfully.', variant: 'success');
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
        return view('livewire.roles.index', [
            'roles' => Role::withCount('users')
                ->where('name', 'like', '%'.$this->search.'%')
                ->orWhere('slug', 'like', '%'.$this->search.'%')
                ->paginate($this->perPage),
        ]);
    }
}
