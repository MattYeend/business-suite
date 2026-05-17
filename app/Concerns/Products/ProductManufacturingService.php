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
    public static function canManufacture(
        Product $product,
        int $quantity = 1
    ): bool {
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
    public static function getMissingParts(
        Product $product,
        int $quantity = 1
    ): array {
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
    private static function allPartsAvailable(
        Collection $items,
        int $quantity
    ): bool {
        return $items->every(function ($item) use ($quantity) {
            $requiredQuantity = $item->quantity * $quantity;
            return $item->part->hasSufficientStock($requiredQuantity);
        });
    }

    /**
     * Calculate part shortages for manufacturing.
     *
     * @param  Collection $items
     * @param  int $quantity
     *
     * @return array
     */
    private static function calculateShortages(
        Collection $items,
        int $quantity
    ): array {
        return $items
            ->map(fn ($item) => self::calculateItemShortage($item, $quantity))
            ->filter(fn ($result) => $result !== null)
            ->values()
            ->toArray();
    }

    /**
     * Calculate shortage for a single BOM item.
     *
     * @param  mixed $item
     * @param  int $quantity
     *
     * @return array|null
     */
    private static function calculateItemShortage($item, int $quantity): ?array
    {
        $requiredQuantity = $item->quantity * $quantity;
        $shortage = $requiredQuantity - $item->part->quantity;

        if ($shortage <= 0) {
            return null;
        }

        return [
            'part' => $item->part,
            'required' => $requiredQuantity,
            'available' => $item->part->quantity,
            'shortage' => $shortage,
        ];
    }
}
