<?php

namespace App\Repositories;

use App\Models\Role;
use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    /**
     * The storage disk for user files.
     */
    private const STORAGE_DISK = 'public';

    /**
     * The avatar storage path.
     */
    private const AVATAR_PATH = 'avatars';

    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    // ==================== PUBLIC METHODS ====================

    /**
     * Get aggregated user statistics for the dashboard.
     *
     * @return array{total:int, active:int, pending:int, suspended:int}
     */
    public function getDashboardStatistics(): array
    {
        return [
            'total'     => $this->count(),
            'active'    => $this->count(['is_active' => true], fn ($q) => $q->whereNotNull('email_verified_at')),
            'pending'   => $this->count(['is_active' => true], fn ($q) => $q->whereNull('email_verified_at')),
            'suspended' => $this->count(['is_active' => false]),
        ];
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
     * Search users with role + status/role filters.
     */
    public function searchWithFilters(?string $term, int $perPage = 10, string $status = '', array $roleIds = []): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        $query = $this->model->with('role', 'roles');

        if ($term) {
            $query->where(function ($q) use ($term) {
                $q->where('name', 'like', "%{$term}%")
                  ->orWhere('email', 'like', "%{$term}%");
            });
        }

        match ($status) {
            'active'    => $query->where('is_active', true)->whereNotNull('email_verified_at'),
            'suspended' => $query->where('is_active', false),
            'pending'   => $query->where('is_active', true)->whereNull('email_verified_at'),
            default     => null,
        };

        if (!empty($roleIds)) {
            $query->whereHas('roles', fn ($q) => $q->whereIn('roles.id', $roleIds));
        }

        return $query->latest()->paginate($perPage);
    }

    /**
     * Which of these emails are already used (soft deleted users included, they still hold the
     * unique index). Returned in lowercase.
     */
    public function existingEmails(array $emails): array
    {
        $emails = array_values(array_unique(array_filter(array_map('mb_strtolower', $emails))));

        if ($emails === []) {
            return [];
        }

        return $this->model->withTrashed()
            ->whereIn(DB::raw('lower(email)'), $emails)
            ->pluck('email')
            ->map(fn ($email) => mb_strtolower($email))
            ->all();
    }

    /**
     * Create an active account that still has to verify its email: a random password is set and
     * mailed together with the verification link. Login stays closed until the link is used.
     */
    public function createInvited(array $userData, array $roleIds, ?int $defaultRoleId = null): User
    {
        return DB::transaction(function () use ($userData, $roleIds, $defaultRoleId) {
            $password = $this->temporaryPassword();

            $user = $this->model->create([
                ...$userData,
                'password' => Hash::make($password),
                'must_change_password' => true,
                'is_active' => true,
            ]);
            $user->syncRoles($roleIds, $defaultRoleId);
            $user->sendEmailVerificationNotification($password);

            return $user->fresh(['role', 'roles']);
        });
    }

    /**
     * Send the verification email again. An account still on a generated password gets a new one,
     * included in the email: only its hash is stored, so the old one cannot be repeated. A password
     * the user chose is never replaced.
     */
    public function resendVerification(User $user): void
    {
        if (! $user->must_change_password) {
            $user->sendEmailVerificationNotification();

            return;
        }

        DB::transaction(function () use ($user) {
            $password = $this->temporaryPassword();

            $user->update(['password' => Hash::make($password)]);
            $user->sendEmailVerificationNotification($password);
        });
    }

    /**
     * Create user with roles.
     */
    public function createWithRoles(array $userData, array $roleIds, ?int $defaultRoleId = null): User
    {
        return DB::transaction(function () use ($userData, $roleIds, $defaultRoleId) {
            $userData = $this->handleEmailVerificationOnCreate($userData);

            $password = null;
            if (! isset($userData['password'])) {
                $password = $this->temporaryPassword();
                $userData['password'] = Hash::make($password);
                $userData['must_change_password'] = true;
            }

            $user = $this->model->create($userData);
            $user->syncRoles($roleIds, $defaultRoleId);

            $this->sendVerificationIfInactive($user, $userData['is_active'] ?? false, $password);

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
            $userData = $this->handleEmailVerificationOnUpdate($user, $userData);

            $user->update($userData);
            $user->syncRoles($roleIds, $defaultRoleId);

            $this->sendVerificationIfInactive($user, $userData['is_active'] ?? true);

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
            'must_change_password' => false,
        ]);
    }

    /**
     * Update user avatar.
     */
    public function updateAvatar(int $userId, $file): string
    {
        return DB::transaction(function () use ($userId, $file) {
            $user = $this->findOrFail($userId);

            // Delete old media if exists
            if ($user->media_id) {
                app(\App\Repositories\Contracts\MediaRepositoryInterface::class)->deleteMedia($user->media_id);
            }

            // Create new media
            $media = app(\App\Repositories\Contracts\MediaRepositoryInterface::class)->upload($file, self::AVATAR_PATH);

            $user->update([
                'avatar' => $media->filename,
                'media_id' => $media->id,
            ]);

            return $media->filename;
        });
    }

    /**
     * Delete user avatar.
     */
    public function deleteAvatar(int $userId): bool
    {
        return DB::transaction(function () use ($userId) {
            $user = $this->findOrFail($userId);

            if ($user->media_id) {
                app(\App\Repositories\Contracts\MediaRepositoryInterface::class)->deleteMedia($user->media_id);
            }

            return $user->update([
                'avatar' => null,
                'media_id' => null,
            ]);
        });
    }

    /**
     * Set default role for login.
     */
    public function setDefaultRole(int $userId, int $roleId): bool
    {
        return DB::transaction(function () use ($userId, $roleId) {
            $user = $this->findOrFail($userId);

            $this->validateUserHasRole($user, $roleId);

            $user->syncRoles($user->roles->pluck('id')->toArray(), $roleId);

            return true;
        });
    }

    /**
     * Register a new user.
     */
    public function register(array $data): User
    {
        return DB::transaction(function () use ($data) {
            $defaultRole = $this->getDefaultRole();

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

            event(new Registered($user));

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
            $role = Role::findOrFail($roleId);

            return $user->setActiveRole($role);
        });
    }

    // ==================== PRIVATE HELPERS ====================

    /**
     * Password mailed to a new account: 8 characters with upper case, lower case and digits, without
     * look-alikes (0 O o 1 l I) so it can be retyped. Short on purpose: the user is asked to replace it
     * at first sign in, and the login form limits attempts.
     */
    private function temporaryPassword(): string
    {
        $sets = ['ABCDEFGHJKLMNPQRSTUVWXYZ', 'abcdefghijkmnpqrstuvwxyz', '23456789'];
        $all = implode('', $sets);
        $pick = fn (string $from): string => $from[random_int(0, strlen($from) - 1)];

        // One of each kind first, then fill up and mix with a cryptographically secure shuffle.
        $characters = array_map($pick, $sets);
        while (count($characters) < 8) {
            $characters[] = $pick($all);
        }

        return (new \Random\Randomizer)->shuffleBytes(implode('', $characters));
    }

    /**
     * Handle email_verified_at logic when creating a user.
     */
    private function handleEmailVerificationOnCreate(array $userData): array
    {
        $isActive = $userData['is_active'] ?? false;
        $userData['email_verified_at'] = $isActive ? now() : null;

        return $userData;
    }

    /**
     * Handle email_verified_at logic when updating a user.
     */
    private function handleEmailVerificationOnUpdate(User|\Illuminate\Database\Eloquent\Model $user, array $userData): array
    {
        if (! isset($userData['is_active'])) {
            return $userData;
        }

        $wasActive = $user->is_active;
        $isNowActive = $userData['is_active'];

        // If becoming active and was not verified, verify now
        if ($isNowActive && ! $wasActive && ! $user->email_verified_at) {
            $userData['email_verified_at'] = now();
        }

        // If becoming inactive, remove verification
        if (! $isNowActive && $wasActive) {
            $userData['email_verified_at'] = null;
        }

        return $userData;
    }

    /**
     * Send email verification notification if user is inactive.
     */
    private function sendVerificationIfInactive(User|\Illuminate\Database\Eloquent\Model $user, bool $isActive, ?string $password = null): void
    {
        if (! $isActive && ! $user->email_verified_at) {
            $password === null
                ? $this->resendVerification($user)
                : $user->sendEmailVerificationNotification($password);
        }
    }

    /**
     * Remove avatar file from storage if exists.
     */
    private function removeAvatarFile(User|\Illuminate\Database\Eloquent\Model $user): void
    {
        if ($user->avatar && Storage::disk(self::STORAGE_DISK)->exists($user->avatar)) {
            Storage::disk(self::STORAGE_DISK)->delete($user->avatar);
        }
    }

    /**
     * Validate that user has the specified role.
     *
     * @throws \Exception
     */
    private function validateUserHasRole(User|\Illuminate\Database\Eloquent\Model $user, int $roleId): void
    {
        $roleIds = $user->roles->pluck('id')->toArray();

        if (! in_array($roleId, $roleIds)) {
            throw new \Exception('Unauthorized role selection.');
        }
    }

    /**
     * Get the default role for registration.
     */
    private function getDefaultRole(): ?Role
    {
        return Role::where('slug', 'user')->first();
    }
}
