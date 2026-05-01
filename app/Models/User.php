<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Concerns\HasTeam;
use App\Concerns\HasUserRoles;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
// use Illuminate\Support\Collection;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Spatie\Permission\Traits\HasRoles;

#[Fillable([
    'name',
    'email',
    'password',
    'is_user',
    'is_admin',
    'is_super_admin',
    'phone',
    'avatar',
    'timezone',
    'locale',
    'team_id',
    'is_real',
    'meta',
    'created_at',
    'created_by',
    'updated_at',
    'updated_by',
    'deleted_at',
    'deteled_by',
    'restored_at',
    'resotred_by',
])]
#[Hidden([
    'password',
    'two_factor_secret',
    'two_factor_recovery_codes',
    'remember_token',
])]
/**
 * @mixin \Spatie\Permission\Traits\HasRoles
 */
class User extends Authenticatable
{
    /**
     * @use HasFactory<UserFactory>
     * @use Notifiable<Notifiable>
     * @use TwoFactorAuthenticatable<TwoFactorAuthenticatable>
     * @use SoftDeletes<SoftDeletes>
     * @use HasRoles<HasRoles>
     */
    use HasFactory,
        Notifiable,
        TwoFactorAuthenticatable,
        SoftDeletes,
        HasRoles,
        HasTeam,
        HasUserRoles;

    /**
     * Check if the user has standard user permissions.
     *
     * @return bool
     */
    public function isUser(): bool
    {
        return (bool) $this->is_user;
    }

    /**
     * Check if the user has admin permissions.
     *
     * @return bool
     */
    public function isAdmin(): bool
    {
        return (bool) $this->is_admin;
    }

    /**
     * Check if the user has super admin permissions.
     *
     * @return bool
     */
    public function isSuperAdmin(): bool
    {
        return (bool) $this->is_super_admin;
    }

    /**
     * Check if the user is a real user (not a test user).
     *
     * @return bool
     */
    public function isReal(): bool
    {
        return (bool) $this->is_real;
    }

    /**
     * Get team name/identifier.
     *
     * This is a helper method to get a human-readable
     * team name based on team_id.
     * You can customise this method to fit your actual
     * team structure and naming conventions.
     *
     * @return string
     */
    public function getTeamNameAttribute(): string
    {
        // You can customise this based on your team naming convention
        return match ($this->team_id) {
            1 => 'Head Office',
            2 => 'Sales Department',
            3 => 'IT Department',
            4 => 'HR Department',
            5 => 'Finance Department',
            6 => 'Marketing Department',
            default => 'Team ' . $this->team_id,
        };
    }

    /**
     * Scope a query to only include regular users.
     *
     * @param  Builder $query
     *
     * @return Builder
     */
    public function scopeUsers($query)
    {
        return $query->where('is_user', true);
    }

    /**
     * Scope a query to only include admin users.
     *
     * @param  Builder $query
     *
     * @return Builder
     */
    public function scopeAdmins($query)
    {
        return $query->where('is_admin', true);
    }

    /**
     * Scope a query to only include super admin users.
     *
     * @param  Builder $query
     *
     * @return Builder
     */
    public function scopeSuperAdmins($query)
    {
        return $query->where('is_super_admin', true);
    }

    /**
     * Scope a query to only include real users.
     *
     * @param  Builder $query
     *
     * @return Builder
     */
    public function scopeReal($query)
    {
        return $query->where('is_real', true);
    }

    /**
     * Get the user's initials.
     *
     * @return string
     */
    public function getInitialsAttribute(): string
    {
        $words = explode(' ', $this->name);
        if (count($words) >= 2) {
            return strtoupper(substr($words[0], 0, 1)
                . substr($words[1], 0, 1));
        }
        return strtoupper(substr($this->name, 0, 2));
    }

    /**
     * Get the user's role display name.
     *
     * @return string
     */
    public function getRoleDisplayAttribute(): string
    {
        if ($this->is_super_admin) {
            return 'Super Admin';
        }
        if ($this->is_admin) {
            return 'Admin';
        }
        if ($this->is_user) {
            return 'User';
        }
        return 'Unknown';
    }

    /**
     * Get the user's primary role name.
     *
     * This is a helper method to get the name of the user's primary
     * role (the first role assigned).
     *
     * @return string|null
     */
    public function getPrimaryRoleAttribute(): ?string
    {
        return $this->roles->first()?->name;
    }

    /**
     * Get all roles as a comma-separated string.
     *
     * This is a helper method to get a list of all roles assigned to
     * the user in a human-readable format.
     *
     * @return string
     */
    public function getRolesListAttribute(): string
    {
        return $this->roles->pluck('name')->implode(', ');
    }

    /**
     * Assign role within team context.
     *
     * This is a helper method to assign a role to the user while
     * automatically setting the team context for permissions.
     * It ensures that the role is assigned within the correct
     * team scope, which is essential for team-based permissions
     * to work correctly.
     *
     * @param string|array $roles
     * @param int|null $teamId
     *
     * @return self
     */
    public function assignRoleInTeam(
        string|array $roles,
        ?int $teamId = null
    ): self {
        $teamId = $teamId ?? $this->team_id;

        if ($teamId) {
            setPermissionsTeamId($teamId);
        }

        $this->assignRole($roles);

        if ($teamId) {
            setPermissionsTeamId(null);
        }

        return $this;
    }

    /**
     * Check if user has permission in their current team.
     *
     * @param  string $permission
     *
     * @return bool
     */
    public function hasPermissionInTeam(string $permission): bool
    {
        if ($this->team_id) {
            setPermissionsTeamId($this->team_id);
            $hasPermission = $this->hasPermissionTo($permission);
            setPermissionsTeamId(null);
            return $hasPermission;
        }

        return $this->hasPermissionTo($permission);
    }

    /**
     * Check if user has role in their current team.
     *
     * This is a helper method that checks if the user has a specific
     * role within the context of their current team.
     *
     * @param  string $role
     *
     * @return bool
     */
    public function hasRoleInTeam(string $role): bool
    {
        if ($this->team_id) {
            setPermissionsTeamId($this->team_id);
            $hasRole = $this->hasRole($role);
            setPermissionsTeamId(null);
            return $hasRole;
        }

        return $this->hasRole($role);
    }

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
            'two_factor_confirmed_at' => 'datetime',
            'is_user' => 'boolean',
            'is_admin' => 'boolean',
            'is_super_admin' => 'boolean',
            'is_real' => 'boolean',
            'meta' => 'array',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'deleted_at' => 'datetime',
            'restored_at' => 'datetime',
        ];
    }
}
