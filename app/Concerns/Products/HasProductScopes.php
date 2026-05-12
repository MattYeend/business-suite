<?php

namespace App\Concerns\Products;

use Illuminate\Database\Eloquent\Builder;

trait HasProductScopes
{
    /**
     * Active products only.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 'active');
    }

    /**
     * Products that are discontinued.
     */
    public function scopeDiscontinued(Builder $query): Builder
    {
        return $query->where('status', 'discontinued');
    }

    /**
     * Products that are out of stock.
     */
    public function scopeOutOfStock(Builder $query): Builder
    {
        return $query->where(
            'status',
            'out_of_stock'
        )->orWhere(
            'quantity',
            '<=',
            0
        );
    }

    /**
     * Products below minimum stock level.
     */
    public function scopeLowStock(Builder $query): Builder
    {
        return $query->whereColumn('quantity', '<=', 'min_stock_level');
    }

    /**
     * Products at or below reorder point.
     */
    public function scopeNeedsReorder(Builder $query): Builder
    {
        return $query->whereNotNull('reorder_point')
            ->whereColumn('quantity', '<=', 'reorder_point');
    }
}
