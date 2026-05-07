<?php

namespace App\Services\CompanyContacts;

class CompanyContactDataPreparationService
{
    /**
     * Prepare company contact data for creation.
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
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'] ?? null,
            'phone' => $data['phone'] ?? null,
            'mobile' => $data['mobile'] ?? null,
            'job_title' => $data['job_title'] ?? null,
            'is_primary' => $data['is_primary'] ?? null,
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
            'first_name' => $data['first_name'] ?? null,
            'last_name' => $data['last_name'] ?? null,
            'email' => $data['email'] ?? null,
            'phone' => $data['phone'] ?? null,
            'mobile' => $data['mobile'] ?? null,
            'job_title' => $data['job_title'] ?? null,
            'is_primary' => $data['is_primary'] ?? null,
            'updated_by' => $updatedBy,
        ], fn ($value) => $value !== null);
    }
}
