<?php

namespace App\Concerns;

use App\Models\CompanyAddress;
use Illuminate\Database\Eloquent\Builder;

/**
 * @mixin CompanyAddress
 */
trait HasCompanyAddressScopes
{
    /**
     * Scope a query to only include primary addresses.
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
     * Scope a query to only include real (verified/physical) addresses.
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
     * Scope a query to only include billing addresses.
     *
     * @param Builder $query
     *
     * @return Builder
     */
    public function scopeBilling(Builder $query): Builder
    {
        return $query->where('type', CompanyAddress::TYPE_BILLING);
    }

    /**
     * Scope a query to only include shipping addresses.
     *
     * @param Builder $query
     *
     * @return Builder
     */
    public function scopeShipping(Builder $query): Builder
    {
        return $query->where('type', CompanyAddress::TYPE_SHIPPING);
    }

    /**
     * Scope a query to only include office addresses.
     *
     * @param Builder $query
     *
     * @return Builder
     */
    public function scopeOffice(Builder $query): Builder
    {
        return $query->where('type', CompanyAddress::TYPE_OFFICE);
    }

    /**
     * Scope a query to only include warehouse addresses.
     *
     * @param Builder $query
     *
     * @return Builder
     */
    public function scopeWarehouse(Builder $query): Builder
    {
        return $query->where('type', CompanyAddress::TYPE_WAREHOUSE);
    }

    /**
     * Scope a query to filter by country.
     *
     * @param Builder $query
     * @param string $country
     *
     * @return Builder
     */
    public function scopeInCountry(Builder $query, string $country): Builder
    {
        return $query->where('country', $country);
    }

    /**
     * Scope a query to filter by city.
     *
     * @param Builder $query
     * @param string $city
     *
     * @return Builder
     */
    public function scopeInCity(Builder $query, string $city): Builder
    {
        return $query->where('city', $city);
    }
}
