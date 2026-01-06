<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    /**
     * Search users with role relation.
     */
    public function searchWithRoles(?string $term, int $perPage = 10): LengthAwarePaginator
    {
        $query = $this->model->with('role', 'roles');

        if ($term) {
            $query->where(function ($q) use ($term) {
                $q->where('name', 'like', "%{$term}%")
                    ->orWhere('email', 'like', "%{$term}%");
            });
        }

        return $query->latest()->paginate($perPage);
    }

    /**
     * Create user with roles.
     */
    public function createWithRoles(array $userData, array $roleIds, ?int $defaultRoleId = null): User
    {
        return DB::transaction(function () use ($userData, $roleIds, $defaultRoleId) {
            $isActive = $userData['is_active'] ?? false;

            if ($isActive) {
                $userData['email_verified_at'] = now();
            } else {
                $userData['email_verified_at'] = null;
            }

            $user = $this->model->create($userData);
            $user->syncRoles($roleIds, $defaultRoleId);

            if (! $isActive) {
                $user->sendEmailVerificationNotification();
            }

            return $user->fresh(['role', 'roles']);
        });
    }

    /**
     * Update user with roles.
     */
    public function updateWithRoles(int $userId, array $userData, array $roleIds, ?int $defaultRoleId = null): bool
    {
        return DB::transaction(function () use ($userId, $userData, $roleIds, $defaultRoleId) {
            $user = $this->findOrFail($userId);

            if (isset($userData['is_active'])) {
                $wasActive = $user->is_active;
                $isNowActive = $userData['is_active'];

                if ($isNowActive && ! $wasActive && ! $user->email_verified_at) {
                    $userData['email_verified_at'] = now();
                }

                if (! $isNowActive && $wasActive) {
                    $userData['email_verified_at'] = null;
                }
            }

            $user->update($userData);
            $user->syncRoles($roleIds, $defaultRoleId);

            if (isset($userData['is_active']) && ! $userData['is_active'] && ! $user->email_verified_at) {
                $user->sendEmailVerificationNotification();
            }

            return true;
        });
    }

    /**
     * Update user password.
     */
    public function updatePassword(int $userId, string $newPassword): bool
    {
        return $this->update($userId, [
            'password' => Hash::make($newPassword),
        ]);
    }

    /**
     * Update user avatar.
     */
    public function updateAvatar(int $userId, $file): string
    {
        return DB::transaction(function () use ($userId, $file) {
            $user = $this->findOrFail($userId);

            // Delete old avatar if exists
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }

            // Store new avatar
            $avatarPath = $file->store('avatars', 'public');
            $user->update(['avatar' => $avatarPath]);

            return $avatarPath;
        });
    }

    /**
     * Delete user avatar.
     */
    public function deleteAvatar(int $userId): bool
    {
        return DB::transaction(function () use ($userId) {
            $user = $this->findOrFail($userId);

            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }

            return $user->update(['avatar' => null]);
        });
    }

    /**
     * Set default role for login.
     */
    public function setDefaultRole(int $userId, int $roleId): bool
    {
        return DB::transaction(function () use ($userId, $roleId) {
            $user = $this->findOrFail($userId);
            $roleIds = $user->roles->pluck('id')->toArray();

            if (! in_array($roleId, $roleIds)) {
                throw new \Exception('Unauthorized role selection.');
            }

            $user->syncRoles($roleIds, $roleId);

            return true;
        });
    }

    /**
     * Register a new user.
     */
    public function register(array $data): User
    {
        return DB::transaction(function () use ($data) {
            $defaultRole = \App\Models\Role::where('slug', 'user')->first();

            $user = $this->model->create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'role_id' => $defaultRole?->id,
                'is_active' => false,
            ]);

            if ($defaultRole) {
                $user->roles()->attach($defaultRole->id, ['is_default' => true]);
            }

            event(new \Illuminate\Auth\Events\Registered($user));

            return $user;
        });
    }

    /**
     * Set the active role for a user.
     */
    public function setActiveRole(int $userId, int $roleId): bool
    {
        return DB::transaction(function () use ($userId, $roleId) {
            $user = $this->findOrFail($userId);
            $role = \App\Models\Role::findOrFail($roleId);

            return $user->setActiveRole($role);
        });
    }
}
