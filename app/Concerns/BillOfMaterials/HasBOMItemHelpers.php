<?php

namespace App\Concerns\BillOfMaterials;

use App\Models\Part;

/**
 * @property int $part_id
 * @property float $quantity
 * @property float|null $scrap_percentage
 * @property float|null $total_cost
 * @property float|null $formatted_total_cost
 *
 * @property-read Part $part
 */
trait HasBOMItemHelpers
{
    /**
     * Calculate quantity including scrap percentage.
     *
     * @return float
     */
    public function getQuantityWithScrap(): float
    {
        if ($this->scrap_percentage === null || $this->scrap_percentage === 0) {
            return $this->quantity;
        }

        return $this->quantity * (1 + ($this->scrap_percentage / 100));
    }

    /**
     * Calculate quantity needed for multiple products.
     *
     * @param  int $productQuantity
     *
     * @return float
     */
    public function calculateQuantityForProducts(int $productQuantity): float
    {
        return $this->getQuantityWithScrap() * $productQuantity;
    }

    /**
     * Check if sufficient stock exists for manufacturing.
     *
     * @param  int $productQuantity
     *
     * @return array
     */
    public function checkStockAvailability(int $productQuantity = 1): array
    {
        $requiredQuantity = $this->calculateQuantityForProducts(
            $productQuantity
        );
        $availableQuantity = $this->part->quantity;
        $sufficient = $availableQuantity >= $requiredQuantity;

        return [
            'part_id' => $this->part_id,
            'part_name' => $this->part->name,
            'part_sku' => $this->part->sku,
            'required_per_product' => $this->quantity,
            'scrap_percentage' => $this->scrap_percentage,
            'quantity_with_scrap' => $this->getQuantityWithScrap(),
            'total_required' => $requiredQuantity,
            'available' => $availableQuantity,
            'sufficient' => $sufficient,
            'shortage' => max(0, $requiredQuantity - $availableQuantity),
        ];
    }

    /**
     * Get extended cost (quantity × unit cost).
     *
     * @return float
     */
    public function getExtendedCost(): float
    {
        return $this->total_cost;
    }

    /**
     * Get formatted extended cost.
     *
     * @return string
     */
    public function getFormattedExtendedCost(): string
    {
        return $this->formatted_total_cost;
    }
}
