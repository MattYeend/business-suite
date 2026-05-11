<?php

namespace App\Concerns\Parts;

/**
 * Pricing calculation helpers.
 *
 * @property float $price
 * @property float|null $cost_price
 * @property float|null $discount_percentage
 * @property float|null $tax_rate
 * @property int $quantity
 *
 * @property-read float $net_price
 */
trait HasPartPriceCalculations
{
    /**
     * Calculate profit margin percentage.
     *
     * @return float|null
     */
    public function getProfitMarginAttribute(): ?float
    {
        if ($this->cost_price === null || $this->cost_price === 0.0) {
            return null;
        }

        return ($this->price - $this->cost_price) / $this->price * 100;
    }

    /**
     * Get stock value.
     *
     * @return float
     */
    public function getStockValueAttribute(): float
    {
        return $this->quantity * ($this->cost_price ?? 0);
    }

    /**
     * Get net price.
     *
     * @return float
     */
    public function getNetPriceAttribute(): float
    {
        if (! $this->hasDiscount()) {
            return $this->price;
        }

        return $this->price * (1 - $this->discount_percentage / 100);
    }

    /**
     * Get price including tax.
     *
     * @return float
     */
    public function getPriceIncludingTaxAttribute(): float
    {
        if (! $this->hasTaxRate()) {
            return $this->net_price;
        }

        return $this->net_price * (1 + ($this->tax_rate / 100));
    }
}
