<?php

namespace App\Services\BillOfMaterialItems;

use App\Models\BillOfMaterialItem;
use App\Models\Log;
use App\Models\User;

class BOMItemLogService
{
    /**
     * Log creation.
     *
     * @param  BillOfMaterialItem $billOfMaterialItem
     * @param  User $actor
     * @param  int $actorId
     *
     * @return array
     */
    public function logCreation(
        BillOfMaterialItem $billOfMaterialItem,
        User $actor,
        int $actorId
    ): array {
        $data = $this->basePartData($billOfMaterialItem) + [
            'created_at' => now(),
            'created_by' => $actor?->name,
        ];

        Log::log(
            Log::ACTION_CREATE_BILL_OF_MATERIAL_ITEM,
            $data,
            $actorId,
        );

        return $data;
    }

    /**
     * Log a show event.
     *
     * @param  BillOfMaterialItem $billOfMaterialItem
     * @param  User $actor
     * @param  int $actorId
     *
     * @return array
     */
    public function logShow(
        BillOfMaterialItem $billOfMaterialItem,
        User $actor,
        int $actorId
    ): array {
        $data = $this->basePartData($billOfMaterialItem) + [
            'shown_at' => now(),
            'shown_by' => $actor?->name,
        ];

        Log::log(
            Log::ACTION_SHOW_BILL_OF_MATERIAL_ITEM,
            $data,
            $actorId,
        );

        return $data;
    }

    /**
     * Log a update event.
     *
     * @param  BillOfMaterialItem $billOfMaterialItem
     * @param  User $actor
     * @param  int $actorId
     *
     * @return array
     */
    public function logUpdate(
        BillOfMaterialItem $billOfMaterialItem,
        User $actor,
        int $actorId
    ): array {
        $data = $this->basePartData($billOfMaterialItem) + [
            'updated_at' => now(),
            'updated_by' => $actor?->name,
        ];

        Log::log(
            Log::ACTION_UPDATE_BILL_OF_MATERIAL_ITEM,
            $data,
            $actorId,
        );

        return $data;
    }

    /**
     * Log a deletion event.
     *
     * @param  BillOfMaterialItem $billOfMaterialItem
     * @param  User $actor
     * @param  int $actorId
     *
     * @return array
     */
    public function logDeletion(
        BillOfMaterialItem $billOfMaterialItem,
        User $actor,
        int $actorId
    ): array {
        $data = $this->basePartData($billOfMaterialItem) + [
            'deleted_at' => now(),
            'deleted_by' => $actor?->name,
        ];

        Log::log(
            Log::ACTION_DELETE_BILL_OF_MATERIAL_ITEM,
            $data,
            $actorId,
        );

        return $data;
    }

    /**
     * Log force deletion.
     *
     * @param  BillOfMaterialItem $billOfMaterialItem
     * @param  User $actor
     * @param  int $actorId
     *
     * @return array
     */
    public function logForceDeletion(
        BillOfMaterialItem $billOfMaterialItem,
        User $actor,
        int $actorId
    ): array {
        $data = $this->basePartData($billOfMaterialItem) + [
            'force_deleted_at' => now(),
            'force_deleted_by' => $actor?->name,
        ];

        Log::log(
            Log::ACTION_FORCE_DELETE_BILL_OF_MATERIAL_ITEM,
            $data,
            $actorId,
        );

        return $data;
    }

    /**
     * Log a restoration event.
     *
     * @param  BillOfMaterialItem $billOfMaterialItem
     * @param  User $actor
     * @param  int $actorId
     *
     * @return array
     */
    public function logRestoration(
        BillOfMaterialItem $billOfMaterialItem,
        User $actor,
        int $actorId
    ): array {
        $data = $this->basePartData($billOfMaterialItem) + [
            'restored_at' => now(),
            'restored_by' => $actor?->name,
        ];

        Log::log(
            Log::ACTION_RESTORE_BILL_OF_MATERIAL_ITEM,
            $data,
            $actorId,
        );

        return $data;
    }

    /**
     * Log a import event.
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
            Log::ACTION_IMPORT_BILL_OF_MATERIAL_ITEM,
            $data,
            $actorId,
        );

        return $data;
    }

    /**
     * Log a export event.
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
            Log::ACTION_EXPORT_BILL_OF_MATERIAL_ITEM,
            $data,
            $actorId,
        );

        return $data;
    }

    /**
     * Log a update event performed by cron.
     *
     * @param  BillOfMaterialItem $billOfMaterialItem
     *
     * @return array
     */
    public function logUpdateByCron(
        BillOfMaterialItem $billOfMaterialItem,
    ): array {
        $data = $this->basePartData($billOfMaterialItem) + [
            'updated_at' => now(),
            'updated_by' => 'System (Cron)',
        ];

        Log::log(
            Log::ACTION_BILL_OF_MATERIAL_ITEM_UPDATED_BY_CRON,
            $data,
            null,
        );

        return $data;
    }

    /**
     * Get base data for logging.
     *
     * @param  BillOfMaterialItem|null $billOfMaterialItem
     *
     * @return array
     */
    protected function basePartData(
        ?BillOfMaterialItem $billOfMaterialItem
    ): array {
        if (! $billOfMaterialItem) {
            return $this->getNullData();
        }

        return $this->getPartData($billOfMaterialItem);
    }

    /**
     * Get null data.
     *
     * @return array
     */
    private function getNullData(): array
    {
        return array_merge(
            $this->getNullBaseData(),
            $this->getNullFlagAndMetaData(),
        );
    }

    /**
     * Get data.
     *
     * @param  BillOfMaterialItem $billOfMaterialItem
     *
     * @return array
     */
    private function getPartData(
        BillOfMaterialItem $billOfMaterialItem
    ): array {
        return array_merge(
            $this->getBaseData($billOfMaterialItem),
            $this->getFlagAndMetaData($billOfMaterialItem),
        );
    }

    /**
     * Get null base data.
     *
     * @return array
     */
    private function getNullBaseData(): array
    {
        return [
            'id' => null,
            'bill_of_material_id' => null,
            'product_id' => null,
            'part_id' => null,
            'quantity' => null,
            'sequence' => null,
            'notes' => null,
        ];
    }

    /**
     * Get base data.
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
            'bill_of_material_id' => $billOfMaterialItem->bill_of_material_id,
            'product_id' => $billOfMaterialItem->product_id,
            'part_id' => $billOfMaterialItem->part_id,
            'quantity' => $billOfMaterialItem->quantity,
            'sequence' => $billOfMaterialItem->sequence,
            'notes' => $billOfMaterialItem->notes,
        ];
    }

    /**
     * Get null flag and meta data.
     *
     * @return array
     */
    private function getNullFlagAndMetaData(): array
    {
        return [
            'is_optional' => null,
            'meta' => null,
        ];
    }

    /**
     * Get flag data.
     *
     * @param  BillOfMaterialItem $billOfMaterialItem
     *
     * @return array
     */
    private function getFlagAndMetaData(
        BillOfMaterialItem $billOfMaterialItem
    ): array {
        return [
            'is_optional' => $billOfMaterialItem->is_optional,
            'meta' => $billOfMaterialItem->meta,
        ];
    }
}
