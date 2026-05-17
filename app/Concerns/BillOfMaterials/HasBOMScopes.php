<?php

namespace App\Concerns\BillOfMaterials;

use Illuminate\Database\Eloquent\Builder;

trait HasBOMScopes
{
    /**
     * Scope a query to only include real BOM.
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
     * Scope: Active BOMs only.
     *
     * @param  Builder $query
     *
     * @return Builder
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope: Currently effective BOMs.
     *
     * @param  Builder $query
     *
     * @return Builder
     */
    public function scopeEffective(Builder $query): Builder
    {
        $now = now();

        return $query->where('is_active', true)
            ->where(function ($q) use ($now) {
                $q->whereNull('effective_from')
                    ->orWhere('effective_from', '<=', $now);
            })
            ->where(function ($q) use ($now) {
                $q->whereNull('effective_to')
                    ->orWhere('effective_to', '>=', $now);
            });
    }

    /**
     * Scope: BOMs for a specific product.
     *
     * @param  Builder $query
     * @param  int $productId
     *
     * @return Builder
     */
    public function scopeForProduct(Builder $query, int $productId): Builder
    {
        return $query->where('product_id', $productId);
    }
}
