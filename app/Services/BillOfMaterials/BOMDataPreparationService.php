<?php

namespace App\Services\BillOfMaterials;

class BOMDataPreparationService
{
    /**
     * Prepare BOM data for creation.
     *
     * @param  array $data
     * @param  int|null $createdBy
     *
     * @return array
     */
    public function prepareForCreation(
        array $data,
        ?int $createdBy
    ): array {
        return array_merge(
            $this->getBaseCreateData($data),
            $this->getFlagAndMetaCreateData($data, $createdBy),
        );
    }

    /**
     * Prepare fillable data for update.
     *
     * @param  array $data
     * @param  int|null $updatedBy
     *
     * @return array
     */
    public function prepareForUpdate(array $data, ?int $updatedBy): array
    {
        return array_filter(
            array_merge(
                $this->getBaseUpdateData($data),
                $this->getFlagAndMetaUpdateData($data, $updatedBy),
            ),
            fn ($value) => $value !== null
        );
    }

    /**
     * Get base BOM data for creation.
     *
     * @param  array $data
     *
     * @return array
     */
    private function getBaseCreateData(array $data): array
    {
        return [
            'product_id' => $data['product_id'],
            'bom_number' => $data['bom_number'],
            'version' => $data['version'] ?? null,
            'description' => $data['description'] ?? null,
            'effective_from' => $data['effective_from'] ?? null,
            'effective_to' => $data['effective_to'] ?? null,
        ];
    }

    /**
     * Get base BOM data for update.
     *
     * @param  array $data
     *
     * @return array
     */
    private function getBaseUpdateData(array $data): array
    {
        return [
            'product_id' => $data['product_id'] ?? null,
            'bom_number' => $data['bom_number'] ?? null,
            'version' => $data['version'] ?? null,
            'description' => $data['description'] ?? null,
            'effective_from' => $data['effective_from'] ?? null,
            'effective_to' => $data['effective_to'] ?? null,
        ];
    }

    /**
     * Get flag and meta BOM data for creation.
     *
     * @param  array $data
     * @param  int|null $createdBy
     *
     * @return array
     */
    private function getFlagAndMetaCreateData(
        array $data,
        ?int $createdBy
    ): array {
        return [
            'is_active' => $data['is_active'] ?? true,
            'is_real' => $data['is_real'] ?? true,
            'meta' => $data['meta'] ?? null,
            'created_by' => $createdBy,
        ];
    }

    /**
     * Get flag and meta BOM data for update.
     *
     * @param  array $data
     * @param  int|null $updatedBy
     *
     * @return array
     */
    private function getFlagAndMetaUpdateData(
        array $data,
        ?int $updatedBy
    ): array {
        return [
            'is_active' => $data['is_active'] ?? null,
            'is_real' => $data['is_real'] ?? null,
            'meta' => $data['meta'] ?? null,
            'updated_by' => $updatedBy,
        ];
    }
}
