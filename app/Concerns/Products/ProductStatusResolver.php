<?php

namespace App\Services\Product;

use App\Models\Product;

class ProductStatusResolver
{
    /**
     * Map of status constants to labels.
     *
     * @var array<string,string>
     */
    private static array $statusLabels = [
        Product::STATUS_ACTIVE => 'Active',
        Product::STATUS_DISCONTINUED => 'Discontinued',
        Product::STATUS_PENDING => 'Pending',
        Product::STATUS_OUT_OF_STOCK => 'Out of Stock',
    ];

    /**
     * Resolve stock status from product state.
     *
     * @param  Product $product
     *
     * @return string
     */
    public static function resolveStockStatus(Product $product): string
    {
        foreach (self::getStockStatusChecks() as $status => $check) {
            if ($check($product)) {
                return $status;
            }
        }

        return 'In Stock';
    }

    /**
     * Resolve human-readable status label.
     *
     * @param  string|null $status
     *
     * @return string|null
     */
    public static function resolveStatusLabel(?string $status): ?string
    {
        if ($status === null) {
            return null;
        }

        return self::$statusLabels[$status]
            ?? ucfirst(str_replace('_', ' ', $status));
    }

    /**
     * Determine stock status based on quantity.
     *
     * @param  Product $product
     *
     * @return string
     */
    public static function determineStockStatus(Product $product): string
    {
        return match (true) {
            self::shouldBeOutOfStock($product) => Product::STATUS_OUT_OF_STOCK,
            self::shouldBeReactivated($product) => Product::STATUS_ACTIVE,
            default => $product->status,
        };
    }

    /**
     * Stock status resolution priority order.
     *
     * @var array<string,callable>
     */
    private static function getStockStatusChecks(): array
    {
        return [
            'Out of Stock' => fn (Product $p) => $p->isOutOfStock(),
            'Needs Reorder' => fn (Product $p) => $p->needsReorder(),
            'Low Stock' => fn (Product $p) => $p->isLowStock(),
        ];
    }

    /**
     * Check if product should be marked as out of stock.
     *
     * @param  Product $product
     *
     * @return bool
     */
    private static function shouldBeOutOfStock(Product $product): bool
    {
        return $product->quantity <= 0;
    }

    /**
     * Check if product should be reactivated from out of stock.
     *
     * @param  Product $product
     *
     * @return bool
     */
    private static function shouldBeReactivated(Product $product): bool
    {
        return $product->status === Product::STATUS_OUT_OF_STOCK;
    }
}
