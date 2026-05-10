<?php

namespace App\Concerns\Users;

use Illuminate\Database\Eloquent\Builder;

/**
 * Trait providing query scopes for User model filtering.
 *
 * @mixin \App\Models\User
 */
trait HasUserScopes
{
    /**
     * Scope a query to only include regular users.
     *
     * @param  Builder $query
     *
     * @return Builder
     */
    public function scopeUsers(Builder $query): Builder
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
    public function scopeAdmins(Builder $query): Builder
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
    public function scopeSuperAdmins(Builder $query): Builder
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
    public function scopeReal(Builder $query): Builder
    {
        return $query->where('is_real', true);
    }
}
