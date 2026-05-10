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
