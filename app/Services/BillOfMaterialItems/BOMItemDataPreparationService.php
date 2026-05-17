<?php

namespace App\Services\BillOfMaterialItems;

class BOMItemDataPreparationService
{
    /**
     * Prepare BOMItem data for creation.
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
     * Get base BOMItem data for creation.
     *
     * @param  array $data
     *
     * @return array
     */
    private function getBaseCreateData(array $data): array
    {
        return [
            'bill_of_material_id' => $data['bill_of_material_id'],
            'product_id' => $data['product_id'],
            'part_id' => $data['part_id'],
            'quantity' => $data['quantity'],
            'sequence' => $data['sequence'] ?? null,
            'notes' => $data['notes'] ?? null,
            'is_optional' => $data['is_optional'],
        ];
    }

    /**
     * Get base BOMItem data for update.
     *
     * @param  array $data
     *
     * @return array
     */
    private function getBaseUpdateData(array $data): array
    {
        return [
            'bill_of_material_id' => $data['bill_of_material_id'] ?? null,
            'product_id' => $data['product_id'] ?? null,
            'part_id' => $data['part_id'] ?? null,
            'quantity' => $data['quantity'] ?? null,
            'sequence' => $data['sequence'] ?? null,
            'notes' => $data['notes'] ?? null,
            'is_optional' => $data['is_optional'] ?? null,
        ];
    }

    /**
     * Get flag and meta BOMItem data for creation.
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
            'is_real' => $data['is_real'] ?? true,
            'meta' => $data['meta'] ?? null,
            'created_by' => $createdBy,
        ];
    }

    /**
     * Get flag and meta BOMItem data for update.
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
            'is_real' => $data['is_real'] ?? null,
            'meta' => $data['meta'] ?? null,
            'updated_by' => $updatedBy,
        ];
    }
}
