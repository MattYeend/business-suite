<?php

namespace App\Services\Companies;

class CompanyDataPreparationService
{
    /**
     * Prepare user data for creation.
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
            'industry_id' => $data['industry_id'],
            'email' => $data['email'] ?? null,
            'website' => $data['website'] ?? null,
            'phone' => $data['phone'] ?? null,
            'address' => $data['address'] ?? null,
            'city' => $data['city'] ?? null,
            'region' => $data['region'] ?? null,
            'postal_code' => $data['postal_code'] ?? null,
            'country' => $data['country'] ?? null,
            'employee_count' => $data['employee_count'] ?? null,
            'annual_revenue' => $data['annual_revenue'] ?? null,
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
            'industry_id' => $data['industry_id'] ?? null,
            'email' => $data['email'] ?? null,
            'website' => $data['website'] ?? null,
            'phone' => $data['phone'] ?? null,
            'address' => $data['address'] ?? null,
            'city' => $data['city'] ?? null,
            'region' => $data['region'] ?? null,
            'postal_code' => $data['postal_code'] ?? null,
            'country' => $data['country'] ?? null,
            'employee_count' => $data['employee_count'] ?? null,
            'annual_revenue' => $data['annual_revenue'] ?? null,
            'updated_by' => $updatedBy,
        ], fn ($value) => $value !== null);
    }
}
