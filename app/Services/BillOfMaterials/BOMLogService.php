<?php

namespace App\Services\BillOfMaterials;

use App\Models\BillOfMaterial;
use App\Models\Log;
use App\Models\User;

class BOMLogService
{
    /**
     * Log creation.
     *
     * @param  BillOfMaterial $billOfMaterial
     * @param  User $actor
     * @param  int $actorId
     *
     * @return array
     */
    public function logCreation(
        BillOfMaterial $billOfMaterial,
        User $actor,
        int $actorId
    ): array {
        $data = $this->basePartData($billOfMaterial) + [
            'created_at' => now(),
            'created_by' => $actor?->name,
        ];

        Log::log(
            Log::ACTION_CREATE_BILL_OF_MATERIAL,
            $data,
            $actorId,
        );

        return $data;
    }

    /**
     * Log a show event.
     *
     * @param  BillOfMaterial $billOfMaterial
     * @param  User $actor
     * @param  int $actorId
     *
     * @return array
     */
    public function logShow(
        BillOfMaterial $billOfMaterial,
        User $actor,
        int $actorId
    ): array {
        $data = $this->basePartData($billOfMaterial) + [
            'shown_at' => now(),
            'shown_by' => $actor?->name,
        ];

        Log::log(
            Log::ACTION_SHOW_BILL_OF_MATERIAL,
            $data,
            $actorId,
        );

        return $data;
    }

    /**
     * Log a update event.
     *
     * @param  BillOfMaterial $billOfMaterial
     * @param  User $actor
     * @param  int $actorId
     *
     * @return array
     */
    public function logUpdate(
        BillOfMaterial $billOfMaterial,
        User $actor,
        int $actorId
    ): array {
        $data = $this->basePartData($billOfMaterial) + [
            'updated_at' => now(),
            'updated_by' => $actor?->name,
        ];

        Log::log(
            Log::ACTION_UPDATE_BILL_OF_MATERIAL,
            $data,
            $actorId,
        );

        return $data;
    }

    /**
     * Log a deletion event.
     *
     * @param  BillOfMaterial $billOfMaterial
     * @param  User $actor
     * @param  int $actorId
     *
     * @return array
     */
    public function logDeletion(
        BillOfMaterial $billOfMaterial,
        User $actor,
        int $actorId
    ): array {
        $data = $this->basePartData($billOfMaterial) + [
            'deleted_at' => now(),
            'deleted_by' => $actor?->name,
        ];

        Log::log(
            Log::ACTION_DELETE_BILL_OF_MATERIAL,
            $data,
            $actorId,
        );

        return $data;
    }

    /**
     * Log force deletion.
     *
     * @param  BillOfMaterial $billOfMaterial
     * @param  User $actor
     * @param  int $actorId
     *
     * @return array
     */
    public function logForceDeletion(
        BillOfMaterial $billOfMaterial,
        User $actor,
        int $actorId
    ): array {
        $data = $this->basePartData($billOfMaterial) + [
            'force_deleted_at' => now(),
            'force_deleted_by' => $actor?->name,
        ];

        Log::log(
            Log::ACTION_FORCE_DELETE_BILL_OF_MATERIAL,
            $data,
            $actorId,
        );

        return $data;
    }

    /**
     * Log a restoration event.
     *
     * @param  BillOfMaterial $billOfMaterial
     * @param  User $actor
     * @param  int $actorId
     *
     * @return array
     */
    public function logRestoration(
        BillOfMaterial $billOfMaterial,
        User $actor,
        int $actorId
    ): array {
        $data = $this->basePartData($billOfMaterial) + [
            'restored_at' => now(),
            'restored_by' => $actor?->name,
        ];

        Log::log(
            Log::ACTION_RESTORE_BILL_OF_MATERIAL,
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
            Log::ACTION_IMPORT_BILL_OF_MATERIAL,
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
            Log::ACTION_EXPORT_BILL_OF_MATERIAL,
            $data,
            $actorId,
        );

        return $data;
    }

    /**
     * Log a update event performed by cron.
     *
     * @param  BillOfMaterial $billOfMaterial
     *
     * @return array
     */
    public function logUpdateByCron(
        BillOfMaterial $billOfMaterial,
    ): array {
        $data = $this->basePartData($billOfMaterial) + [
            'updated_at' => now(),
            'updated_by' => 'System (Cron)',
        ];

        Log::log(
            Log::ACTION_BILL_OF_MATERIAL_UPDATED_BY_CRON,
            $data,
            null,
        );

        return $data;
    }

    /**
     * Get base data for logging.
     *
     * @param  BillOfMaterial|null $billOfMaterial
     *
     * @return array
     */
    protected function basePartData(?BillOfMaterial $billOfMaterial): array
    {
        if (! $billOfMaterial) {
            return $this->getNullData();
        }

        return $this->getPartData($billOfMaterial);
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
     * @param  BillOfMaterial $billOfMaterial
     *
     * @return array
     */
    private function getPartData(BillOfMaterial $billOfMaterial): array
    {
        return array_merge(
            $this->getBaseData($billOfMaterial),
            $this->getFlagAndMetaData($billOfMaterial),
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
            'product_id' => null,
            'bom_number' => null,
            'version' => null,
            'description' => null,
        ];
    }

    /**
     * Get base data.
     *
     * @param  BillOfMaterial $billOfMaterial
     *
     * @return array
     */
    private function getBaseData(BillOfMaterial $billOfMaterial): array
    {
        return [
            'id' => $billOfMaterial->id,
            'product_id' => $billOfMaterial->product_id,
            'bom_number' => $billOfMaterial->bom_number,
            'version' => $billOfMaterial->version,
            'description' => $billOfMaterial->description,
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
            'is_active' => null,
            'effective_from' => null,
            'effective_to' => null,
            'meta' => null,
        ];
    }

    /**
     * Get flag data.
     *
     * @param  BillOfMaterial $billOfMaterial
     *
     * @return array
     */
    private function getFlagAndMetaData(BillOfMaterial $billOfMaterial): array
    {
        return [
            'is_active' => $billOfMaterial->is_active,
            'effective_from' => $billOfMaterial->effective_from,
            'effective_to' => $billOfMaterial->effective_to,
            'meta' => $billOfMaterial->meta,
        ];
    }
}
