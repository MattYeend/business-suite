<?php

namespace App\Services\Products;

use Illuminate\Database\Eloquent\Builder;

class ProductFilterService
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
            $q->where('name', 'like', "%{$search}%")
                ->orWhere('price', 'like', "%{$search}%")
                ->orWhere('currency', 'like', "%{$search}%")
                ->orWhere('status', 'like', "%{$search}%")
                ->orWhere('quantity', 'like', "%{$search}%")
                ->orWhere('min_stock_level', 'like', "%{$search}%");
        });
    }

    /**
     * Apply status filter to query.
     *
     * @param  Builder $query
     * @param  string|null $status
     *
     * @return Builder
     */
    public function applyStatus(Builder $query, ?string $status): Builder
    {
        if ($status === null) {
            return $query;
        }

        return match ($status) {
            'real' => $query->where('is_real', true),
            'test' => $query->where('is_real', false),
            default => $query,
        };
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
        $query = $this->applySearch($query, $filters['search'] ?? null);

        return $this->applyStatus($query, $filters['status'] ?? null);
    }
}
