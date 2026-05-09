<?php

namespace App\Services\CompanyAddresses;

class CompanyAddressDataPreparationService
{
    /**
     * Prepare company address data for creation.
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
            'address_line_1' => $data['address_line_1'],
            'address_line_2' => $data['address_line_2'] ?? null,
            'city' => $data['city'],
            'county' => $data['county'] ?? null,
            'postal_code' => $data['postal_code'],
            'country' => $data['country'],
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
            'type' => $data['type'] ?? null,
            'address_line_1' => $data['address_line_1'] ?? null,
            'address_line_2' => $data['address_line_2'] ?? null,
            'city' => $data['city'] ?? null,
            'county' => $data['county'] ?? null,
            'postal_code' => $data['postal_code'] ?? null,
            'country' => $data['country'] ?? null,
            'is_primary' => $data['is_primary'] ?? null,
            'updated_by' => $updatedBy,
        ], fn ($value) => $value !== null);
    }
}
