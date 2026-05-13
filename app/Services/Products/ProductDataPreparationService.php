<?php

namespace App\Services\Products;

class ProductDataPreparationService
{
    /**
     * Prepare product data for creation.
     *
     * Merges all product data groups with appropriate defaults for new records.
     * All required fields must be present in the input data array.
     *
     * @param  array $data Raw input data containing product information
     * @param  int|null $createdBy User ID of the creator
     *
     * @return array Prepared data array ready for model creation
     */
    public function prepareForCreation(
        array $data,
        ?int $createdBy
    ): array {
        return array_merge(
            $this->getBaseCreateData($data),
            $this->getPriceCreateData($data),
            $this->getQuantityCreateData($data),
            $this->getMetaCreateData($data, $createdBy),
        );
    }

    /**
     * Prepare fillable data for update.
     *
     * Merges all product data groups, filtering out null values to prevent
     * overwriting existing data with nulls. Only non-null values will be updated.
     *
     * @param  array $data Raw input data containing fields to update
     * @param  int|null $updatedBy User ID of the updater
     *
     * @return array Prepared data array ready for model update
     */
    public function prepareForUpdate(array $data, ?int $updatedBy): array
    {
        return array_filter(
            array_merge(
                $this->getBaseUpdateData($data),
                $this->getPriceUpdateData($data),
                $this->getQuantityUpdateData($data),
                $this->getMetaUpdateData($data, $updatedBy),
            ),
            fn ($value) => $value !== null
        );
    }

    /**
     * Get base product data for creation.
     *
     * Extracts core product identification and description fields.
     * SKU and name are required; description is optional.
     *
     * @param  array $data Raw input data
     *
     * @return array Base product data with required fields
     */
    private function getBaseCreateData(array $data): array
    {
        return [
            'sku' => $data['sku'] ?? null,
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'status' => $data['status'] ?? 'active',
        ];
    }

    /**
     * Get base product data for update.
     *
     * Extracts core product identification and description fields.
     * All fields are optional for updates.
     *
     * @param  array $data Raw input data
     *
     * @return array Base product data with nullable fields
     */
    private function getBaseUpdateData(array $data): array
    {
        return [
            'sku' => $data['sku'] ?? null,
            'name' => $data['name'] ?? null,
            'description' => $data['description'] ?? null,
            'status' => $data['status'] ?? null,
        ];
    }

    /**
     * Get price product data for creation.
     *
     * Extracts pricing information with defaults.
     * Price is required; currency defaults to GBP.
     *
     * @param  array $data Raw input data
     *
     * @return array Price data with required price field
     */
    private function getPriceCreateData(array $data): array
    {
        return [
            'price' => $data['price'] ?? 0,
            'currency' => $data['currency'] ?? 'GBP',
        ];
    }

    /**
     * Get price product data for update.
     *
     * Extracts pricing information for updates.
     * All fields are optional.
     *
     * @param  array $data Raw input data
     *
     * @return array Price data with nullable fields
     */
    private function getPriceUpdateData(array $data): array
    {
        return [
            'price' => $data['price'] ?? null,
            'currency' => $data['currency'] ?? null,
        ];
    }

    /**
     * Get quantity and stock management data for creation.
     *
     * Extracts inventory and stock level information with defaults.
     * Quantity and min_stock_level default to 0.
     *
     * @param  array $data Raw input data
     *
     * @return array Quantity data with default values
     */
    private function getQuantityCreateData(array $data): array
    {
        return [
            'quantity' => $data['quantity'] ?? 0,
            'min_stock_level' => $data['min_stock_level'] ?? 0,
            'max_stock_level' => $data['max_stock_level'] ?? null,
            'reorder_point' => $data['reorder_point'] ?? null,
            'reorder_quantity' => $data['reorder_quantity'] ?? null,
            'lead_time_days' => $data['lead_time_days'] ?? null,
        ];
    }

    /**
     * Get quantity and stock management data for update.
     *
     * Extracts inventory and stock level information for updates.
     * All fields are optional.
     *
     * @param  array $data Raw input data
     *
     * @return array Quantity data with nullable fields
     */
    private function getQuantityUpdateData(array $data): array
    {
        return [
            'quantity' => $data['quantity'] ?? null,
            'min_stock_level' => $data['min_stock_level'] ?? null,
            'max_stock_level' => $data['max_stock_level'] ?? null,
            'reorder_point' => $data['reorder_point'] ?? null,
            'reorder_quantity' => $data['reorder_quantity'] ?? null,
            'lead_time_days' => $data['lead_time_days'] ?? null,
        ];
    }

    /**
     * Get metadata and tracking data for creation.
     *
     * Extracts metadata and creator tracking information.
     * is_real defaults to true.
     *
     * @param  array $data Raw input data
     * @param  int|null $createdBy User ID of the creator
     *
     * @return array Metadata with creator tracking
     */
    private function getMetaCreateData(
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
     * Get metadata and tracking data for update.
     *
     * Extracts metadata and updater tracking information.
     * All fields are optional.
     *
     * @param  array $data Raw input data
     * @param  int|null $updatedBy User ID of the updater
     *
     * @return array Metadata with updater tracking
     */
    private function getMetaUpdateData(
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

