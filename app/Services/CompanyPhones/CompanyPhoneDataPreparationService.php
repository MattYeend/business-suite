<?php

namespace App\Services\CompanyPhones;

class CompanyPhoneDataPreparationService
{
    /**
     * Prepare company phone data for creation.
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
            'company_id' => $data['company_id'],
            'type' => $data['type'],
            'number' => $data['number'],
            'is_primary' => $data['is_primary'],
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
            'company_id' => $data['company_id'] ?? null,
            'type' => $data['type'] ?? null,
            'number' => $data['number'] ?? null,
            'is_primary' => $data['is_primary'] ?? null,
            'updated_by' => $updatedBy,
        ], fn ($value) => $value !== null);
    }
}
