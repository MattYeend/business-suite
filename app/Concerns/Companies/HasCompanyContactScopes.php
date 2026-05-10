<?php

namespace App\Concerns\Companies;

use Illuminate\Database\Eloquent\Builder;

trait HasCompanyContactScopes
{
    /**
     * Scope to filter primary contacts.
     *
     * @param Builder $query
     *
     * @return Builder
     */
    public function scopePrimary(Builder $query): Builder
    {
        return $query->where('is_primary', true);
    }

    /**
     * Scope to filter real contacts.
     *
     * @param Builder $query
     *
     * @return Builder
     */
    public function scopeReal(Builder $query): Builder
    {
        return $query->where('is_real', true);
    }

    /**
     * Scope to filter contacts by company.
     *
     * @param Builder $query
     * @param int $companyId
     *
     * @return Builder
     */
    public function scopeForCompany(Builder $query, int $companyId): Builder
    {
        return $query->where('company_id', $companyId);
    }

    /**
     * Scope to filter contacts with email.
     *
     * @param Builder $query
     *
     * @return Builder
     */
    public function scopeWithEmail(Builder $query): Builder
    {
        return $query->whereNotNull('email');
    }

    /**
     * Scope to filter contacts with phone.
     *
     * @param Builder $query
     *
     * @return Builder
     */
    public function scopeWithPhone(Builder $query): Builder
    {
        return $query->where(function ($q) {
            $q->whereNotNull('phone')
                ->orWhereNotNull('mobile');
        });
    }
}
