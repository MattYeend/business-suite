<?php

namespace App\Concerns\Parts;

use App\Models\Part;

trait HasPartAccessors
{
    /**
     * Get stock status as string.
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
    public static function getPartStatuses(): array
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
