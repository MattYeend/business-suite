<?php

namespace App\Services\Parts;

use App\Models\Part;

class PartFormatterService
{
    /**
     * Format a single part with all data.
     *
     * @param  Part $part
     *
     * @return array
     */
    public function format(Part $part): array
    {
        return array_merge(
            $this->getBaseData($part),
            $this->getIdentificationInformation($part),
            $this->getClassificationInformation($part),
            $this->getDimensionInformation($part),
            $this->getPricingInformation($part),
            $this->getInventoryInformation($part),
            $this->getLocationInformation($part),
            $this->getBooleanFlags($part),
            $this->getMetaInformation($part),
            $this->getDateData($part),
        );
    }

    /**
     * Get the base data.
     *
     * @param  Part $part
     *
     * @return array
     */
    private function getBaseData(Part $part): array
    {
        return [
            'id' => $part->id,
            'name' => $part->name,
            'description' => $part->description,
        ];
    }

    /**
     * Get the identification information.
     *
     * @param  Part $part
     *
     * @return array
     */
    private function getIdentificationInformation(Part $part): array
    {
        return [
            'sku' => $part->sku,
            'part_number' => $part->part_number,
            'barcode' => $part->barcode,
            'brand' => $part->brand,
            'manufacturer' => $part->manufacturer,
        ];
    }

    /**
     * Get the classification information.
     *
     * @param  Part $part
     *
     * @return array
     */
    private function getClassificationInformation(Part $part): array
    {
        return [
            'type' => $part->type,
            'status' => $part->status,
            'unit_of_measure' => $part->unit_of_measure,
            'colour' => $part->colour,
            'material' => $part->material,
        ];
    }

    /**
     * Get the dimensions.
     *
     * @param  Part $part
     *
     * @return array
     */
    private function getDimensionInformation(Part $part): array
    {
        return [
            'height' => $part->height,
            'width' => $part->width,
            'length' => $part->length,
            'weight' => $part->weight,
            'volume' => $part->volume,
        ];
    }

    /**
     * Get the pricing information.
     *
     * @param  Part $part
     *
     * @return array
     */
    private function getPricingInformation(Part $part): array
    {
        return [
            'price' => $part->price,
            'cost_price' => $part->cost_price,
            'currency' => $part->currency,
            'tax_rate' => $part->tax_rate,
            'tax_code' => $part->tax_code,
            'discount_percentage' => $part->discount_percentage,
        ];
    }

    /**
     * Get the inventory information.
     *
     * @param  Part $part
     *
     * @return array
     */
    private function getInventoryInformation(Part $part): array
    {
        return [
            'quantity' => $part->quantity,
            'min_stock_level' => $part->min_stock_level,
            'max_stock_level' => $part->max_stock_level,
            'reorder_point' => $part->reorder_point,
            'reorder_quantity' => $part->reorder_quantity,
            'lead_time_days' => $part->lead_time_days,
        ];
    }

    /**
     * Get the warehouse and bin location information.
     *
     * @param  Part $part
     *
     * @return array
     */
    private function getLocationInformation(Part $part): array
    {
        return [
            'warehouse_location' => $part->warehouse_location,
            'bin_location' => $part->bin_location,
        ];
    }

    /**
     * Get the boolean flags.
     *
     * @param  Part $part
     *
     * @return array
     */
    private function getBooleanFlags(Part $part): array
    {
        return [
            'is_active' => $part->is_active,
            'is_purchasable' => $part->is_purchasable,
            'is_sellable' => $part->is_sellable,
            'is_manufactured' => $part->is_manufactured,
            'is_serialised' => $part->is_serialised,
            'is_batch_tracked' => $part->is_batch_tracked,
        ];
    }

    /**
     * Get the meta information.
     *
     * @param  Part $part
     *
     * @return array
     */
    private function getMetaInformation(Part $part): array
    {
        return [
            'meta' => $part->meta,
        ];
    }

    /**
     * Get the date/audit data.
     *
     * @param  Part $part
     *
     * @return array
     */
    private function getDateData(Part $part): array
    {
        return [
            'created_at' => $part->created_at,
            'updated_at' => $part->updated_at,
            'deleted_at' => $part->deleted_at,
            'restored_at' => $part->restored_at,
            'created_by' => $part->created_by,
            'updated_by' => $part->updated_by,
            'deleted_by' => $part->deleted_by,
            'restored_by' => $part->restored_by,
        ];
    }
}
