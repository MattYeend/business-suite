<?php

namespace App\Concerns;

use Illuminate\Database\Eloquent\Builder;

trait HasCompanyPhoneScopes
{
    /**
     * Scope a query to only include real phones.
     *
     * @param  Builder $query
     *
     * @return void
     */
    public function scopeReal(Builder $query): void
    {
        $query->where('is_real', true);
    }

    /**
     * Scope a query to only include primary phones.
     *
     * @param  Builder $query
     *
     * @return void
     */
    public function scopePrimary(Builder $query): void
    {
        $query->where('is_primary', true);
    }

    /**
     * Scope a query to only include phones of a specific type.
     *
     * @param  Builder $query
     * @param  string $type
     *
     * @return void
     */
    public function scopeOfType(Builder $query, string $type): void
    {
        $query->where('type', $type);
    }

    /**
     * Scope a query to only include main phones.
     *
     * @param  Builder $query
     *
     * @return void
     */
    public function scopeMain(Builder $query): void
    {
        $query->where('type', 'main');
    }

    /**
     * Scope a query to only include fax phones.
     *
     * @param  Builder $query
     *
     * @return void
     */
    public function scopeFax(Builder $query): void
    {
        $query->where('type', 'fax');
    }

    /**
     * Scope a query to only include toll-free phones.
     *
     * @param  Builder $query
     *
     * @return void
     */
    public function scopeTollFree(Builder $query): void
    {
        $query->where('type', 'toll_free');
    }

    /**
     * Scope a query to only include mobile phones.
     *
     * @param  Builder $query
     *
     * @return void
     */
    public function scopeMobile(Builder $query): void
    {
        $query->where('type', 'mobile');
    }
}
