<?php

namespace App\Services\Pipelines;

use App\Models\Pipeline;

class PipelineFormatterService
{
    /**
     * Format a single pipeline with all data.
     *
     * @param  Pipeline $pipeline
     *
     * @return array
     */
    public function format(Pipeline $pipeline): array
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
            'created_at' => $pipeline->created_at,
            'updated_at' => $pipeline->updated_at,
            'deleted_at' => $pipeline->deleted_at,
            'restored_at' => $pipeline->restored_at,
            'created_by' => $pipeline->created_by,
            'updated_by' => $pipeline->updated_by,
            'deleted_by' => $pipeline->deleted_by,
            'restored_by' => $pipeline->restored_by,
        ];
    }
}
