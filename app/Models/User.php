<?php

namespace App\Models;

use App\Concerns\HasTeam;
use App\Concerns\Users\HasUserRoles;
use App\Concerns\Users\HasUserScopes;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
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
    'deleted_by',
    'restored_at',
    'restored_by',
])]

#[Hidden([
    'password',
    'two_factor_secret',
    'two_factor_recovery_codes',
    'remember_token',
])]

/**
 *@mixin \Spatie\Permission\Traits\HasRoles
 *
 * @method bool can(string $ability, mixed $arguments = [])
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property bool $is_user
 * @property bool $is_admin
 * @property bool $is_super_admin
 * @property bool $is_real
 * @property int|null $team_id
 * @property string|null $phone
 * @property string $timezone
 * @property string $locale
 * @property array|null $meta
 *
 * @property-read string $teamName
 * @property-read string $initials
 * @property-read string $roleDisplay
 * @property-read string|null $primaryRole
 * @property-read string $rolesList
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
        HasUserRoles,
        HasUserScopes,
        Authorizable;

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
     * @return string
     */
    public function getTeamNameAttribute(): string
    {
        return $this->resolveTeamName($this->team_id);
    }

    /**
     * Get the user's initials.
     *
     * @return string
     */
    public function getInitialsAttribute(): string
    {
        $words = explode(' ', $this->name);

        return count($words) >= 2
            ? $this->getInitialsFromMultipleWords($words)
            : $this->getInitialsFromSingleWord($this->name);
    }

    /**
     * Get the user's role display name.
     *
     * @return string
     */
    public function getRoleDisplayAttribute(): string
    {
        return match (true) {
            $this->is_super_admin => 'Super Admin',
            $this->is_admin => 'Admin',
            $this->is_user => 'User',
            default => 'Unknown'
        };
    }

    /**
     * Get the user's primary role name.
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
     * @return string
     */
    public function getRolesListAttribute(): string
    {
        return $this->roles->pluck('name')->implode(', ');
    }

    /**
     * Assign role within team context.
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
        $effectiveTeamId = $teamId ?? $this->team_id;

        $this->executeInTeamContext(
            fn () => $this->assignRole($roles),
            $effectiveTeamId
        );

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
        return $this->executeInTeamContext(
            fn () => $this->hasPermissionTo($permission),
            $this->team_id
        );
    }

    /**
     * Check if user has role in their current team.
     *
     * @param  string $role
     *
     * @return bool
     */
    public function hasRoleInTeam(string $role): bool
    {
        return $this->executeInTeamContext(
            fn () => $this->hasRole($role),
            $this->team_id
        );
    }

    /**
     * Execute a callback within the team context.
     *
     * @param  callable $callback
     * @param  int|null $teamId
     *
     * @return mixed
     */
    protected function executeInTeamContext(
        callable $callback,
        ?int $teamId
    ): mixed {
        if (! $teamId) {
            return $callback();
        }

        setPermissionsTeamId($teamId);
        $result = $callback();
        setPermissionsTeamId(null);

        return $result;
    }

    /**
     * Resolve team name from team ID.
     *
     * @param  int|null $teamId
     *
     * @return string
     */
    protected function resolveTeamName(?int $teamId): string
    {
        if ($teamId === null) {
            return 'No Team';
        }

        $teamNames = $this->getTeamNamesMapping();

        return $teamNames[$teamId] ?? "Team {$teamId}";
    }

    /**
     * Get the mapping of team IDs to names.
     *
     * @return array<int, string>
     */
    protected function getTeamNamesMapping(): array
    {
        return [
            1 => 'Head Office',
            2 => 'Sales Department',
            3 => 'IT Department',
            4 => 'HR Department',
            5 => 'Finance Department',
            6 => 'Marketing Department',
        ];
    }

    /**
     * Extract initials from multiple words.
     *
     * @param  array $words
     *
     * @return string
     */
    protected function getInitialsFromMultipleWords(array $words): string
    {
        return strtoupper(
            substr($words[0], 0, 1) . substr($words[1], 0, 1)
        );
    }

    /**
     * Extract initials from a single word.
     *
     * @param  string $name
     *
     * @return string
     */
    protected function getInitialsFromSingleWord(string $name): string
    {
        return strtoupper(substr($name, 0, 2));
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
