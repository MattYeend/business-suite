<?php

namespace App\Concerns\Products;

trait ProductStockAttributes
{
    /**
     * Check if product is low on stock.
     *
     * @return bool
     */
    public function getIsLowStockAttribute(): bool
    {
        return $this->quantity <= $this->min_stock_level;
    }

    /**
     * Check if product needs reordering.
     *
     * @return bool
     */
    public function getNeedsReorderAttribute(): bool
    {
        if ($this->reorder_point === null) {
            return false;
        }

        return $this->quantity <= $this->reorder_point;
    }

    /**
     * Check if product is out of stock.
     *
     * @return bool
     */
    public function getIsOutOfStockAttribute(): bool
    {
        return $this->quantity <= 0;
    }
}
