<?php

namespace App\Concerns;

use App\Models\CompanyPhone;
use Illuminate\Database\Eloquent\Builder;

/**
 * @mixin CompanyPhone
 */

trait HasCompanyPhoneScopes
{
    /**
     * Scope a query to only include real phones.
     *
     * @param  Builder $query
     *
     * @return Builder
     */
    public function scopeReal(Builder $query): Builder
    {
        return $query->where('is_real', true);
    }

    /**
     * Scope a query to only include primary phones.
     *
     * @param  Builder $query
     *
     * @return Builder
     */
    public function scopePrimary(Builder $query): Builder
    {
        return $query->where('is_primary', true);
    }

    /**
     * Scope a query to only include phones of a specific type.
     *
     * @param  Builder $query
     * @param  string $type
     *
     * @return Builder
     */
    public function scopeOfType(Builder $query, string $type): Builder
    {
        return $query->where('type', $type);
    }

    /**
     * Scope a query to only include main phones.
     *
     * @param  Builder $query
     *
     * @return Builder
     */
    public function scopeMain(Builder $query): Builder
    {
        return $query->where('type', CompanyPhone::TYPE_MAIN);
    }

    /**
     * Scope a query to only include fax phones.
     *
     * @param  Builder $query
     *
     * @return Builder
     */
    public function scopeFax(Builder $query): Builder
    {
        return $query->where('type', CompanyPhone::TYPE_FAX);
    }

    /**
     * Scope a query to only include toll-free phones.
     *
     * @param  Builder $query
     *
     * @return Builder
     */
    public function scopeTollFree(Builder $query): Builder
    {
        return $query->where('type', CompanyPhone::TYPE_TOLL_FREE);
    }

    /**
     * Scope a query to only include mobile phones.
     *
     * @param  Builder $query
     *
     * @return Builder
     */
    public function scopeMobile(Builder $query): Builder
    {
        return $query->where('type', CompanyPhone::TYPE_MOBILE);
    }
}
