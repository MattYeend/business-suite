<?php

namespace App\Concerns\Products;

use App\Models\Product;
use App\Services\Product\ProductManufacturingService;
use App\Services\Product\ProductStatusResolver;

/**
 * Product entity helper methods.
 *
 * @property bool $is_active
 * @property bool $is_default
 * @property bool $is_real
 * @property string $entity
 * @property float $price
 * @property int $quantity
 * @property string $currency
 * @property string $status
 * @property int|null $min_stock_level
 * @property int|null $reorder_point
 */
trait HasProductHelpers
{
    /**
     * HasProductStockHelpers<HasProductStockHelpers>
     */
    use HasProductStockHelpers;

    /**
     * Calculate total BOM cost for this product.
     *
     * @return float
     */
    public function calculateBomCost(): float
    {
        return $this->billOfMaterialItems()
            ->with('part')
            ->get()
            ->sum(function ($item) {
                return $item->quantity * ($item->part->cost_price ?? 0);
            });
    }

    /**
     * Check if product can be manufactured with current stock.
     *
     * @param  int $quantity
     *
     * @return bool
     */
    public function canManufacture(int $quantity = 1): bool
    {
        return ProductManufacturingService::canManufacture($this, $quantity);
    }

    /**
     * Get missing parts for manufacturing.
     *
     * @param  int $quantity
     *
     * @return array
     */
    public function getMissingParts(int $quantity = 1): array
    {
        return ProductManufacturingService::getMissingParts($this, $quantity);
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
     * Adjust stock quantity.
     *
     * @param  int $quantity
     *
     * @return bool
     */
    public function adjustStock(int $quantity): bool
    {
        $this->quantity += $quantity;
        $this->status = $this->determineStockStatus();

        return $this->save();
    }

    /**
     * Get all available statuses.
     *
     * @return array<int,string>
     */
    public static function getProductStatus(): array
    {
        return [
            Product::STATUS_ACTIVE,
            Product::STATUS_DISCONTINUED,
            Product::STATUS_PENDING,
            Product::STATUS_OUT_OF_STOCK,
        ];
    }

    /**
     * Get a human-readable status label.
     *
     * @return string|null
     */
    public function getStatusLabel(): ?string
    {
        return ProductStatusResolver::resolveStatusLabel($this->status);
    }

    /**
     * Determine the appropriate status based on current quantity.
     *
     * @return string
     */
    protected function determineStockStatus(): string
    {
        return ProductStatusResolver::determineStockStatus($this);
    }

    /**
     * Format money with currency symbol.
     *
     * @param  float $amount
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
