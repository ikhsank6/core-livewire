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
     */
    public function createWithRoles(array $userData, array $roleIds, ?int $defaultRoleId = null): User
    {
        return DB::transaction(function () use ($userData, $roleIds, $defaultRoleId) {
            $user = $this->model->create($userData);
            $user->syncRoles($roleIds, $defaultRoleId);

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
            $user->update($userData);
            $user->syncRoles($roleIds, $defaultRoleId);

            return true;
        });
    }
}
