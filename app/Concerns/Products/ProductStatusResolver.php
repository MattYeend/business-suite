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
        if ($product->isOutOfStock()) {
            return 'Out of Stock';
        }

        if ($product->needsReorder()) {
            return 'Needs Reorder';
        }

        if ($product->isLowStock()) {
            return 'Low Stock';
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

        if (isset(self::$statusLabels[$status])) {
            return self::$statusLabels[$status];
        }

        return ucfirst(str_replace('_', ' ', $status));
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
        if ($product->quantity <= 0) {
            return Product::STATUS_OUT_OF_STOCK;
        }

        if ($product->status === Product::STATUS_OUT_OF_STOCK) {
            return Product::STATUS_ACTIVE;
        }

        return $product->status;
    }
}
