<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
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
        HasRoles;

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
     * Scope a query to only include regular users.
     *
     * @param Builder $query
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
     * @param Builder $query
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
     * @param Builder $query
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
     * @param Builder $query
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
     * Assign a specialised role to the user (in addition to base role).
     *
     * @param string|array $roles
     *
     * @return self
     */
    public function assignSpecialisedRole(string|array $roles): self
    {
        $this->assignRole($roles);
        return $this;
    }

    /**
     * Remove a specialised role from the user.
     *
     * @param string|array $roles
     *
     * @return self
     */
    public function removeSpecialisedRole(string|array $roles): self
    {
        $this->removeRole($roles);
        return $this;
    }

    /**
     * Get all specialised roles (excluding base roles).
     *
     * @return Collection
     */
    public function getSpecialisedRolesAttribute()
    {
        $baseRoles = ['super-admin', 'admin', 'user'];
        return $this->roles->filter(function ($role) use ($baseRoles) {
            return ! in_array($role->name, $baseRoles);
        });
    }

    /**
     * Check if user has any specialised role.
     *
     * @return bool
     */
    public function hasSpecialisedRoles(): bool
    {
        return $this->specialisedRoles->isNotEmpty();
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
