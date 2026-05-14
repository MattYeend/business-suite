<?php

namespace App\Concerns\BillOfMaterials;

/**
 * BOM entity helper methods.
 *
 * @property int|null $total_cost
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
        if (!$this->is_active) {
            return false;
        }

        $now = now();

        if ($this->effective_from && $this->effective_from->gt($now)) {
            return false;
        }

        if ($this->effective_to && $this->effective_to->lt($now)) {
            return false;
        }

        return true;
    }

    // /**
    //  * Get the total number of parts in this BOM.
    //  *
    //  * @return int
    //  */
    // public function getTotalPartsAttribute(): int
    // {
    //     return $this->items()->count();
    // }

    // /**
    //  * Get the total cost of all items in this BOM.
    //  *
    //  * @return float
    //  */
    // public function getTotalCostAttribute(): float
    // {
    //     return $this->calculateTotalCost();
    // }

    /**
     * Get formatted total cost.
     *
     * @param string
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
}
