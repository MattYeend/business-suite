<?php

namespace App\Concerns\Parts;

use App\Models\Part;

/**
 * Part stock helper methods.
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
trait HasPartStockHelpers
{
    /**
     * Check if part is low on stock.
     *
     * @return bool
     */
    public function isLowStock(): bool
    {
        return $this->quantity <= $this->min_stock_level;
    }

    /**
     * Check if part needs reordering.
     *
     * @return bool
     */
    public function needsReorder(): bool
    {
        return $this->hasReorderPoint()
            && $this->quantity <= $this->reorder_point;
    }

    /**
     * Check if part is out of stock.
     *
     * @return bool
     */
    public function isOutOfStock(): bool
    {
        return $this->quantity === Part::STATUS_OUT_OF_STOCK;
    }

    /**
     * Check if part is in stock.
     *
     * @return bool
     */
    public function isInStock(): bool
    {
        return $this->quantity > Part::STATUS_OUT_OF_STOCK;
    }

    /**
     * Check if part has sufficient stock.
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
     * Get stock percentage of max level.
     *
     * @return float
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

        return max(0, $this->max_stock_level - $this->quantity);
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

    /**
     * Get low stock status as attribute.
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
