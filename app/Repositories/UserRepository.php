<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

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
     *
     * If is_active = true, email_verified_at is set immediately.
     * If is_active = false, send activation email via queue.
     */
    public function createWithRoles(array $userData, array $roleIds, ?int $defaultRoleId = null): User
    {
        return DB::transaction(function () use ($userData, $roleIds, $defaultRoleId) {
            $isActive = $userData['is_active'] ?? false;

            // If active, set email_verified_at immediately
            if ($isActive) {
                $userData['email_verified_at'] = now();
            } else {
                // Ensure email_verified_at is null for inactive users
                $userData['email_verified_at'] = null;
            }

            $user = $this->model->create($userData);
            $user->syncRoles($roleIds, $defaultRoleId);

            // If not active, send activation email via queue
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

            // Handle activation status change
            if (isset($userData['is_active'])) {
                $wasActive = $user->is_active;
                $isNowActive = $userData['is_active'];

                // If becoming active and was not verified, verify now
                if ($isNowActive && ! $wasActive && ! $user->email_verified_at) {
                    $userData['email_verified_at'] = now();
                }

                // If becoming inactive and was active, user needs re-verification
                if (! $isNowActive && $wasActive) {
                    $userData['email_verified_at'] = null;
                }
            }

            $user->update($userData);
            $user->syncRoles($roleIds, $defaultRoleId);

            // If user is now inactive and email not verified, send verification email
            if (isset($userData['is_active']) && ! $userData['is_active'] && ! $user->email_verified_at) {
                $user->sendEmailVerificationNotification();
            }

            return true;
        });
    }
}
