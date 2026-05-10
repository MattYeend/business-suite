<?php

namespace App\Services\PipelineStages;

use App\Models\PipelineStage;

class PipelineStageFormatterService
{
    /**
     * Format a single stage with all data.
     *
     * @param  PipelineStage $stage
     *
     * @return array
     */
    public function format(PipelineStage $stage): array
    {
        return array_merge(
            $this->formatBaseData($stage),
            $this->formatTerminalData($stage),
            $this->formatMetaData($stage),
            $this->formatDateData($stage),
        );
    }

    /**
     * Format the base stage information.
     *
     * Includes identifying and display-related fields.
     *
     * @param  PipelineStage $stage
     *
     * @return array
     */
    private function formatBaseData(PipelineStage $stage): array
    {
        return [
            'id' => $stage->id,
            'pipeline_id' => $stage->pipeline_id,
            'name' => $stage->name,
            'colour' => $stage->colour,
            'position' => $stage->position,
        ];
    }

    /**
     * Format terminal-stage configuration data.
     *
     * Includes whether the stage is terminal and its terminal type.
     *
     * @param  PipelineStage $stage
     *
     * @return array
     */
    private function formatTerminalData(PipelineStage $stage): array
    {
        return [
            'is_terminal' => $stage->is_terminal,
            'terminal_type' => $stage->terminal_type,
        ];
    }

    /**
     * Format metadata and operational stage settings.
     *
     * Includes probability, SLA settings, approval requirements,
     * and custom metadata.
     *
     * @param  PipelineStage $stage
     *
     * @return array
     */
    private function formatMetaData(PipelineStage $stage): array
    {
        return [
            'probability' => $stage->probability,
            'sla_hours' => $stage->sla_hours,
            'requires_approval' => $stage->requires_approval,
            'meta' => $stage->meta,
        ];
    }

    /**
     * Format audit and timestamp-related information.
     *
     * Includes creation, update, deletion, and restoration metadata.
     *
     * @param  PipelineStage $stage
     *
     * @return array
     */
    private function formatDateData(PipelineStage $stage): array
    {
        return [
            'created_at' => $stage->created_at,
            'updated_at' => $stage->updated_at,
            'deleted_at' => $stage->deleted_at,
            'restored_at' => $stage->restored_at,
            'created_by' => $stage->created_by,
            'updated_by' => $stage->updated_by,
            'deleted_by' => $stage->deleted_by,
            'restored_by' => $stage->restored_by,
        ];
    }
}
