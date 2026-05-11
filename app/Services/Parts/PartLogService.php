<?php

namespace App\Services\Parts;

use App\Models\Log;
use App\Models\Part;
use App\Models\User;

class PartLogService
{
    /**
     * Log part creation.
     *
     * @param  Part $part
     * @param  User $actor
     * @param  int $actorId
     *
     * @return array
     */
    public function logCreation(
        Part $part,
        User $actor,
        int $actorId
    ): array {
        $data = $this->basePartData($part) + [
            'created_at' => now(),
            'created_by' => $actor?->name,
        ];

        Log::log(
            Log::ACTION_CREATE_PART,
            $data,
            $actorId,
        );

        return $data;
    }

    /**
     * Log a part show event.
     *
     * @param  Part $part
     * @param  User $actor
     * @param  int $actorId
     *
     * @return array
     */
    public function logShow(
        Part $part,
        User $actor,
        int $actorId
    ): array {
        $data = $this->basePartData($part) + [
            'shown_at' => now(),
            'shown_by' => $actor?->name,
        ];

        Log::log(
            Log::ACTION_SHOW_PART,
            $data,
            $actorId,
        );

        return $data;
    }

    /**
     * Log a part update event.
     *
     * @param  Part $part
     * @param  User $actor
     * @param  int $actorId
     *
     * @return array
     */
    public function logUpdate(
        Part $part,
        User $actor,
        int $actorId
    ): array {
        $data = $this->basePartData($part) + [
            'updated_at' => now(),
            'updated_by' => $actor?->name,
        ];

        Log::log(
            Log::ACTION_UPDATE_PART,
            $data,
            $actorId,
        );

        return $data;
    }

    /**
     * Log a part deletion event.
     *
     * @param  Part $part
     * @param  User $actor
     * @param  int $actorId
     *
     * @return array
     */
    public function logDeletion(
        Part $part,
        User $actor,
        int $actorId
    ): array {
        $data = $this->basePartData($part) + [
            'deleted_at' => now(),
            'deleted_by' => $actor?->name,
        ];

        Log::log(
            Log::ACTION_DELETE_PART,
            $data,
            $actorId,
        );

        return $data;
    }

    /**
     * Log part force deletion.
     *
     * @param  Part $part
     * @param  User $actor
     * @param  int $actorId
     *
     * @return array
     */
    public function logForceDeletion(
        Part $part,
        User $actor,
        int $actorId
    ): array {
        $data = $this->basePartData($part) + [
            'force_deleted_at' => now(),
            'force_deleted_by' => $actor?->name,
        ];

        Log::log(
            Log::ACTION_FORCE_DELETE_PART,
            $data,
            $actorId,
        );

        return $data;
    }

    /**
     * Log a part restoration event.
     *
     * @param  Part $part
     * @param  User $actor
     * @param  int $actorId
     *
     * @return array
     */
    public function logRestoration(
        Part $part,
        User $actor,
        int $actorId
    ): array {
        $data = $this->basePartData($part) + [
            'restored_at' => now(),
            'restored_by' => $actor?->name,
        ];

        Log::log(
            Log::ACTION_RESTORE_PART,
            $data,
            $actorId,
        );

        return $data;
    }

    /**
     * Log a part import event.
     *
     * @param  array $importData
     * @param  User $actor
     * @param  int $actorId
     *
     * @return array
     */
    public function logImport(
        array $importData,
        User $actor,
        int $actorId
    ): array {
        $data = [
            'imported_at' => now(),
            'imported_by' => $actor?->name,
            'imported_count' => count($importData),
            'imported_data_sample' => array_slice($importData, 0, 5),
        ];

        Log::log(
            Log::ACTION_IMPORT_PART,
            $data,
            $actorId,
        );

        return $data;
    }

    /**
     * Log a part export event.
     *
     * @param  array $exportData
     * @param  User $actor
     * @param  int $actorId
     *
     * @return array
     */
    public function logExport(
        array $exportData,
        User $actor,
        int $actorId
    ): array {
        $data = [
            'exported_at' => now(),
            'exported_by' => $actor?->name,
            'exported_count' => count($exportData),
            'exported_data_sample' => array_slice($exportData, 0, 5),
        ];

        Log::log(
            Log::ACTION_EXPORT_PART,
            $data,
            $actorId,
        );

        return $data;
    }

    /**
     * Log a part update event performed by cron.
     *
     * @param  Part $part
     *
     * @return array
     */
    public function logUpdateByCron(
        Part $part,
    ): array {
        $data = $this->basePartData($part) + [
            'updated_at' => now(),
            'updated_by' => 'System (Cron)',
        ];

        Log::log(
            Log::ACTION_PART_UPDATED_BY_CRON,
            $data,
            null,
        );

        return $data;
    }

    /**
     * Get base part data for logging.
     *
     * @param  Part|null $part
     *
     * @return array
     */
    protected function basePartData(?Part $part): array
    {
        if (! $part) {
            return $this->getNullData();
        }

        return $this->getPartData($part);
    }

    /**
     * Get null part data.
     *
     * @return array
     */
    private function getNullData(): array
    {
        return array_merge(
            $this->getNullBaseData(),
            $this->getNullMeasureData(),
            $this->getNullPriceData(),
            $this->getNullQuantityData(),
            $this->getNullFlagAndMetaData(),
        );
    }

    /**
     * Get part data.
     *
     * @param  Part $part
     *
     * @return array
     */
    private function getPartData(Part $part): array
    {
        return array_merge(
            $this->getBaseData($part),
            $this->getMeasureData($part),
            $this->getPriceData($part),
            $this->getQuantityData($part),
            $this->getFlagAndMetaData($part),
        );
    }

    /**
     * Get null base part data.
     *
     * @return array
     */
    private function getNullBaseData(): array
    {
        return [
            'id' => null,
            'sku' => null,
            'part_number' => null,
            'barcode' => null,
            'name' => null,
            'description' => null,
        ];
    }
    
    /**
     * Get base part data.
     *
     * @param  Part $part
     *
     * @return array
     */
    private function getBaseData(Part $part): array
    {
        return [
            'id' => $part->id,
            'sku' => $part->sku,
            'part_number' => $part->part_number,
            'barcode' => $part->barcode,
            'name' => $part->name,
            'description' => $part->description,
        ];
    }

    /**
     * Get null measurable part data.
     *
     * @return array
     */
    private function getNullMeasureData(): array
    {
        return [
            'brand' => null,
            'manufacturer' => null,
            'type' => null,
            'status' => null,
            'unit_of_measure' => null,
            'height' => null,
            'width' => null,
            'length' => null,
            'weight' => null,
            'volume' => null,
            'colour' => null,
            'material' => null,
        ];
    }

    /**
     * Get measurable part data.
     *
     * @param  Part $part
     *
     * @return array
     */
    private function getMeasureData(Part $part): array
    {
        return [
            'brand' => $part->brand,
            'manufacturer' => $part->manufacturer,
            'type' => $part->type,
            'status' => $part->status,
            'unit_of_measure' => $part->unit_of_measure,
            'height' => $part->height,
            'width' => $part->width,
            'length' => $part->length,
            'weight' => $part->weight,
            'volume' => $part->volume,
            'colour' => $part->colour,
            'material' => $part->material,
        ];
    }

    /**
     * Get null price part data.
     *
     * @return array
     */
    private function getNullPriceData(): array
    {
        return [
            'price' => null,
            'cost_price' => null,
            'currency' => null,
            'tax_rate' => null,
            'tax_code' => null,
            'discount_percentage' => null,
        ];
    }

    /**
     * Get price part data.
     *
     * @param  Part $part
     *
     * @return array
     */
    private function getPriceData(Part $part): array
    {
        return [
            'price' => $part->price,
            'cost_price' => $part->cost_price,
            'currency' => $part->currency,
            'tax_rate' => $part->tax_code,
            'tax_code' => $part->tax_rate,
            'discount_percentage' => $part->discount_percentage,
        ];
    }

    /**
     * Get null quantity part data.
     *
     * @return array
     */
    private function getNullQuantityData(): array
    {
        return [
            'quantity' => null,
            'min_stock_level' => null,
            'max_stock_level' => null,
            'reorder_point' => null,
            'reorder_quantity' => null,
            'lead_time_days' => null,
            'warehouse_location' => null,
            'bin_location' => null,
        ];
    }

    /**
     * Get quantity part data.
     *
     * @param  Part $part
     *
     * @return array
     */
    private function getQuantityData(Part $part): array
    {
        return [
            'quantity' => $part->quantity,
            'min_stock_level' => $part->min_stock_level,
            'max_stock_level' => $part->max_stock_level,
            'reorder_point' => $part->reorder_point,
            'reorder_quantity' => $part->reorder_quantity,
            'lead_time_days' => $part->lead_time_days,
            'warehouse_location' => $part->warehouse_location,
            'bin_location' => $part->bin_location,
        ];
    }

    /**
     * Get null flag and meta part data.
     *
     * @return array
     */
    private function getNullFlagAndMetaData(): array
    {
        return [
            'is_active' => null,
            'is_purchasable' => null,
            'is_sellable' => null,
            'is_manufactured' => null,
            'is_serialised' => null,
            'is_batch_tracked' => null,
            'meta' => null,
        ];
    }

    /**
     * Get flag part data.
     *
     * @param  Part $part
     *
     * @return array
     */
    private function getFlagAndMetaData(Part $part): array
    {
        return [
            'is_active' => $part->is_active,
            'is_purchasable' => $part->is_purchasable,
            'is_sellable' => $part->is_sellable,
            'is_manufactured' => $part->is_manufactured,
            'is_serialised' => $part->is_serialised,
            'is_batch_tracked' => $part->is_batch_tracked,
            'meta' => $part->meta,
        ];
    }
}
