<?php

namespace App\Concerns\Products;

use App\Models\Product;
use App\Services\Product\ProductStatusResolver;

/**
 * Product accessor methods.
 *
 * @property bool $is_active
 * @property bool $is_default
 * @property bool $is_real
 * @property string $entity
 * @property int $quantity
 * @property int|null $min_stock_level
 * @property int|null $reorder_point
 * @property float $price
 * @property bool $is_low_stock
 * @property bool $needs_reorder
 * @property bool $is_out_of_stock
 * @property string $stock_status
 * @property string $formatted_price
 * @property mixed $primary_image
 * @property bool $has_bom
 * @property float|null $calculated_cost_price
 * @property string|null $formatted_calculated_cost_price
 * @property float|null $profit_margin
 * @property string|null $formatted_profit_margin
 * @property float $stock_value
 * @property string $formatted_stock_value
 */
trait HasProductAccessors
{
    /**
     * ProductStockAttributes<ProductStockAttributes>
     */
    use ProductStockAttributes;

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
     * Check if product has a Bill of Material.
     *
     * @return bool
     */
    public function getHasBomAttribute(): bool
    {
        return $this->billOfMaterial()->exists();
    }

    /**
     * Get calculated cost price from BOM.
     *
     * @return float|null
     */
    public function getCalculatedCostPriceAttribute(): ?float
    {
        if (! $this->has_bom) {
            return null;
        }

        return $this->calculateBomCost();
    }

    /**
     * Get formatted calculated cost price.
     *
     * @return string|null
     */
    public function getFormattedCalculatedCostPriceAttribute(): ?string
    {
        $cost = $this->calculated_cost_price;

        if ($cost === null) {
            return null;
        }

        return $this->formatMoney($cost);
    }

    /**
     * Calculate profit margin percentage.
     *
     * @return float|null
     */
    public function getProfitMarginAttribute(): ?float
    {
        $cost = $this->calculated_cost_price;

        if ($cost === null || $this->price === 0) {
            return null;
        }

        return ($this->price - $cost) / $this->price * 100;
    }

    /**
     * Get formatted profit margin.
     *
     * @return string|null
     */
    public function getFormattedProfitMarginAttribute(): ?string
    {
        $margin = $this->profit_margin;

        if ($margin === null) {
            return null;
        }

        return number_format($margin, 2) . '%';
    }

    /**
     * Calculate total value of stock.
     *
     * @return float
     */
    public function getStockValueAttribute(): float
    {
        $cost = $this->calculated_cost_price ?? 0;
        return $this->quantity * $cost;
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
     * Get stock status as string.
     *
     * @return string
     */
    public function getStockStatusAttribute(): string
    {
        return ProductStatusResolver::resolveStockStatus($this);
    }

    /**
     * Get all available product statuses.
     *
     * @return array<int,string>
     */
    public static function getProductStatuses(): array
    {
        return [
            Product::STATUS_ACTIVE,
            Product::STATUS_DISCONTINUED,
            Product::STATUS_PENDING,
            Product::STATUS_OUT_OF_STOCK,
        ];
    }

    /**
     * Get human-readable status label.
     *
     * @return string
     */
    public function getStatusLabelAttribute(): string
    {
        return ProductStatusResolver::resolveStatusLabel($this->status);
    }
}
