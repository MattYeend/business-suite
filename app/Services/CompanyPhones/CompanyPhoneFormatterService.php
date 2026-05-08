<?php

namespace App\Services\CompanyPhones;

use App\Models\CompanyPhone;

class CompanyPhoneFormatterService
{
    /**
     * Format a single company phone with all data.
     *
     * @param  CompanyPhone $companyPhone
     *
     * @return array
     */
    public function format(CompanyPhone $companyPhone): array
    {
        return array_merge(
            $this->getBaseData($companyPhone),
            $this->getTypeAndPrimary($companyPhone),
            $this->getMetaInformation($companyPhone),
            $this->getDateData($companyPhone),
        );
    }

    /**
     * Get the company phone base data
     *
     * @param  CompanyPhone $companyPhone
     *
     * @return array
     */
    private function getBaseData(CompanyPhone $companyPhone): array
    {
        return [
            'id' => $companyPhone->id,
            'company_id' => $companyPhone->company_id,
        ];
    }

    /**
     * Get the company phone's contact information
     *
     * @param  CompanyPhone $companyPhone
     *
     * @return array
     */
    private function getTypeAndPrimary(CompanyPhone $companyPhone): array
    {
        return [
            'type' => $companyPhone->type,
            'number' => $companyPhone->number,
            'is_primary' => $companyPhone->is_primary,
        ];
    }

    /**
     * Get the company phone's meta information
     *
     * @param  CompanyPhone $companyPhone
     *
     * @return array
     */
    private function getMetaInformation(CompanyPhone $companyPhone): array
    {
        return [
            'meta' => $companyPhone->meta,
        ];
    }

    /**
     * Get the company phone's date data.
     *
     * @param  CompanyPhone $companyPhone
     *
     * @return array
     */
    private function getDateData(CompanyPhone $companyPhone): array
    {
        return [
            'created_at' => $companyPhone->created_at,
            'updated_at' => $companyPhone->updated_at,
            'deleted_at' => $companyPhone->deleted_at,
            'restored_at' => $companyPhone->restored_at,
            'created_by' => $companyPhone->created_by,
            'updated_by' => $companyPhone->updated_by,
            'deleted_by' => $companyPhone->deleted_by,
            'restored_by' => $companyPhone->restored_by,
        ];
    }
}
