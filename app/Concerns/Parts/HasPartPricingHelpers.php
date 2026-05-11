<?php

namespace App\Concerns\Parts;

/**
 * Part helper methods.
 *
 * @property float $price
 * @property float|null $cost_price
 * @property string $currency
 * @property float|null $tax_rate
 * @property string|null $tax_code
 * @property float|null $discount_percentage
 * @property int $quantity
 *
 * @property-read float|null $profit_margin
 * @property-read float $stock_value
 * @property-read float $net_price
 * @property-read float $price_including_tax
 */
trait HasPartPricingHelpers
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

    /**
     * Get formatted price.
     *
     * @return string
     */
    public function getFormattedPriceAttribute(): string
    {
        return $this->formatMoney($this->price);
    }

    /**
     * Get formatted cost price.
     *
     * @return string|null
     */
    public function getFormattedCostPriceAttribute(): ?string
    {
        if ($this->cost_price === null) {
            return null;
        }

        return $this->formatMoney($this->cost_price);
    }

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
     * Get formatted profit margin.
     *
     * @return string|null
     */
    public function getFormattedProfitMarginAttribute(): ?string
    {
        if ($this->profit_margin === null) {
            return null;
        }

        return number_format($this->profit_margin, 2) . '%';
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
     * Get formatted stock value.
     *
     * @return string
     */
    public function getFormattedStockValueAttribute(): string
    {
        return $this->formatMoney($this->stock_value);
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

        return $this->price * (1 - ($this->discount_percentage / 100));
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

    /**
     * Get formatted net price.
     *
     * @return string
     */
    public function getFormattedNetPriceAttribute(): string
    {
        return $this->formatMoney($this->net_price);
    }

    /**
     * Get formatted price including tax.
     *
     * @return string
     */
    public function getFormattedPriceIncludingTaxAttribute(): string
    {
        return $this->formatMoney($this->price_including_tax);
    }

    /**
     * Format money with currency symbol.
     *
     * @param  float $amount
     *
     * @return string
     */
    protected function formatMoney(float $amount): string
    {
        $symbols = [
            'GBP' => '£',
            'USD' => '$',
            'EUR' => '€',
        ];

        $symbol = $symbols[$this->currency]
            ?? $this->currency . ' ';

        return $symbol . number_format($amount, 2);
    }
}
