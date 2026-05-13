<?php

namespace App\Concerns\Products;

use App\Models\Product;

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
    // /**
    //  * Calculate total BOM cost for this product.
    //  *
    //  * @return float
    //  */
    // public function calculateBomCost(): float
    // {
    //     return $this->billOfMaterialItems()
    //         ->with('part')
    //         ->get()
    //         ->sum(function ($item) {
    //             return $item->quantity * ($item->part->cost_price ?? 0);
    //         });
    // }

    // /**
    //  * Check if product can be manufactured with current stock.
    //  *
    //  * @return bool
    //  */
    // public function canManufacture(int $quantity = 1): bool
    // {
    //     if (!$this->has_bom) {
    //         return false;
    //     }

    //     $items = $this->billOfMaterialItems()->with('part')->get();

    //     foreach ($items as $item) {
    //         $requiredQuantity = $item->quantity * $quantity;
    //         if (!$item->part->hasSufficientStock($requiredQuantity)) {
    //             return false;
    //         }
    //     }

    //     return true;
    // }

    // /**
    //  * Get missing parts for manufacturing.
    //  *
    //  * @param  int $quantity
    //  *
    //  * @return array
    //  */
    // public function getMissingParts(int $quantity = 1): array
    // {
    //     if (!$this->has_bom) {
    //         return [];
    //     }

    //     $missing = [];
    //     $items = $this->billOfMaterialItems()->with('part')->get();

    //     foreach ($items as $item) {
    //         $requiredQuantity = $item->quantity * $quantity;
    //         $shortage = $requiredQuantity - $item->part->quantity;
    //         if ($shortage > 0) {
    //             $missing[] = [
    //                 'part' => $item->part,
    //                 'required' => $requiredQuantity,
    //                 'available' => $item->part->quantity,
    //                 'shortage' => $shortage,
    //             ];
    //         }
    //     }

    //     return $missing;
    // }

    /**
     * Adjust stock quantity.
     *
     * @param  int $quantity
     * @param  string|null $reason
     *
     * @return bool
     */
    public function adjustStock(int $quantity, ?string $reason = null): bool
    {
        $this->quantity += $quantity;

        // Update status based on new quantity
        if ($this->quantity <= 0) {
            $this->status = Product::STATUS_OUT_OF_STOCK;
        } elseif (
            $this->status === Product::STATUS_OUT_OF_STOCK
            &&
            $this->quantity > 0
        ) {
            $this->status = 'active';
        }

        return $this->save();
    }

    /**
     * Check if product has sufficient stock.
     *
     * @param  int $requiredQuantity
     *
     * @return bool
     */
    public function hasSufficientStock(int $requiredQuantity): bool
    {
        return $this->quantity >= $requiredQuantity;
    }

    /**
     * Determine whether the product is of a given status.
     *
     * @param  string $status
     *
     * @return bool
     */
    public function isStatus(string $status): bool
    {
        return $this->status === $status;
    }

    /**
     * Determine whether the status is active.
     *
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->status === Product::STATUS_ACTIVE;
    }

    /**
     * Determine whether the status is discontinued.
     *
     * @return bool
     */
    public function isDiscontinued(): bool
    {
        return $this->status === Product::STATUS_DISCONTINUED;
    }

    /**
     * Determine whether the status is pending.
     *
     * @return bool
     */
    public function isPending(): bool
    {
        return $this->status === Product::STATUS_PENDING;
    }

    /**
     * Determine whether the status is out of stock.
     *
     * @return bool
     */
    public function isOutOfStock(): bool
    {
        return $this->status === Product::STATUS_OUT_OF_STOCK;
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
     * Check if part has reorder point set.
     *
     * @return bool
     */
    public function hasReorderPoint(): bool
    {
        return $this->reorder_point !== null;
    }

    /**
     * Check if part needs reordering.
     *
     * @return bool
     */
    public function needsReorder(): bool
    {
        return $this->hasReorderPoint()
            && $this->quantity <= $this->reorder_point;
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
        return match ($this->status) {
            Product::STATUS_ACTIVE => 'Active',
            Product::STATUS_DISCONTINUED => 'Discontinued',
            Product::STATUS_PENDING => 'Pending',
            Product::STATUS_OUT_OF_STOCK => 'Out of stock',
            default => $this->status ? ucfirst(
                str_replace('_', ' ', $this->status)
            ) : null,
        };
    }
}
