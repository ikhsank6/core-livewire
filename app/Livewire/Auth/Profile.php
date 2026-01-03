<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('components.layouts.app')]
#[Title('Profile')]
class Profile extends Component
{
    use WithFileUploads;

    public string $name = '';

    public string $email = '';

    public $avatar;

    public ?string $currentAvatar = null;

    public function mount(): void
    {
        /** @var User $user */
        $user = Auth::user();

        $this->name = $user->name;
        $this->email = $user->email;
        $this->currentAvatar = $user->avatar ?? null;
    }

    /**
     * Update name on change
     */
    public function updateName(): void
    {
        $this->validate([
            'name' => 'required|string|max:255',
        ]);

        try {
            /** @var User $user */
            $user = Auth::user();
            $user->update(['name' => $this->name]);

            $this->dispatch('notify', text: 'Name updated successfully.', variant: 'success');
        } catch (\Exception $e) {
            $this->dispatch('notify', text: 'Error: '.$e->getMessage(), variant: 'danger');
        }
    }

    /**
     * Update email on change
     */
    public function updateEmail(): void
    {
        $this->validate([
            'email' => 'required|email|max:255|unique:users,email,'.Auth::id(),
        ]);

        try {
            /** @var User $user */
            $user = Auth::user();
            $user->update(['email' => $this->email]);

            $this->dispatch('notify', text: 'Email updated successfully.', variant: 'success');
        } catch (\Exception $e) {
            $this->dispatch('notify', text: 'Error: '.$e->getMessage(), variant: 'danger');
        }
    }

    /**
     * Handle avatar upload - triggered automatically when avatar property changes
     */
    public function updatedAvatar(): void
    {
        $this->validate([
            'avatar' => 'image|max:2048',
        ]);

        try {
            /** @var User $user */
            $user = Auth::user();

            // Delete old avatar if exists
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }

            // Store new avatar
            $avatarPath = $this->avatar->store('avatars', 'public');
            $user->update(['avatar' => $avatarPath]);
            $this->currentAvatar = $avatarPath;

            $this->reset('avatar');

            $this->dispatch('notify', text: 'Photo updated successfully.', variant: 'success');
        } catch (\Exception $e) {
            $this->dispatch('notify', text: 'Error: '.$e->getMessage(), variant: 'danger');
        }
    }

    /**
     * Delete current avatar
     */
    public function deleteAvatar(): void
    {
        try {
            /** @var User $user */
            $user = Auth::user();

            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }

            $user->update(['avatar' => null]);
            $this->currentAvatar = null;

            $this->dispatch('notify', text: 'Photo removed successfully.', variant: 'success');
        } catch (\Exception $e) {
            $this->dispatch('notify', text: 'Error: '.$e->getMessage(), variant: 'danger');
        }
    }

    /**
     * Switch currently active role
     */
    public function switchRole($roleId): void
    {
        try {
            /** @var User $user */
            $user = Auth::user();
            $role = \App\Models\Role::findOrFail($roleId);

            if ($user->setActiveRole($role)) {
                // Clear menu cache to reflect new role
                app(\App\Services\MenuService::class)->clearMenuCache($roleId);

                $this->dispatch('notify', text: 'Switched to '.$role->name.' role.', variant: 'success');
                $this->js('window.location.reload()');
            } else {
                $this->dispatch('notify', text: 'Unauthorized role switch.', variant: 'danger');
            }
        } catch (\Exception $e) {
            $this->dispatch('notify', text: 'Error: '.$e->getMessage(), variant: 'danger');
        }
    }

    /**
     * Set default role for login
     */
    public function setDefaultRole($roleId): void
    {
        try {
            /** @var User $user */
            $user = Auth::user();

            // Get all role IDs for this user
            $roleIds = $user->roles->pluck('id')->toArray();

            if (! in_array($roleId, $roleIds)) {
                throw new \Exception('Unauthorized role selection.');
            }

            // Sync with new default
            $user->syncRoles($roleIds, $roleId);

            // Refresh the user instance relationship to update the UI
            $user->load('roles');

            $this->dispatch('notify', text: 'Default role updated successfully.', variant: 'success');
        } catch (\Exception $e) {
            $this->dispatch('notify', text: 'Error: '.$e->getMessage(), variant: 'danger');
        }
    }

    public function render()
    {
        return view('livewire.auth.profile', [
            'userRoles' => Auth::user()->roles,
            'activeRoleId' => Auth::user()->role_id,
        ]);
    }
}
