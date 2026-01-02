<?php

namespace App\Livewire\Users;

use App\Forms\UserForm;
use App\Models\User;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.app')]
#[Title('Users')]
class UserIndex extends Component implements HasForms
{
    use InteractsWithForms;
    use WithPagination;

    #[Url]
    public $search = '';

    #[Url]
    public $perPage = 10;

    public ?array $data = [];

    public ?User $record = null;

    public $showModal = false;

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema(UserForm::schema())
            ->statePath('data')
            ->model($this->record ?? User::class)
            ->columns(1);
    }

    public function create(): void
    {
        $this->record = null;
        $this->form->fill();
        $this->showModal = true;
    }

    public function edit(User $user): void
    {
        $this->record = $user;
        $formData = $user->attributesToArray();

        // Ensure IDs are strings for Filament state matching
        $formData['roles'] = $user->roles->pluck('id')->map(fn ($id) => (string) $id)->toArray();

        $defaultRole = $user->roles()->wherePivot('is_default', true)->first();
        $formData['default_role_id'] = $defaultRole ? (string) $defaultRole->id : null;

        $this->form->fill($formData);
        $this->showModal = true;
    }

    public function save(): void
    {
        try {
            $data = $this->form->getState();
            $roleIds = $data['roles'] ?? [];
            $defaultRoleId = $data['default_role_id'] ?? null;

            // Unset relation data as it's handled separately
            unset($data['roles'], $data['default_role_id']);

            // Handle password logic efficiently
            if (array_key_exists('password', $data) && empty($data['password'])) {
                unset($data['password']);
            }

            if ($this->record) {
                $this->record->update($data);
                $this->record->syncRoles($roleIds, $defaultRoleId);
                $this->dispatch('notify', text: 'User updated successfully.', variant: 'success');
            } else {
                $user = User::create($data);
                $user->syncRoles($roleIds, $defaultRoleId);
                $this->dispatch('notify', text: 'User created successfully.', variant: 'success');
            }

            $this->showModal = false;
            $this->dispatch('refresh');
        } catch (\Exception $e) {
            $this->dispatch('notify', text: 'Error: '.$e->getMessage(), variant: 'danger');
        }
    }

    public function delete(User $user): void
    {
        try {
            $user->delete();
            $this->dispatch('notify', text: 'User deleted successfully.', variant: 'success');
        } catch (\Exception $e) {
            $this->dispatch('notify', text: 'Error deleting user: '.$e->getMessage(), variant: 'danger');
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
        return view('livewire.users.index', [
            'users' => User::with('role')
                ->where('name', 'like', '%'.$this->search.'%')
                ->orWhere('email', 'like', '%'.$this->search.'%')
                ->latest()
                ->paginate($this->perPage),
        ]);
    }
}
