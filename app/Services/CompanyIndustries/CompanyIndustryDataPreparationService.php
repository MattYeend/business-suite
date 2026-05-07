<?php

namespace App\Services\CompanyIndustries;

class CompanyIndustryDataPreparationService
{
    /**
     * Prepare company industry data for creation.
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
            'slug' => $data['slug'],
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
            'slug' => $data['slug'] ?? null,
            'updated_by' => $updatedBy,
        ], fn ($value) => $value !== null);
    }
}
