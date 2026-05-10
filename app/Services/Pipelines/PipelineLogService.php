<?php

namespace App\Services\Pipelines;

use App\Models\Log;
use App\Models\Pipeline;
use App\Models\User;

class PipelineLogService
{
    /**
     * Log pipeline creation.
     *
     * @param  Pipeline $pipeline The pipeline that was created.
     * @param  User $actor The user who performed the action.
     * @param  int $actorId The ID of the user who performed the action.
     *
     * @return array
     */
    public function logCreation(
        Pipeline $pipeline,
        User $actor,
        int $actorId
    ): array {
        $data = $this->basePipelineData($pipeline) + [
            'created_at' => now(),
            'created_by' => $actor?->name,
        ];

        Log::log(
            Log::ACTION_CREATE_PIPELINE,
            $data,
            $actorId,
        );

        return $data;
    }

    /**
     * Log a pipeline show event.
     *
     * @param  Pipeline $pipeline The pipeline that was shown.
     * @param  User $actor The user who performed the action.
     * @param  int $actorId The ID of the user who performed the action.
     *
     * @return array The structured data written to the log entry.
     */
    public function logShow(
        Pipeline $pipeline,
        User $actor,
        int $actorId
    ): array {
        $data = $this->basePipelineData($pipeline) + [
            'shown_at' => now(),
            'shown_by' => $actor?->name,
        ];

        Log::log(
            Log::ACTION_SHOW_PIPELINE,
            $data,
            $actorId,
        );

        return $data;
    }
    /**
     * Log a pipeline update event.
     *
     * @param  Pipeline $pipeline The pipeline that was updated.
     * @param  User $actor The user who performed the action.
     * @param  int $actorId The ID of the user who performed the action.
     *
     * @return array The structured data written to the log entry.
     */
    public function logUpdate(
        Pipeline $pipeline,
        User $actor,
        int $actorId
    ): array {
        $data = $this->basePipelineData($pipeline) + [
            'updated_at' => now(),
            'updated_by' => $actor?->name,
        ];

        Log::log(
            Log::ACTION_UPDATE_PIPELINE,
            $data,
            $actorId,
        );

        return $data;
    }

    /**
     * Log a pipeline deletion event.
     *
     * @param  Pipeline $pipeline The pipeline that was deleted.
     * @param  User $actor The user who performed the action.
     * @param  int $actorId The ID of the user who performed the action.
     *
     * @return array The structured data written to the log entry.
     */
    public function logDeletion(
        Pipeline $pipeline,
        User $actor,
        int $actorId
    ): array {
        $data = $this->basePipelineData($pipeline) + [
            'deleted_at' => now(),
            'deleted_by' => $actor?->name,
        ];

        Log::log(
            Log::ACTION_DELETE_PIPELINE,
            $data,
            $actorId,
        );

        return $data;
    }

    /**
     * Log pipeline force deletion (permanent).
     *
     * @param  Pipeline $pipeline The pipeline that was force deleted.
     * @param  User $actor The user who performed the action.
     * @param  int $actorId The ID of the user who performed the action.
     *
     * @return array The structured data written to the log entry.
     */
    public function logForceDeletion(
        Pipeline $pipeline,
        User $actor,
        int $actorId
    ): array {
        $data = $this->basePipelineData($pipeline) + [
            'force_deleted_at' => now(),
            'force_deleted_by' => $actor?->name,
        ];

        Log::log(
            Log::ACTION_FORCE_DELETE_PIPELINE,
            $data,
            $actorId,
        );

        return $data;
    }

    /**
     * Log a pipeline restoration event.
     *
     * @param  Pipeline $pipeline The pipeline that was restored.
     * @param  User $actor The user who performed the action.
     * @param  int $actorId The ID of the user who performed the action.
     * or null for system-initiated restoration.
     *
     * @return array The structured data written to the log entry.
     */
    public function logRestoration(
        Pipeline $pipeline,
        User $actor,
        int $actorId
    ): array {
        $data = $this->basePipelineData($pipeline) + [
            'restored_at' => now(),
            'restored_by' => $actor?->name,
        ];

        Log::log(
            Log::ACTION_RESTORE_PIPELINE,
            $data,
            $actorId,
        );

        return $data;
    }

    /**
     * Log a pipeline import event.
     *
     * @param  array $importData The data that was imported.
     * @param  User $actor The user who performed the action.
     * @param  int $actorId The ID of the user who performed the action.
     *
     * @return array The structured data written to the log entry.
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
            Log::ACTION_IMPORT_PIPELINE,
            $data,
            $actorId,
        );

        return $data;
    }

    /**
     * Log a pipeline export event.
     *
     * @param  array $exportData The data that was exported.
     * @param  User $actor The user who performed the action.
     * @param  int $actorId The ID of the user who performed the action.
     *
     * @return array The structured data written to the log entry.
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
            Log::ACTION_EXPORT_PIPELINE,
            $data,
            $actorId,
        );

        return $data;
    }

    /**
     * Log a pipeline update event performed by a scheduled task (cron).
     *
     * @param  Pipeline $pipeline The pipeline that was updated.
     *
     * @return array The structured data written to the log entry.
     */
    public function logUpdateByCron(
        Pipeline $pipeline,
    ): array {
        $data = $this->basePipelineData($pipeline) + [
            'updated_at' => now(),
            'updated_by' => 'System (Cron)',
        ];

        Log::log(
            Log::ACTION_PIPELINE_UPDATED_BY_CRON,
            $data,
            null,
        );

        return $data;
    }

    /**
     * Get base pipeline data for logging.
     *
     * @param  Pipeline $pipeline
     *
     * @return array
     */
    protected function basePipelineData(Pipeline $pipeline): array
    {
        if (! $pipeline) {
            return $this->getNullData();
        }

        return $this->getPipelineData($pipeline);
    }

    /**
     * Get null data
     *
     * @return array
     */
    private function getNullData(): array
    {
        return [
            'id' => null,
            'name' => null,
            'description' => null,
            'entity_type' => null,
            'is_default' => null,
            'is_active' => null,
            'position' => null,
            'meta' => null,
        ];
    }

    /**
     * Get pipeline data
     *
     * @param  Pipeline $pipeline
     *
     * @return array
     */
    private function getPipelineData(Pipeline $pipeline): array
    {
        return [
            'id' => $pipeline->id,
            'name' => $pipeline->name,
            'description' => $pipeline->description,
            'entity_type' => $pipeline->entity_type,
            'is_default' => $pipeline->is_default,
            'is_active' => $pipeline->is_active,
            'position' => $pipeline->position,
            'meta' => $pipeline->meta,
        ];
    }
}
