<?php

namespace App\Services\Parts;

class PartDataPreparationService
{
    /**
     * Prepare part data for creation.
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
        return [
            'sku' => $data['sku'],
            'part_number' => $data['part_number'] ?? null,
            'barcode' => $data['barcode'] ?? null,

            'name' => $data['name'],
            'description' => $data['description'],

            'brand' => $data['brand'] ?? null,
            'manufacturer' => $data['manufacturer'] ?? null,

            'type' => $data['type'] ?? 'finished_good',
            'status' => $data['status'] ?? 'active',

            'unit_of_measure' => $data['unit_of_measure'] ?? 'each',

            'height' => $data['height'] ?? null,
            'width' => $data['width'] ?? null,
            'length' => $data['length'] ?? null,
            'weight' => $data['weight'] ?? null,
            'volume' => $data['volume'] ?? null,

            'colour' => $data['colour'] ?? null,
            'material' => $data['material'] ?? null,

            'price' => $data['price'],
            'cost_price' => $data['cost_price'] ?? null,
            'currency' => $data['currency'] ?? 'GBP',
            'tax_rate' => $data['tax_rate'] ?? null,
            'tax_code' => $data['tax_code'] ?? null,
            'discount_percentage' => $data['discount_percentage'] ?? null,

            'quantity' => $data['quantity'] ?? 0,
            'min_stock_level' => $data['min_stock_level'] ?? 0,
            'max_stock_level' => $data['max_stock_level'] ?? null,
            'reorder_point' => $data['reorder_point'] ?? null,
            'reorder_quantity' => $data['reorder_quantity'] ?? null,
            'lead_time_days' => $data['lead_time_days'] ?? null,

            'warehouse_location' => $data['warehouse_location'] ?? null,
            'bin_location' => $data['bin_location'] ?? null,

            'is_active' => $data['is_active'] ?? true,
            'is_purchasable' => $data['is_purchasable'] ?? true,
            'is_sellable' => $data['is_sellable'] ?? true,
            'is_manufactured' => $data['is_manufactured'] ?? false,
            'is_serialised' => $data['is_serialised'] ?? false,
            'is_batch_tracked' => $data['is_batch_tracked'] ?? false,
            'is_real' => $data['is_real'] ?? true,

            'meta' => $data['meta'] ?? null,

            'created_by' => $createdBy,
        ];
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
        return array_filter([
            'sku' => $data['sku'] ?? null,
            'part_number' => $data['part_number'] ?? null,
            'barcode' => $data['barcode'] ?? null,

            'name' => $data['name'] ?? null,
            'description' => $data['description'] ?? null,

            'brand' => $data['brand'] ?? null,
            'manufacturer' => $data['manufacturer'] ?? null,

            'type' => $data['type'] ?? null,
            'status' => $data['status'] ?? null,

            'unit_of_measure' => $data['unit_of_measure'] ?? null,

            'height' => $data['height'] ?? null,
            'width' => $data['width'] ?? null,
            'length' => $data['length'] ?? null,
            'weight' => $data['weight'] ?? null,
            'volume' => $data['volume'] ?? null,

            'colour' => $data['colour'] ?? null,
            'material' => $data['material'] ?? null,

            'price' => $data['price'] ?? null,
            'cost_price' => $data['cost_price'] ?? null,
            'currency' => $data['currency'] ?? null,
            'tax_rate' => $data['tax_rate'] ?? null,
            'tax_code' => $data['tax_code'] ?? null,
            'discount_percentage' => $data['discount_percentage'] ?? null,

            'quantity' => $data['quantity'] ?? null,
            'min_stock_level' => $data['min_stock_level'] ?? null,
            'max_stock_level' => $data['max_stock_level'] ?? null,
            'reorder_point' => $data['reorder_point'] ?? null,
            'reorder_quantity' => $data['reorder_quantity'] ?? null,
            'lead_time_days' => $data['lead_time_days'] ?? null,

            'warehouse_location' => $data['warehouse_location'] ?? null,
            'bin_location' => $data['bin_location'] ?? null,

            'is_active' => $data['is_active'] ?? null,
            'is_purchasable' => $data['is_purchasable'] ?? null,
            'is_sellable' => $data['is_sellable'] ?? null,
            'is_manufactured' => $data['is_manufactured'] ?? null,
            'is_serialised' => $data['is_serialised'] ?? null,
            'is_batch_tracked' => $data['is_batch_tracked'] ?? null,
            'is_real' => $data['is_real'] ?? null,

            'meta' => $data['meta'] ?? null,

            'updated_by' => $updatedBy,
        ], fn ($value) => $value !== null);
    }
}
