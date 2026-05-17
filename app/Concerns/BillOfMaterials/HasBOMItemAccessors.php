<?php

namespace App\Concerns\BillOfMaterials;

use App\Models\Part;

/**
 * @property float $quantity
 * @property float|null $total_cost
 *
 * @property-read Part $part
 */
trait HasBOMItemAccessors
{
    /**
     * Calculate the total cost for this BOM item.
     *
     * @return float
     */
    public function getTotalCostAttribute(): float
    {
        $partCost = $this->part->cost_price ?? 0;
        $quantityWithScrap = $this->getQuantityWithScrap();

        return $quantityWithScrap * $partCost;
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
     * Get quantity including scrap allowance.
     *
     * @return float
     */
    public function getQuantityWithScrapAttribute(): float
    {
        return $this->getQuantityWithScrap();
    }

    /**
     * Check if part is available in sufficient quantity.
     *
     * @return bool
     */
    public function getIsAvailableAttribute(): bool
    {
        return $this->part->quantity >= $this->quantity;
    }

    /**
     * Get shortage quantity if part is not available.
     *
     * @return float
     */
    public function getShortageAttribute(): float
    {
        $shortage = $this->quantity - $this->part->quantity;
        return max(0, $shortage);
    }
}
