<?php

namespace App\Services\BillOfMaterialItems;

use App\Models\BillOfMaterialItem;

class BOMItemFormatterService
{
    /**
     * Format a single BOM with all data.
     *
     * @param  BillOfMaterialItem $billOfMaterialItem
     *
     * @return array
     */
    public function format(
        BillOfMaterialItem $billOfMaterialItem
    ): array {
        return array_merge(
            $this->getBaseData($billOfMaterialItem),
            $this->getRelationshipData($billOfMaterialItem),
            $this->getQuantityData($billOfMaterialItem),
            $this->getBooleanFlags($billOfMaterialItem),
            $this->getMetaInformation($billOfMaterialItem),
            $this->getDateData($billOfMaterialItem),
        );
    }

    /**
     * Get the base data.
     *
     * @param  BillOfMaterialItem $billOfMaterialItem
     *
     * @return array
     */
    private function getBaseData(
        BillOfMaterialItem $billOfMaterialItem
    ): array {
        return [
            'id' => $billOfMaterialItem->id,
        ];
    }

    /**
     * Get the relationship data.
     *
     * @param  BillOfMaterialItem $billOfMaterialItem
     *
     * @return array
     */
    private function getRelationshipData(
        BillOfMaterialItem $billOfMaterialItem
    ): array {
        return [
            'bill_of_material_id' => $billOfMaterialItem->bill_of_material_id,
            'product_id' => $billOfMaterialItem->product_id,
            'part_id' => $billOfMaterialItem->part_id,
        ];
    }

    /**
     * Get the quantity data.
     *
     * @param  BillOfMaterialItem $billOfMaterialItem
     *
     * @return array
     */
    private function getQuantityData(
        BillOfMaterialItem $billOfMaterialItem
    ): array {
        return [
            'quantity' => $billOfMaterialItem->quantity,
            'sequence' => $billOfMaterialItem->sequence,
            'notes' => $billOfMaterialItem->notes,
        ];
    }

    /**
     * Get the boolean flags.
     *
     * @param  BillOfMaterialItem $billOfMaterialItem
     *
     * @return array
     */
    private function getBooleanFlags(
        BillOfMaterialItem $billOfMaterialItem
    ): array {
        return [
            'is_optional' => $billOfMaterialItem->is_optional,
            'is_real' => $billOfMaterialItem->is_real,
        ];
    }

    /**
     * Get the meta information.
     *
     * @param  BillOfMaterialItem $billOfMaterialItem
     *
     * @return array
     */
    private function getMetaInformation(
        BillOfMaterialItem $billOfMaterialItem
    ): array {
        return [
            'meta' => $billOfMaterialItem->meta,
        ];
    }

    /**
     * Get the date/audit data.
     *
     * @param  BillOfMaterialItem $billOfMaterialItem
     *
     * @return array
     */
    private function getDateData(BillOfMaterialItem $billOfMaterialItem): array
    {
        return [
            'created_at' => $billOfMaterialItem->created_at,
            'updated_at' => $billOfMaterialItem->updated_at,
            'deleted_at' => $billOfMaterialItem->deleted_at,
            'restored_at' => $billOfMaterialItem->restored_at,
            'created_by' => $billOfMaterialItem->created_by,
            'updated_by' => $billOfMaterialItem->updated_by,
            'deleted_by' => $billOfMaterialItem->deleted_by,
            'restored_by' => $billOfMaterialItem->restored_by,
        ];
    }
}
