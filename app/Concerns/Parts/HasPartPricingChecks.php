<?php

namespace App\Concerns\Parts;

/**
 * Pricing validation and existence checks.
 *
 * @property float|null $cost_price
 * @property float|null $discount_percentage
 * @property float|null $tax_rate
 */
trait HasPartPricingChecks
{
    /**
     * Check if part has cost price.
     *
     * @return bool
     */
    public function hasCostPrice(): bool
    {
        return $this->cost_price !== null;
    }

    /**
     * Check if part has discount.
     *
     * @return bool
     */
    public function hasDiscount(): bool
    {
        return $this->discount_percentage !== null
            && $this->discount_percentage > 0;
    }

    /**
     * Check if part has tax rate.
     *
     * @return bool
     */
    public function hasTaxRate(): bool
    {
        return $this->tax_rate !== null
            && $this->tax_rate > 0;
    }
}
