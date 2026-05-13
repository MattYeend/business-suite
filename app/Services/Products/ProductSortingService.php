<?php

namespace App\Services\Products;

use Illuminate\Database\Eloquent\Builder;

class ProductSortingService
{
    /**
     * Apply sorting to query.
     *
     * @param  Builder $query
     * @param  string|null $sortBy
     * @param  string|null $sortDirection
     *
     * @return Builder
     */
    public function applySorting(
        Builder $query,
        ?string $sortBy = 'created_at',
        ?string $sortDirection = 'desc'
    ): Builder {
        $sortBy = $sortBy ?? 'created_at';
        $sortDirection = strtolower($sortDirection) === 'asc' ? 'asc' :
            'desc';

        return match ($sortBy) {
            'sku' => $query->orderBy('sku', $sortDirection),
            'created_at' => $query->orderBy('created_at', $sortDirection),
            'updated_at' => $query->orderBy('updated_at', $sortDirection),
            default => $query->orderBy('created_at', $sortDirection),
        };
    }

    /**
     * Get available sort fields.
     *
     * @return array
     */
    public function getAvailableSortFields(): array
    {
        return [
            'sku' => 'SKU',
            'created_at' => 'Created Date',
            'updated_at' => 'Updated Date',
        ];
    }
}
