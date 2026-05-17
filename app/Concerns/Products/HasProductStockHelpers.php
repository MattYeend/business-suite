<?php

namespace App\Concerns\Products;

use App\Models\Product;

/**
 * Product stock helper methods.
 *
 * @property string $type
 * @property string $status
 * @property float|null $discount_percentage
 * @property int $quantity
 * @property int $min_stock_level
 * @property int|null $max_stock_level
 * @property int|null $reorder_point
 *
 * @property-read float|null $stock_percentage
 * @property-read int|null $quantity_to_max
 * @property-read int|null $quantity_below_reorder_point
 * @property-read bool $is_low_stock
 * @property-read bool $needs_reorder
 * @property-read bool $is_out_of_stock
 */
trait HasProductStockHelpers
{
    /**
     * Check if product is in stock.
     *
     * @return bool
     */
    public function isInStock(): bool
    {
        return $this->quantity > Product::STATUS_OUT_OF_STOCK;
    }

    /**
     * Check if product has sufficient stock.
     *
     * @param  int $requiredQuantity
     *
     * @return bool
     */
    public function hasSufficientStock(int $requiredQuantity): bool
    {
        return $this->quantity >= $requiredQuantity;
    }

    /**
     * Increase stock quantity.
     *
     * @param  int $quantity
     *
     * @return bool
     */
    public function increaseStock(int $quantity): bool
    {
        return $this->adjustStock($quantity);
    }

    /**
     * Decrease stock quantity.
     *
     * @param  int $quantity
     *
     * @return bool
     */
    public function decreaseStock(int $quantity): bool
    {
        return $this->adjustStock(-$quantity);
    }

    /**
     * Check if product has reorder point set.
     *
     * @return bool
     */
    public function hasReorderPoint(): bool
    {
        return $this->reorder_point !== null;
    }

    /**
     * Check if product has no max stock level.
     *
     * @return bool
     */
    public function hasNoMaxStockLevel(): bool
    {
        return $this->max_stock_level === null || $this->max_stock_level === 0;
    }

    /**
     * Check if product has max stock level set.
     *
     * @return bool
     */
    public function hasMaxStockLevel(): bool
    {
        return $this->max_stock_level !== null;
    }

    /**
     * Get stock percentage of max level.
     *
     * @return float|null
     */
    public function getStockPercentageAttribute(): ?float
    {
        if ($this->hasNoMaxStockLevel()) {
            return null;
        }

        return $this->quantity / $this->max_stock_level * 100;
    }

    /**
     * Get quantity needed to reach max stock level.
     *
     * @return int|null
     */
    public function getQuantityToMaxAttribute(): ?int
    {
        if ($this->max_stock_level === null) {
            return null;
        }

        return max(0, $this->max_stock_level -  $this->quantity);
    }

    /**
     * Get quantity below reorder point.
     *
     * @return int|null
     */
    public function getQuantityBelowReorderPointAttribute(): ?int
    {
        if (! $this->hasReorderPoint()) {
            return null;
        }

        return max(0, $this->reorder_point - $this->quantity);
    }
}
