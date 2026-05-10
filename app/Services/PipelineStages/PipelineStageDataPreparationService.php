<?php

namespace App\Services\PipelineStages;

class PipelineStageDataPreparationService
{
    /**
     * Prepare pipeline stage data for creation.
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
            'pipeline_id' => $data['pipeline_id'],
            'name' => $data['name'],
            'colour' => $data['colour'] ?? null,
            'position' => $data['position'] ?? 0,
            'is_terminal' => $data['is_terminal'] ?? false,
            'terminal_type' => $data['terminal_type'] ?? null,
            'probability' => $data['probability'] ?? null,
            'sla_hours' => $data['sla_hours'] ?? null,
            'requires_approval' => $data['requires_approval'] ?? false,
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
            'pipeline_id' => $data['pipeline_id'] ?? null,
            'name' => $data['name'] ?? null,
            'colour' => $data['colour'] ?? null,
            'position' => $data['position'] ?? null,
            'is_terminal' => $data['is_terminal'] ?? null,
            'terminal_type' => $data['terminal_type'] ?? null,
            'probability' => $data['probability'] ?? null,
            'sla_hours' => $data['sla_hours'] ?? null,
            'requires_approval' => $data['requires_approval'] ?? null,
            'is_real' => $data['is_real'] ?? null,
            'meta' => $data['meta'] ?? null,
            'updated_by' => $updatedBy,
        ], fn ($value) => $value !== null);
    }
}
