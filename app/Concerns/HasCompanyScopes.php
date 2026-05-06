<?php

namespace App\Concerns;

use Illuminate\Database\Eloquent\Builder;

trait HasCompanyScopes
{
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
