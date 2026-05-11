<?php

namespace App\Concerns\Parts;

/**
 * Price formatting helpers.
 *
 * @property float $price
 * @property float|null $cost_price
 * @property string $currency
 *
 * @property-read float|null $profit_margin
 * @property-read float $stock_value
 * @property-read float $net_price
 * @property-read float $price_including_tax
 */
trait HasPartPriceFormatting
{
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
     * Get formatted stock value.
     *
     * @return string
     */
    public function getFormattedStockValueAttribute(): string
    {
        return $this->formatMoney($this->stock_value);
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
     * @param float $amount
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
            ?? "{$this->currency} ";

        return $symbol . number_format($amount, 2);
    }
}
