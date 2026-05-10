<?php

namespace App\Concerns\Companies;

use Illuminate\Database\Eloquent\Builder;

trait HasCompanyScopes
{
    /**
     * Scope to eager load industry relationship.
     *
     * @param  Builder $query
     *
     * @return Builder
     */
    public function scopeWithIndustry(Builder $query): Builder
    {
        return $query->with('industry');
    }

    /**
     * Scope to eager load contacts relationship.
     *
     * @param  Builder $query
     *
     * @return Builder
     */
    public function scopeWithContacts(Builder $query): Builder
    {
        return $query->with('companyContacts');
    }

    /**
     * Scope to eager load phones relationship.
     *
     * @param  Builder $query
     *
     * @return Builder
     */
    public function scopeWithPhones(Builder $query): Builder
    {
        return $query->with('companyPhones');
    }

    /**
     * Scope to eager load addresses relationship.
     *
     * @param  Builder $query
     *
     * @return Builder
     */
    public function scopeWithAddresses(Builder $query): Builder
    {
        return $query->with('companyAddresses');
    }

    /**
     * Scope to eager load creator relationship.
     *
     * @param  Builder $query
     *
     * @return Builder
     */
    public function scopeWithCreator(Builder $query): Builder
    {
        return $query->with('creator');
    }

    /**
     * Scope to eager load all common relationships.
     *
     * @param  Builder $query
     *
     * @return Builder
     */
    public function scopeWithAllRelations(Builder $query): Builder
    {
        return $query->with([
            'industry',
            'companyContacts',
            'companyPhones',
            'companyAddresses',
            'creator',
            'updater',
        ]);
    }

    /**
     * Scope a query to only include real companies.
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
     * Scope a query to only include test companies.
     *
     * @param  Builder $query
     *
     * @return Builder
     */
    public function scopeTest(Builder $query): Builder
    {
        return $query->where('is_real', false);
    }

    /**
     * Scope a query to filter by industry.
     *
     * @param  Builder $query
     * @param  int $industryId
     *
     * @return Builder
     */
    public function scopeInIndustry(Builder $query, int $industryId): Builder
    {
        return $query->where('industry_id', $industryId);
    }

    /**
     * Scope a query to filter by country.
     *
     * @param  Builder $query
     * @param  string $country
     *
     * @return Builder
     */
    public function scopeInCountry(Builder $query, string $country): Builder
    {
        return $query->where('country', $country);
    }

    /**
     * Scope companies that have a phone.
     *
     * @param  Builder $query
     *
     * @return Builder
     */
    public function scopeHasPhone(Builder $query): Builder
    {
        return $query->whereNotNull('phone');
    }

    /**
     * Scope a query to filter by employee count range.
     *
     * @param  Builder $query
     * @param  int $min
     * @param  int|null $max
     *
     * @return Builder
     */
    public function scopeEmployeeCountBetween(
        Builder $query,
        int $min,
        ?int $max = null
    ): Builder {
        $query->where('employee_count', '>=', $min);

        if ($max !== null) {
            $query->where('employee_count', '<=', $max);
        }

        return $query;
    }

    /**
     * Scope a query to filter by annual revenue range.
     *
     * @param  Builder $query
     * @param  float $min
     * @param  float|null $max
     *
     * @return Builder
     */
    public function scopeRevenueBetween(
        Builder $query,
        float $min,
        ?float $max = null
    ): Builder {
        $query->where('annual_revenue', '>=', $min);

        if ($max !== null) {
            $query->where('annual_revenue', '<=', $max);
        }

        return $query;
    }
}
