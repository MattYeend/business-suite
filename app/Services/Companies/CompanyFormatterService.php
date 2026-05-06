<?php

namespace App\Services\Companies;

use App\Models\Company;

class CompanyFormatterService
{
    /**
     * Format a single company with all data.
     *
     * @param  Company $company
     *
     * @return array
     */
    public function format(Company $company): array
    {
        return array_merge(
            $this->getBaseData($company),
            $this->getContactInformation($company),
            $this->getAddressInformation($company),
            $this->getEmployeeAndRevenueInformation($company),
            $this->getMetaInformation($company),
            $this->getDateData($company),
        );
    }

    /**
     * Get the company base data
     *
     * @param  Company $company
     *
     * @return array
     */
    private function getBaseData(Company $company): array
    {
        return [
            'id' => $company->id,
            'name' => $company->name,
            'industry_id' => $company->industry_id,
        ];
    }

    /**
     * Get the company's contact information
     *
     * @param  Company $company
     *
     * @return array
     */
    private function getContactInformation(Company $company): array
    {
        return [
            'email' => $company->email,
            'website' => $company->website,
            'phone' => $company->phone,
        ];
    }

    /**
     * Get the company's address
     *
     * @param  Company $company
     *
     * @return array
     */
    private function getAddressInformation(Company $company): array
    {
        return [
            'address' => $company->address,
            'city' => $company->city,
            'region' => $company->region,
            'postal_code' => $company->postal_code,
            'country' => $company->country,
        ];
    }

    /**
     * Get the employee and revenue information
     *
     * @param  Company $company
     *
     * @return array
     */
    private function getEmployeeAndRevenueInformation(Company $company): array
    {
        return [
            'employee_count' => $company->employee_count,
            'annual_revenue' => $company->annual_revenue,
        ];
    }

    /**
     * Get the company's meta information
     *
     * @param  Company $company
     *
     * @return array
     */
    private function getMetaInformation(Company $company): array
    {
        return [
            'meta' => $company->meta,
        ];
    }

    /**
     * Get the company's date data.
     *
     * @param  Company $company
     *
     * @return array
     */
    private function getDateData(Company $company): array
    {
        return [
            'created_at' => $company->created_at,
            'updated_at' => $company->updated_at,
            'deleted_at' => $company->deleted_at,
            'restored_at' => $company->restored_at,
            'created_by' => $company->created_by,
            'updated_by' => $company->updated_by,
            'deleted_by' => $company->deleted_by,
            'restored_by' => $company->restored_by,
        ];
    }
}
