<?php

namespace App\Concerns\Parts;

use App\Models\Part;

/**
 * Part helper methods.
 *
 * @property string $sku
 * @property string|null $part_number
 * @property string|null $barcode
 * @property string $name
 * @property string $description
 * @property string|null $brand
 * @property string|null $manufacturer
 * @property string $type
 * @property string $status
 * @property string $unit_of_measure
 * @property float|null $height
 * @property float|null $width
 * @property float|null $length
 * @property float|null $weight
 * @property float|null $volume
 * @property string|null $colour
 * @property string|null $material
 * @property float $price
 * @property float|null $cost_price
 * @property string $currency
 * @property float|null $tax_rate
 * @property string|null $tax_code
 * @property float|null $discount_percentage
 * @property int $quantity
 * @property int $min_stock_level
 * @property int|null $max_stock_level
 * @property int|null $reorder_point
 * @property int|null $reorder_quantity
 * @property int|null $lead_time_days
 * @property string|null $warehouse_location
 * @property string|null $bin_location
 * @property bool $is_active
 * @property bool $is_purchasable
 * @property bool $is_sellable
 * @property bool $is_manufactured
 * @property bool $is_serialised
 * @property bool $is_batch_tracked
 * @property bool $is_real
 */
trait HasPartHelpers
{
    /**
     * Check if part is active.
     *
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->is_active;
    }

    /**
     * Check if part is inactive.
     *
     * @return bool
     */
    public function isInactive(): bool
    {
        return ! $this->is_active;
    }

    /**
     * Check if part is purchasable.
     *
     * @return bool
     */
    public function isPurchasable(): bool
    {
        return $this->is_purchasable;
    }

    /**
     * Check if part is sellable.
     *
     * @return bool
     */
    public function isSellable(): bool
    {
        return $this->is_sellable;
    }

    /**
     * Check if part is manufactured.
     *
     * @return bool
     */
    public function isManufactured(): bool
    {
        return $this->is_manufactured;
    }

    /**
     * Check if part is serialised.
     *
     * @return bool
     */
    public function isSerialised(): bool
    {
        return $this->is_serialised;
    }

    /**
     * Check if part is batch tracked.
     *
     * @return bool
     */
    public function isBatchTracked(): bool
    {
        return $this->is_batch_tracked;
    }

    /**
     * Check if part is real.
     *
     * @return bool
     */
    public function isReal(): bool
    {
        return $this->is_real;
    }

    /**
     * Determine whether the part is of a given type.
     *
     * @param string $type
     *
     * @return bool
     */
    public function isType(string $type): bool
    {
        return $this->type === $type;
    }

    /**
     * Check if part is a raw material.
     *
     * @return bool
     */
    public function isRawMaterial(): bool
    {
        return $this->type === Part::TYPE_RAW_MATERIAL;
    }

    /**
     * Check if part is a finished good.
     *
     * @return bool
     */
    public function isFinishedGood(): bool
    {
        return $this->type === Part::TYPE_FINISHED_GOOD;
    }

    /**
     * Check if part is a consumable.
     *
     * @return bool
     */
    public function isConsumable(): bool
    {
        return $this->type === Part::TYPE_CONUMABLE;
    }

    /**
     * Check if part is a spare part.
     *
     * @return bool
     */
    public function isSparePart(): bool
    {
        return $this->type === Part::TYPE_SPARE_PART;
    }

    /**
     * Check if part is a sub-assembly.
     *
     * @return bool
     */
    public function isSubAssembly(): bool
    {
        return $this->type === Part::TYPE_SUB_ASSEMBLY;
    }

    /**
     * Determine whether the part has a given status.
     *
     * @param string $status
     *
     * @return bool
     */
    public function hasStatus(string $status): bool
    {
        return $this->status === $status;
    }

    /**
     * Check if part status is active.
     *
     * @return bool
     */
    public function isStatusActive(): bool
    {
        return $this->status === Part::STATUS_ACTIVE;
    }

    /**
     * Check if part is discontinued.
     *
     * @return bool
     */
    public function isDiscontinued(): bool
    {
        return $this->status === Part::STATUS_DISCONTINUED;
    }

    /**
     * Check if part is pending.
     *
     * @return bool
     */
    public function isPending(): bool
    {
        return $this->status === Part::STATUS_PENDING;
    }

    /**
     * Check if part status is out of stock.
     *
     * @return bool
     */
    public function isStatusOutOfStock(): bool
    {
        return $this->status === Part::STATUS_OUT_OF_STOCK;
    }

    /**
     * Check if part is low on stock.
     *
     * @return bool
     */
    public function isLowStock(): bool
    {
        return $this->quantity <= $this->min_stock_level;
    }

    /**
     * Get low stock status as attribute.
     *
     * @return bool
     */
    public function getIsLowStockAttribute(): bool
    {
        return $this->isLowStock();
    }

    /**
     * Check if part needs reordering.
     *
     * @return bool
     */
    public function needsReorder(): bool
    {
        if ($this->reorder_point === null) {
            return false;
        }

        return $this->quantity <= $this->reorder_point;
    }

    /**
     * Get needs reorder status as attribute.
     *
     * @return bool
     */
    public function getNeedsReorderAttribute(): bool
    {
        return $this->needsReorder();
    }

    /**
     * Check if part is out of stock.
     *
     * @return bool
     */
    public function isOutOfStock(): bool
    {
        return $this->quantity <= 0;
    }

    /**
     * Get out of stock status as attribute.
     *
     * @return bool
     */
    public function getIsOutOfStockAttribute(): bool
    {
        return $this->isOutOfStock();
    }

    /**
     * Check if part is in stock.
     *
     * @return bool
     */
    public function isInStock(): bool
    {
        return $this->quantity > 0;
    }

    /**
     * Check if part has sufficient stock.
     *
     * @param int $requiredQuantity
     *
     * @return bool
     */
    public function hasSufficientStock(int $requiredQuantity): bool
    {
        return $this->quantity >= $requiredQuantity;
    }

    /**
     * Check if part has reorder point set.
     *
     * @return bool
     */
    public function hasReorderPoint(): bool
    {
        return $this->reorder_point !== null;
    }

    /**
     * Check if part has max stock level set.
     *
     * @return bool
     */
    public function hasMaxStockLevel(): bool
    {
        return $this->max_stock_level !== null;
    }

    /**
     * Check if part has lead time.
     *
     * @return bool
     */
    public function hasLeadTime(): bool
    {
        return $this->lead_time_days !== null;
    }

    /**
     * Check if part has dimensions.
     *
     * @return bool
     */
    public function hasDimensions(): bool
    {
        return $this->length !== null ||
        $this->width !== null ||
        $this->height !== null;
    }

    /**
     * Check if part has weight.
     *
     * @return bool
     */
    public function hasWeight(): bool
    {
        return $this->weight !== null;
    }

    /**
     * Check if part has volume.
     *
     * @return bool
     */
    public function hasVolume(): bool
    {
        return $this->volume !== null;
    }

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
        return $this->discount_percentage !== null &&
        $this->discount_percentage > 0;
    }

    /**
     * Check if part has tax rate.
     *
     * @return bool
     */
    public function hasTaxRate(): bool
    {
        return $this->tax_rate !== null && $this->tax_rate > 0;
    }

    /**
     * Adjust stock quantity.
     *
     * @param int $quantity
     * @param string|null $reason
     *
     * @return bool
     */
    public function adjustStock(int $quantity, string $reason = null): bool
    {
        $this->quantity += $quantity;

        // Update status based on new quantity
        if ($this->quantity <= 0) {
            $this->status = Part::STATUS_OUT_OF_STOCK;
        } elseif (
            $this->status === Part::STATUS_OUT_OF_STOCK &&
            $this->quantity > 0
        ) {
            $this->status = Part::STATUS_ACTIVE;
        }

        return $this->save();
    }

    /**
     * Increase stock quantity.
     *
     * @param int $quantity
     * @param string|null $reason
     *
     * @return bool
     */
    public function increaseStock(int $quantity, string $reason = null): bool
    {
        return $this->adjustStock($quantity, $reason);
    }

    /**
     * Decrease stock quantity.
     *
     * @param int $quantity
     * @param string|null $reason
     *
     * @return bool
     */
    public function decreaseStock(int $quantity, string $reason = null): bool
    {
        return $this->adjustStock(-$quantity, $reason);
    }

    /**
     * Set stock quantity to a specific value.
     *
     * @param int $quantity
     * @param string|null $reason
     *
     * @return bool
     */
    public function setStock(int $quantity, string $reason = null): bool
    {
        $adjustment = $quantity - $this->quantity;

        return $this->adjustStock($adjustment, $reason);
    }

    /**
     * Get the lead time in weeks.
     *
     * @return float|null
     */
    public function getLeadTimeWeeksAttribute(): ?float
    {
        if ($this->lead_time_days === null) {
            return null;
        }

        return round($this->lead_time_days / 7, 2);
    }

    /**
     * Get stock percentage of max level.
     *
     * @return float|null
     */
    public function getStockPercentage(): ?float
    {
        if ($this->max_stock_level === null || $this->max_stock_level == 0) {
            return null;
        }

        return ($this->quantity / $this->max_stock_level) * 100;
    }

    /**
     * Get quantity needed to reach max stock level.
     *
     * @return int|null
     */
    public function getQuantityToMax(): ?int
    {
        if ($this->max_stock_level === null) {
            return null;
        }

        return max(0, $this->max_stock_level - $this->quantity);
    }

    /**
     * Get quantity below reorder point.
     *
     * @return int|null
     */
    public function getQuantityBelowReorderPoint(): ?int
    {
        if ($this->reorder_point === null) {
            return null;
        }

        $difference = $this->reorder_point - $this->quantity;

        return $difference > 0 ? $difference : 0;
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

        $symbol = $symbols[$this->currency] ?? $this->currency . ' ';

        return $symbol . number_format($amount, 2);
    }
}
