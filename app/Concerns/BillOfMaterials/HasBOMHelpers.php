<?php

namespace App\Concerns\BillOfMaterials;

trait HasBOMHelpers
{
    // /**
    //  * Calculate total cost of this BOM.
    //  *
    //  * @return float
    //  */
    // public function calculateTotalCost(): float
    // {
    //     return $this->items()
    //         ->with('part')
    //         ->get()
    //         ->sum(function ($item) {
    //             return $item->quantity * ($item->part->cost_price ?? 0);
    //         });
    // }

    // /**
    //  * Check if all parts are available for manufacturing.
    //  *
    //  * @param  int $manufacturingQuantity
    //  *
    //  * @return array
    //  */
    // public function checkPartAvailability(
    //     int $manufacturingQuantity = 1
    // ): array {
    //     $availability = [
    //         'can_manufacture' => true,
    //         'parts' => [],
    //     ];

    //     $items = $this->items()->with('part')->get();

    //     foreach ($items as $item) {
    //         $requiredQuantity = $item->quantity * $manufacturingQuantity;
    //         $available = $item->part->quantity;
    //         $sufficient = $available >= $requiredQuantity;

    //         if (!$sufficient) {
    //             $availability['can_manufacture'] = false;
    //         }

    //         $availability['parts'][] = [
    //             'part_id' => $item->part_id,
    //             'part_name' => $item->part->name,
    //             'part_sku' => $item->part->sku,
    //             'required_per_unit' => $item->quantity,
    //             'required_total' => $requiredQuantity,
    //             'available' => $available,
    //             'sufficient' => $sufficient,
    //             'shortage' => max(0, $requiredQuantity - $available),
    //         ];
    //     }

    //     return $availability;
    // }

    /**
     * Clone this BOM with a new version.
     *
     * @param  string $newVersion
     *
     * @return self
     */
    public function cloneWithNewVersion(string $newVersion): self
    {
        $newBom = $this->replicate();
        $newBom->version = $newVersion;
        $newBom->bom_number = $this->generateBomNumber();
        $newBom->is_active = false;
        $newBom->effective_from = null;
        $newBom->effective_to = null;
        $newBom->save();

        // Clone all items
        foreach ($this->items as $item) {
            $newItem = $item->replicate();
            $newItem->bill_of_material_id = $newBom->id;
            $newItem->save();
        }

        return $newBom;
    }

    /**
     * Activate this BOM and deactivate others for the same product.
     *
     * @return bool
     */
    public function activate(): bool
    {
        // Deactivate all other BOMs for this product
        $this->newQuery()
            ->where('product_id', $this->product_id)
            ->where('id', '!=', $this->id)
            ->update(['is_active' => false]);

        // Activate this BOM
        $this->is_active = true;
        $this->effective_from = now();

        return $this->save();
    }

    /**
     * Generate a unique BOM number.
     *
     * @return string
     */
    protected function generateBomNumber(): string
    {
        $prefix = 'BOM';
        $date = now()->format('Ymd');
        $count = $this->newQuery()
            ->whereDate('created_at', today())
            ->count() + 1;

        return sprintf('%s-%s-%04d', $prefix, $date, $count);
    }
}
