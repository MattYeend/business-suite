<?php

namespace App\Concerns\Products;

use App\Models\Product;

/**
 * @property string $status
 *
 * @property-read bool $is_low_stock
 * @property-read bool $needs_reorder
 * @property-read bool $is_out_of_stock
 */
trait ProductStockAttributes
{
    /**
     * Determine whether the product is of a given status.
     *
     * @param  string $status
     *
     * @return bool
     */
    public function isStatus(string $status): bool
    {
        return $this->status === $status;
    }

    /**
     * Determine whether the status is active.
     *
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->status === Product::STATUS_ACTIVE;
    }

    /**
     * Determine whether the status is discontinued.
     *
     * @return bool
     */
    public function isDiscontinued(): bool
    {
        return $this->status === Product::STATUS_DISCONTINUED;
    }

    /**
     * Determine whether the status is pending.
     *
     * @return bool
     */
    public function isPending(): bool
    {
        return $this->status === Product::STATUS_PENDING;
    }

    /**
     * Check if product is low on stock.
     *
     * @return bool
     */
    public function isLowStock(): bool
    {
        return $this->quantity <= $this->min_stock_level;
    }

    /**
     * Check if product needs reordering.
     *
     * @return bool
     */
    public function needsReorder(): bool
    {
        return $this->reorder_point !== null
            && $this->quantity <= $this->reorder_point;
    }

    /**
     * Check if product is out of stock.
     *
     * @return bool
     */
    public function isOutOfStock(): bool
    {
        return $this->quantity === Product::STATUS_OUT_OF_STOCK;
    }

    /**
     * Check if product is low on stock.
     *
     * @return bool
     */
    public function getIsLowStockAttribute(): bool
    {
        return $this->isLowStock();
    }

    /**
     * Get needs reorder status as attribute.
     *
     * @return bool
     */
    public function getNeedsReorderAttribute(): bool
    {
        return $this->needsReorder();
    }

    /**
     * Get out of stock status as attribute.
     *
     * @return bool
     */
    public function getIsOutOfStockAttribute(): bool
    {
        return $this->isOutOfStock();
    }
}
