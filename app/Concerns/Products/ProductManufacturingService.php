<?php

namespace App\Services\Product;

use App\Models\Product;
use Illuminate\Support\Collection;

class ProductManufacturingService
{
    /**
     * Check if product can be manufactured with current stock.
     *
     * @param  Product $product
     * @param  int $quantity
     *
     * @return bool
     */
    public static function canManufacture(Product $product, int $quantity = 1): bool
    {
        if (! $product->has_bom) {
            return false;
        }

        $items = $product->billOfMaterialItems()->with('part')->get();

        return self::allPartsAvailable($items, $quantity);
    }

    /**
     * Get missing parts for manufacturing.
     *
     * @param  Product $product
     * @param  int $quantity
     *
     * @return array
     */
    public static function getMissingParts(Product $product, int $quantity = 1): array
    {
        if (! $product->has_bom) {
            return [];
        }

        $items = $product->billOfMaterialItems()->with('part')->get();

        return self::calculateShortages($items, $quantity);
    }

    /**
     * Check if all parts are available for manufacturing.
     *
     * @param  Collection $items
     * @param  int $quantity
     *
     * @return bool
     */
    private static function allPartsAvailable($items, int $quantity): bool
    {
        foreach ($items as $item) {
            $requiredQuantity = $item->quantity * $quantity;
            if (! $item->part->hasSufficientStock($requiredQuantity)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Calculate part shortages for manufacturing.
     *
     * @param  Collection $items
     * @param  int $quantity
     *
     * @return array
     */
    private static function calculateShortages($items, int $quantity): array
    {
        $missing = [];

        foreach ($items as $item) {
            $requiredQuantity = $item->quantity * $quantity;
            $shortage = $requiredQuantity - $item->part->quantity;

            if ($shortage > 0) {
                $missing[] = [
                    'part' => $item->part,
                    'required' => $requiredQuantity,
                    'available' => $item->part->quantity,
                    'shortage' => $shortage,
                ];
            }
        }

        return $missing;
    }
}
