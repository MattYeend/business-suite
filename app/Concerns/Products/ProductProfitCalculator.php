<?php

namespace App\Services\Product;

class ProductProfitCalculator
{
    /**
     * Calculate profit margin percentage.
     *
     * @param  float $price
     * @param  float|null $cost
     *
     * @return float|null
     */
    public static function calculateMargin(float $price, ?float $cost): ?float
    {
        if ($cost === null) {
            return null;
        }

        if ($price === 0) {
            return null;
        }

        return ($price - $cost) / $price * 100;
    }

    /**
     * Format profit margin as percentage string.
     *
     * @param  float|null $margin
     *
     * @return string|null
     */
    public static function formatMargin(?float $margin): ?string
    {
        if ($margin === null) {
            return null;
        }

        return number_format($margin, 2) . '%';
    }
}
