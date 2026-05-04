<?php

namespace App\Services\CompanyIndustries;

use Illuminate\Database\Eloquent\Builder;

class CompanyIndustryFilterService
{
    /**
     * Apply search filter to query.
     *
     * @param  Builder $query
     * @param  string|null $search
     *
     * @return Builder
     */
    public function applySearch(Builder $query, ?string $search): Builder
    {
        if ($search === null) {
            return $query;
        }

        return $query->where(function (Builder $q) use ($search) {
            $q->where('name', 'like', "%{$search}%");
        });
    }

    /**
     * Apply all filters to query.
     *
     * @param  Builder $query
     * @param  array $filters
     *
     * @return Builder
     */
    public function applyAll(Builder $query, array $filters): Builder
    {
        return $this->applySearch($query, $filters['search'] ?? null);
    }
}
