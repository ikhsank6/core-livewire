<?php

namespace App\Livewire\Users;

use App\Forms\UserForm;
use App\Livewire\Concerns\HasTableView;
use App\Models\Notification;
use App\Models\User;
use App\Repositories\Contracts\RoleRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.app')]
#[Title('Users')]
class UserIndex extends Component implements HasForms
{
    use HasTableView;
    use InteractsWithForms;
    use WithPagination;

    #[Url]
    public $search = '';

    #[Url]
    public $perPage = 10;

    public ?array $data = [];

    public ?User $record = null;

    public $showModal = false;

    public array $selectedUsers = [];

    public bool $selectAll = false;

    #[Url]
    public string $filterStatus = '';

    #[Url]
    public array $filterRoles = [];

    public bool $showFilterModal = false;

    public string $pendingStatus = '';

    public array $pendingRoles = [];

    protected UserRepositoryInterface $userRepository;

    protected RoleRepositoryInterface $roleRepository;

    public function boot(
        UserRepositoryInterface $userRepository,
        RoleRepositoryInterface $roleRepository
    ): void {
        $this->userRepository = $userRepository;
        $this->roleRepository = $roleRepository;
    }

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
        $this->resetValidation();
        $this->form->fill();
        $this->showModal = true;
    }

    public function edit(User $user): void
    {
        $this->record = $user;
        $this->resetValidation();
        $formData = $user->attributesToArray();

        // Ensure IDs are strings for Filament state matching
        $formData['roles'] = $user->roles->pluck('id')->map(fn ($id) => (string) $id)->toArray();

        $defaultRole = $user->roles()->wherePivot('is_default', true)->first();
        $formData['default_role_id'] = $defaultRole ? (string) $defaultRole->id : null;

        $this->form->fill($formData);
        $this->showModal = true;
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->record = null;
        $this->resetValidation();
        $this->form->fill();
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
        // Validate form first - this will show errors under each field
        $data = $this->form->getState();

        $roleIds = $data['roles'] ?? [];
        $defaultRoleId = $data['default_role_id'] ?? null;

        // Unset relation data as it's handled separately
        unset($data['roles'], $data['default_role_id']);

        // Handle password logic efficiently
        if (array_key_exists('password', $data) && empty($data['password'])) {
            unset($data['password']);
        }

        try {
            $isUpdate = (bool) $this->record;

            DB::transaction(function () use ($data, $roleIds, $defaultRoleId, $isUpdate) {
                if ($isUpdate) {
                    $this->userRepository->updateWithRoles(
                        $this->record->id,
                        $data,
                        $roleIds,
                        $defaultRoleId
                    );
                } else {
                    $user = $this->userRepository->createWithRoles($data, $roleIds, $defaultRoleId);

                    // Send notification to Super Admin
                    $superAdminRole = $this->roleRepository->findBySlug('super-admin');

                    if ($superAdminRole) {
                        Notification::create([
                            'from_role_id' => Auth::user()->role_id,
                            'to_role_id' => $superAdminRole->id,
                            'message' => 'New user "'.$user->name.'" has been created by '.Auth::user()->name.'.',
                            'url' => null,
                            'id_reference' => $user->id,
                            'read' => false,
                        ]);
                    }
                }
            });

            $message = $isUpdate ? 'User updated successfully.' : 'User created successfully.';
            $this->dispatch('notify', text: $message, variant: 'success');
            $this->showModal = false;
            $this->dispatch('refresh');
        } catch (\Exception $e) {
            $this->dispatch('notify', text: 'Error: '.$e->getMessage(), variant: 'danger');
        }
    }

    public function delete(User $user): void
    {
        try {
            DB::transaction(function () use ($user) {
                $this->userRepository->delete($user->id);
            });

            $this->dispatch('notify', text: 'User deleted successfully.', variant: 'success');
        } catch (\Exception $e) {
            $this->dispatch('notify', text: 'Error: '.$e->getMessage(), variant: 'danger');
        }
    }

    public function resendActivation(User $user): void
    {
        try {
            // Check if user email is already verified
            if ($user->hasVerifiedEmail()) {
                $this->dispatch('notify', text: 'User email is already verified.', variant: 'danger');

                return;
            }

            // Send verification email
            $user->sendEmailVerificationNotification();

            $this->dispatch('notify', text: 'Activation email has been sent to '.$user->email, variant: 'success');
        } catch (\Exception $e) {
            $this->dispatch('notify', text: 'Error: '.$e->getMessage(), variant: 'danger');
        }
    }

    public function updatedSelectAll(bool $value): void
    {
        if ($value) {
            $this->selectedUsers = User::whereNull('email_verified_at')
                ->when($this->search, fn ($q) => $q->where(function ($q) {
                    $q->where('name', 'like', "%{$this->search}%")
                      ->orWhere('email', 'like', "%{$this->search}%");
                }))
                ->pluck('uuid')
                ->toArray();
        } else {
            $this->selectedUsers = [];
        }
    }

    public function updatedSelectedUsers(): void
    {
        $total = User::whereNull('email_verified_at')
            ->when($this->search, fn ($q) => $q->where(function ($q) {
                $q->where('name', 'like', "%{$this->search}%")
                  ->orWhere('email', 'like', "%{$this->search}%");
            }))
            ->count();

        $this->selectAll = $total > 0 && count($this->selectedUsers) >= $total;
    }

    public function bulkResendActivation(): void
    {
        if (empty($this->selectedUsers)) {
            $this->dispatch('notify', text: 'Pilih setidaknya satu pengguna.', variant: 'danger');
            return;
        }

        $sent = 0;
        foreach ($this->selectedUsers as $uuid) {
            $user = User::where('uuid', $uuid)->whereNull('email_verified_at')->first();
            if ($user) {
                $user->sendEmailVerificationNotification();
                $sent++;
            }
        }

        $this->selectedUsers = [];
        $this->selectAll = false;

        $this->dispatch('notify',
            text: "Email verifikasi berhasil dikirim ke {$sent} pengguna.",
            variant: 'success'
        );
    }

    public function clearSelection(): void
    {
        $this->selectedUsers = [];
        $this->selectAll = false;
    }

    public function reload(): void
    {
        $this->search = '';
        $this->selectedUsers = [];
        $this->selectAll = false;
        $this->resetPage();
    }

    public function openFilterModal(): void
    {
        $this->pendingStatus = $this->filterStatus;
        $this->pendingRoles  = $this->filterRoles;
        $this->showFilterModal = true;
    }

    public function applyFilters(): void
    {
        $this->filterStatus    = $this->pendingStatus;
        $this->filterRoles     = $this->pendingRoles;
        $this->showFilterModal = false;
        $this->resetPage();
    }

    public function clearFilters(): void
    {
        $this->filterStatus    = '';
        $this->filterRoles     = [];
        $this->pendingStatus   = '';
        $this->pendingRoles    = [];
        $this->showFilterModal = false;
        $this->resetPage();
    }

    public function getActiveFilterCountProperty(): int
    {
        return (int) (!empty($this->filterStatus)) + (\count($this->filterRoles) > 0 ? 1 : 0);
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedPerPage(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $roles = $this->roleRepository->all();

        return view('livewire.users.index', [
            'users' => $this->userRepository->searchWithFilters(
                $this->search,
                $this->perPage,
                $this->filterStatus,
                $this->filterRoles,
            ),
            'roles' => $roles,
        ]);
    }
}
