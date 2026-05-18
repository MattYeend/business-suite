<?php

namespace App\Concerns\BillOfMaterials;

use App\Models\Product;
use Illuminate\Support\Carbon;

/**
 * BOM entity helper methods.
 *
 * @property bool $is_active
 * @property Carbon|null $effective_from
 * @property Carbon|null $effective_to
 * @property int|null $total_cost
 *
 * @property-read Product $product
 */

trait HasBOMAccessors
{
    /**
     * Check if BOM is currently effective.
     *
     * @return bool
     */
    public function getIsEffectiveAttribute(): bool
    {
        return $this->is_active
            && $this->isWithinEffectiveDateRange();
    }

    /**
     * Get the total number of parts in this BOM.
     *
     * @return int
     */
    public function getTotalPartsAttribute(): int
    {
        return $this->items()->count();
    }

    /**
     * Get the total cost of all items in this BOM.
     *
     * @return float
     */
    public function getTotalCostAttribute(): float
    {
        return $this->calculateTotalCost();
    }

    /**
     * Get formatted total cost.
     *
     * @return string
     */
    public function getFormattedTotalCostAttribute(): string
    {
        $currency = $this->product->currency ?? 'GBP';
        $symbols = [
            'GBP' => '£',
            'USD' => '$',
            'EUR' => '€',
        ];

        $symbol = $symbols[$currency] ?? $currency . ' ';

        return $symbol . number_format($this->total_cost, 2);
    }

    /**
     * Check if BOM is within effective date range.
     *
     * @return bool
     */
    protected function isWithinEffectiveDateRange(): bool
    {
        $now = now();

        $afterStartDate = ! $this->effective_from ||
            $this->effective_from->lte($now);
        $beforeEndDate = ! $this->effective_to ||
            $this->effective_to->gte($now);

        return $afterStartDate && $beforeEndDate;
    }
}
