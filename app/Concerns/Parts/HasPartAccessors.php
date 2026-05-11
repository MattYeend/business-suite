<?php

namespace App\Concerns\Parts;

use App\Models\Part;

trait HasPartAccessors
{
    /**
     * Get stock status as a string.
     *
     * @return string
     */
    public function getStockStatus(): string
    {
        if ($this->isOutOfStock()) {
            return 'Out of Stock';
        }

        if ($this->needsReorder()) {
            return 'Needs Reorder';
        }

        if ($this->isLowStock()) {
            return 'Low Stock';
        }

        return 'In Stock';
    }

    /**
     * Get stock status as attribute.
     *
     * @return string
     */
    public function getStockStatusAttribute(): string
    {
        return $this->getStockStatus();
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
            Part::TYPE_CONUMABLE,
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
     * Get a human-readable type label.
     *
     * @return string
     */
    public function getTypeLabel(): string
    {
        return match ($this->type) {
            Part::TYPE_RAW_MATERIAL => 'Raw Material',
            Part::TYPE_FINISHED_GOOD => 'Finished Good',
            Part::TYPE_CONUMABLE => 'Consumable',
            Part::TYPE_SPARE_PART => 'Spare Part',
            Part::TYPE_SUB_ASSEMBLY => 'Sub-Assembly',
            default => ucfirst(str_replace('_', ' ', $this->type)),
        };
    }

    /**
     * Get a human-readable status label.
     *
     * @return string
     */
    public function getStatusLabel(): string
    {
        return match ($this->status) {
            Part::STATUS_ACTIVE => 'Active',
            Part::STATUS_DISCONTINUED => 'Discontinued',
            Part::STATUS_PENDING => 'Pending',
            Part::STATUS_OUT_OF_STOCK => 'Out of Stock',
            default => ucfirst(str_replace('_', ' ', $this->status)),
        };
    }

    /**
     * Get formatted price.
     *
     * @return string
     */
    public function getFormattedPrice(): string
    {
        return $this->formatMoney($this->price);
    }

    /**
     * Get formatted price as attribute.
     *
     * @return string
     */
    public function getFormattedPriceAttribute(): string
    {
        return $this->getFormattedPrice();
    }

    /**
     * Get formatted cost price.
     *
     * @return string|null
     */
    public function getFormattedCostPrice(): ?string
    {
        return $this->formatMoney($this->cost_price);
    }

    /**
     * Get formatted cost price as attribute.
     *
     * @return string|null
     */
    public function getFormattedCostPriceAttribute(): ?string
    {
        return $this->getFormattedCostPrice();
    }

    /**
     * Calculate profit margin percentage.
     *
     * @return float|null
     */
    public function getProfitMargin(): ?float
    {
        if ($this->cost_price === null || $this->cost_price === 0) {
            return null;
        }

        return ($this->price - $this->cost_price) / $this->price * 100;
    }

    /**
     * Get profit margin as attribute.
     *
     * @return float|null
     */
    public function getProfitMarginAttribute(): ?float
    {
        return $this->getProfitMargin();
    }

    /**
     * Get formatted profit margin.
     *
     * @return string|null
     */
    public function getFormattedProfitMargin(): ?string
    {
        $margin = $this->getProfitMargin();

        if ($margin === null) {
            return null;
        }

        return number_format($margin, 2) . '%';
    }

    /**
     * Get formatted profit margin as attribute.
     *
     * @return string|null
     */
    public function getFormattedProfitMarginAttribute(): ?string
    {
        return $this->getFormattedProfitMargin();
    }

    /**
     * Calculate total value of stock (quantity × cost_price).
     *
     * @return float
     */
    public function getStockValue(): float
    {
        return $this->quantity * ($this->cost_price ?? 0);
    }

    /**
     * Get stock value as attribute.
     *
     * @return float
     */
    public function getStockValueAttribute(): float
    {
        return $this->getStockValue();
    }

    /**
     * Get formatted stock value.
     *
     * @return string
     */
    public function getFormattedStockValue(): string
    {
        return $this->formatMoney($this->getStockValue());
    }

    /**
     * Get formatted stock value as attribute.
     *
     * @return string
     */
    public function getFormattedStockValueAttribute(): string
    {
        return $this->getFormattedStockValue();
    }

    /**
     * Get dimensions as a formatted string.
     *
     * @return string|null
     */
    public function getDimensions(): ?string
    {
        if (! $this->hasDimensions()) {
            return null;
        }

        $dims = array_filter([
            $this->length ? "{$this->length}L" : null,
            $this->width ? "{$this->width}W" : null,
            $this->height ? "{$this->height}H" : null,
        ]);

        return implode(' x ', $dims);
    }

    /**
     * Get dimensions as attribute.
     *
     * @return string|null
     */
    public function getDimensionsAttribute(): ?string
    {
        return $this->getDimensions();
    }

    /**
     * Calculate net price after discount.
     *
     * @return float
     */
    public function getNetPrice(): float
    {
        if (! $this->hasDiscount()) {
            return $this->price;
        }

        return $this->price * (1 - ($this->discount_percentage / 100));
    }

    /**
     * Calculate price including tax.
     *
     * @return float
     */
    public function getPriceIncludingTax(): float
    {
        if (! $this->hasTaxRate()) {
            return $this->getNetPrice();
        }

        return $this->getNetPrice() * (1 + ($this->tax_rate / 100));
    }

    /**
     * Get formatted net price.
     *
     * @return string
     */
    public function getFormattedNetPrice(): string
    {
        return $this->formatMoney($this->getNetPrice());
    }

    /**
     * Get formatted price including tax.
     *
     * @return string
     */
    public function getFormattedPriceIncludingTax(): string
    {
        return $this->formatMoney($this->getPriceIncludingTax());
    }
}
