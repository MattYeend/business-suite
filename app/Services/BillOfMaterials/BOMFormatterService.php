<?php

namespace App\Services\BillOfMaterials;

use App\Models\BillOfMaterial;

class BOMFormatterService
{
    /**
     * Format a single BOM with all data.
     *
     * @param  BillOfMaterial $billOfMaterial
     *
     * @return array
     */
    public function format(BillOfMaterial $billOfMaterial): array
    {
        return array_merge(
            $this->getBaseData($billOfMaterial),
            $this->getRelationshipData($billOfMaterial),
            $this->getVersioningData($billOfMaterial),
            $this->getBooleanFlags($billOfMaterial),
            $this->getMetaInformation($billOfMaterial),
            $this->getDateData($billOfMaterial),
        );
    }

    /**
     * Get the base data.
     *
     * @param  BillOfMaterial $billOfMaterial
     *
     * @return array
     */
    private function getBaseData(BillOfMaterial $billOfMaterial): array
    {
        return [
            'id' => $billOfMaterial->id,
            'bom_number' => $billOfMaterial->bom_number,
            'description' => $billOfMaterial->description,
        ];
    }

    /**
     * Get the relationship data.
     *
     * @param  BillOfMaterial $billOfMaterial
     *
     * @return array
     */
    private function getRelationshipData(BillOfMaterial $billOfMaterial): array
    {
        return [
            'product_id' => $billOfMaterial->product_id,
        ];
    }

    /**
     * Get the versioning and effectivity data.
     *
     * @param  BillOfMaterial $billOfMaterial
     *
     * @return array
     */
    private function getVersioningData(BillOfMaterial $billOfMaterial): array
    {
        return [
            'version' => $billOfMaterial->version,
            'effective_from' => $billOfMaterial->effective_from,
            'effective_to' => $billOfMaterial->effective_to,
        ];
    }

    /**
     * Get the boolean flags.
     *
     * @param  BillOfMaterial $billOfMaterial
     *
     * @return array
     */
    private function getBooleanFlags(BillOfMaterial $billOfMaterial): array
    {
        return [
            'is_active' => $billOfMaterial->is_active,
            'is_real' => $billOfMaterial->is_real,
        ];
    }

    /**
     * Get the meta information.
     *
     * @param  BillOfMaterial $billOfMaterial
     *
     * @return array
     */
    private function getMetaInformation(BillOfMaterial $billOfMaterial): array
    {
        return [
            'meta' => $billOfMaterial->meta,
        ];
    }

    /**
     * Get the date/audit data.
     *
     * @param  BillOfMaterial $billOfMaterial
     *
     * @return array
     */
    private function getDateData(BillOfMaterial $billOfMaterial): array
    {
        return [
            'created_at' => $billOfMaterial->created_at,
            'updated_at' => $billOfMaterial->updated_at,
            'deleted_at' => $billOfMaterial->deleted_at,
            'restored_at' => $billOfMaterial->restored_at,
            'created_by' => $billOfMaterial->created_by,
            'updated_by' => $billOfMaterial->updated_by,
            'deleted_by' => $billOfMaterial->deleted_by,
            'restored_by' => $billOfMaterial->restored_by,
        ];
    }
}
