<?php

namespace App\Services\Pipelines;

class PipelineDataPreparationService
{
    /**
     * Prepare pipeline data for creation.
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
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'entity_type' => $data['entity_type'],
            'is_default' => $data['is_default'] ?? false,
            'is_active' => $data['is_active'] ?? true,
            'position' => $data['position'] ?? 0,
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
            'name' => $data['name'] ?? null,
            'description' => $data['description'] ?? null,
            'entity_type' => $data['entity_type'] ?? null,
            'is_default' => $data['is_default'] ?? null,
            'is_active' => $data['is_active'] ?? null,
            'position' => $data['position'] ?? null,
            'meta' => $data['meta'] ?? null,
            'updated_by' => $updatedBy,
        ], fn ($value) => $value !== null);
    }
}
