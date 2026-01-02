<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id', // Active role
        'is_active',
        'avatar',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Get the currently active role (for menu access, permissions, etc.)
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Get all roles that the user has (many-to-many).
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'role_user')
            ->withPivot('is_default')
            ->withTimestamps();
    }

    /**
     * Get the default role for login.
     */
    public function getDefaultRole(): ?Role
    {
        return $this->roles()->wherePivot('is_default', true)->first()
            ?? $this->roles()->first();
    }

    /**
     * Set the active role.
     */
    public function setActiveRole(Role $role): bool
    {
        if (! $this->roles()->where('roles.id', $role->id)->exists()) {
            return false;
        }

        $this->update(['role_id' => $role->id]);

        return true;
    }

    /**
     * Sync user roles and set default.
     */
    public function syncRoles(array $roleIds, ?int $defaultRoleId = null): void
    {
        // Prepare pivot data with is_default
        $syncData = [];
        foreach ($roleIds as $roleId) {
            $syncData[$roleId] = ['is_default' => $roleId == $defaultRoleId];
        }

        $this->roles()->sync($syncData);

        // Set active role to default role or first role
        $activeRoleId = $defaultRoleId ?? ($roleIds[0] ?? null);
        if ($activeRoleId) {
            $this->update(['role_id' => $activeRoleId]);
        }
    }

    /**
     * Check if the user has access to a specific menu.
     */
    public function hasMenuAccess(Menu $menu): bool
    {
        if (! $this->role) {
            return false;
        }

        return $this->role->menus()->where('menus.id', $menu->id)->exists();
    }

    /**
     * Check if the user has access to a specific route.
     */
    public function hasRouteAccess(string $routeName): bool
    {
        if (! $this->role) {
            return false;
        }

        return $this->role->menus()->where('route', $routeName)->exists();
    }

    /**
     * Check if user is active.
     */
    public function isActive(): bool
    {
        return $this->is_active;
    }
}
