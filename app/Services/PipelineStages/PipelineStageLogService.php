<?php

namespace App\Services\PipelineStages;

use App\Models\Log;
use App\Models\PipelineStage;
use App\Models\User;
class PipelineStageLogService
{
    /**
     * Log pipeline stage creation.
     *
     * @param  PipelineStage $stage The stage that was created.
     * @param  User $actor The user who performed the action.
     * @param  int $actorId The ID of the user who performed the action.
     *
     * @return array
     */
    public function logCreation(
        PipelineStage $stage,
        User $actor,
        int $actorId
    ): array {
        $data = $this->baseStageData($stage) + [
            'created_at' => now(),
            'created_by' => $actor?->name,
        ];

        Log::log(
            Log::ACTION_CREATE_PIPELINE_STAGE,
            $data,
            $actorId,
        );

        return $data;
    }

    /**
     * Log a pipeline stage show event.
     *
     * @param  PipelineStage $stage The stage that was shown.
     * @param  User $actor The user who performed the action.
     * @param  int $actorId The ID of the user who performed the action.
     *
     * @return array The structured data written to the log entry.
     */
    public function logShow(
        PipelineStage $stage,
        User $actor,
        int $actorId
    ): array {
        $data = $this->baseStageData($stage) + [
            'shown_at' => now(),
            'shown_by' => $actor?->name,
        ];

        Log::log(
            Log::ACTION_SHOW_PIPELINE_STAGE,
            $data,
            $actorId,
        );

        return $data;
    }
    /**
     * Log a pipeline stage update event.
     *
     * @param  PipelineStage $stage The stage that was updated.
     * @param  User $actor The user who performed the action.
     * @param  int $actorId The ID of the user who performed the action.
     *
     * @return array The structured data written to the log entry.
     */
    public function logUpdate(
        PipelineStage $stage,
        User $actor,
        int $actorId
    ): array {
        $data = $this->baseStageData($stage) + [
            'updated_at' => now(),
            'updated_by' => $actor?->name,
        ];

        Log::log(
            Log::ACTION_UPDATE_PIPELINE_STAGE,
            $data,
            $actorId,
        );

        return $data;
    }

    /**
     * Log a pipeline stage deletion event.
     *
     * @param  PipelineStage $stage The stage that was deleted.
     * @param  User $actor The user who performed the action.
     * @param  int $actorId The ID of the user who performed the action.
     *
     * @return array The structured data written to the log entry.
     */
    public function logDeletion(
        PipelineStage $stage,
        User $actor,
        int $actorId
    ): array {
        $data = $this->baseStageData($stage) + [
            'deleted_at' => now(),
            'deleted_by' => $actor?->name,
        ];

        Log::log(
            Log::ACTION_DELETE_PIPELINE_STAGE,
            $data,
            $actorId,
        );

        return $data;
    }

    /**
     * Log pipeline stage force deletion (permanent).
     *
     * @param  PipelineStage $stage The stage that was force deleted.
     * @param  User $actor The user who performed the action.
     * @param  int $actorId The ID of the user who performed the action.
     *
     * @return array The structured data written to the log entry.
     */
    public function logForceDeletion(
        PipelineStage $stage,
        User $actor,
        int $actorId
    ): array {
        $data = $this->baseStageData($stage) + [
            'force_deleted_at' => now(),
            'force_deleted_by' => $actor?->name,
        ];

        Log::log(
            Log::ACTION_FORCE_DELETE_PIPELINE_STAGE,
            $data,
            $actorId,
        );

        return $data;
    }

    /**
     * Log a pipeline stage restoration event.
     *
     * @param  PipelineStage $stage The stage that was restored.
     * @param  User $actor The user who performed the action.
     * @param  int $actorId The ID of the user who performed the action.
     * or null for system-initiated restoration.
     *
     * @return array The structured data written to the log entry.
     */
    public function logRestoration(
        PipelineStage $stage,
        User $actor,
        int $actorId
    ): array {
        $data = $this->baseStageData($stage) + [
            'restored_at' => now(),
            'restored_by' => $actor?->name,
        ];

        Log::log(
            Log::ACTION_RESTORE_PIPELINE_STAGE,
            $data,
            $actorId,
        );

        return $data;
    }

    /**
     * Log a pipeline stage import event.
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
            Log::ACTION_IMPORT_PIPELINE_STAGE,
            $data,
            $actorId,
        );

        return $data;
    }

    /**
     * Log a pipeline stage export event.
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
            Log::ACTION_EXPORT_PIPELINE_STAGE,
            $data,
            $actorId,
        );

        return $data;
    }

    /**
     * Log a pipeline stage update event performed by a scheduled task (cron).
     *
     * @param  PipelineStage $stage The stage that was updated.
     *
     * @return array The structured data written to the log entry.
     */
    public function logUpdateByCron(
        PipelineStage $stage,
    ): array {
        $data = $this->baseStageData($stage) + [
            'updated_at' => now(),
            'updated_by' => 'System (Cron)',
        ];

        Log::log(
            Log::ACTION_PIPELINE_STAGE_UPDATED_BY_CRON,
            $data,
            null,
        );

        return $data;
    }

    /**
     * Get base stage data for logging.
     *
     * @param  PipelineStage $stage
     *
     * @return array
     */
    protected function baseStageData(PipelineStage $stage): array
    {
        if (! $stage) {
            return $this->getNullData();
        }

        return $this->getStageData($stage);
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
            'pipeline_id' => null,
            'name' => null,
            'colour' => null,
            'position' => null,
            'is_terminal' => null,
            'terminal_type' => null,
            'probability' => null,
            'sla_hours' => null,
            'requires_approval' => null,
            'meta' => null,
        ];
    }

    /**
     * Get stage data
     *
     * @param  PipelineStage $stage
     *
     * @return array
     */
    private function getStageData(PipelineStage $stage): array
    {
        return [
            'id' => $stage->id,
            'pipeline_id' => $stage->pipeline_id,
            'name' => $stage->name,
            'colour' => $stage->colour,
            'position' => $stage->position,
            'is_terminal' => $stage->is_terminal,
            'terminal_type' => $stage->terminal_type,
            'probability' => $stage->probability,
            'sla_hours' => $stage->sla_hours,
            'requires_approval' => $stage->requires_approval,
            'meta' => $stage->meta,
        ];
    }
}
