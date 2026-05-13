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
     * HasPartStateHelpers<HasPartStateHelpers>
     * HasPartStockHelpers<HasPartStockHelpers>
     * HasPartDimensionHelpers<HasPartDimensionHelpers>
     * HasPartPricingHelpers<HasPartPricingHelpers>
     */
    use HasPartStateHelpers,
        HasPartStockHelpers,
        HasPartDimensionHelpers,
        HasPartPricingHelpers;

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
     * Check if part has reorder point set.
     *
     * @return bool
     */
    public function hasReorderPoint(): bool
    {
        return $this->reorder_point !== null;
    }

    /**
     * Check if part has no max stock percentage
     * level.
     *
     * @return bool
     */
    public function hasNoMaxStockLevel(): bool
    {
        return $this->max_stock_level === null || $this->max_stock_level === 0;
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
     * Adjust stock quantity.
     *
     * @param int $quantity
     *
     * @return bool
     */
    public function adjustStock(int $quantity): bool
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
     * Get stock status as string.
     *
     * @return string
     */
    public function getStockStatusAttribute(): string
    {
        return match (true) {
            $this->isOutOfStock() => 'Out of Stock',
            $this->needsReorder() => 'Needs Reorder',
            $this->isLowStock() => 'Low Stock',
            default => 'In Stock',
        };
    }

    /**
     * Get all available part types.
     *
     * @return array<int,string>
     */
    public static function getPartTypes(): array
    {
        return [
            Part::TYPE_RAW_MATERIAL,
            Part::TYPE_FINISHED_GOOD,
            Part::TYPE_CONSUMABLE,
            Part::TYPE_SPARE_PART,
            Part::TYPE_SUB_ASSEMBLY,
        ];
    }

    /**
     * Get all available part statuses.
     *
     * @return array<int,string>
     */
    public static function getPartStatus(): array
    {
        return [
            Part::STATUS_ACTIVE,
            Part::STATUS_DISCONTINUED,
            Part::STATUS_PENDING,
            Part::STATUS_OUT_OF_STOCK,
        ];
    }

    /**
     * Get human-readable type label.
     *
     * @return string
     */
    public function getTypeLabelAttribute(): string
    {
        return match ($this->type) {
            Part::TYPE_RAW_MATERIAL => 'Raw Material',
            Part::TYPE_FINISHED_GOOD => 'Finished Good',
            Part::TYPE_CONSUMABLE => 'Consumable',
            Part::TYPE_SPARE_PART => 'Spare Part',
            Part::TYPE_SUB_ASSEMBLY => 'Sub-Assembly',
            default => ucfirst(str_replace('_', ' ', $this->type)),
        };
    }

    /**
     * Get human-readable status label.
     *
     * @return string
     */
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            Part::STATUS_ACTIVE => 'Active',
            Part::STATUS_DISCONTINUED => 'Discontinued',
            Part::STATUS_PENDING => 'Pending',
            Part::STATUS_OUT_OF_STOCK => 'Out of Stock',
            default => ucfirst(str_replace('_', ' ', $this->status)),
        };
    }
}
