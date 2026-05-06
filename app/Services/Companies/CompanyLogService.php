<?php

namespace App\Services\Companies;

use App\Models\Company;
use App\Models\Log;
use App\Models\User;

class CompanyLogService
{
    /**
     * Log company company creation.
     *
     * @param  Company $company The company that was created.
     * @param  User $actor The user who performed the action.
     * @param  int $actorId The ID of the user who performed the action.
     *
     * @return array
     */
    public function logCreation(
        Company $company,
        User $actor,
        int $actorId
    ): array {
        $data = $this->baseIndustryData($company) + [
            'created_at' => now(),
            'created_by' => $actor?->name,
        ];

        Log::log(
            Log::ACTION_CREATE_COMPANY_INDUSTRY,
            $data,
            $actorId,
        );

        return $data;
    }

    /**
     * Log a company company show event.
     *
     * @param  Company $company The company that was shown.
     * @param  User $actor The user who performed the action.
     * @param  int $actorId The ID of the user who performed the action.
     *
     * @return array The structured data written to the log entry.
     */
    public function logShow(
        Company $company,
        User $actor,
        int $actorId
    ): array {
        $data = $this->baseIndustryData($company) + [
            'shown_at' => now(),
            'shown_by' => $actor?->name,
        ];

        Log::log(
            Log::ACTION_SHOW_COMPANY_INDUSTRY,
            $data,
            $actorId,
        );

        return $data;
    }
    /**
     * Log a company company update event.
     *
     * @param  Company $company The company that was updated.
     * @param  User $actor The user who performed the action.
     * @param  int $actorId The ID of the user who performed the action.
     *
     * @return array The structured data written to the log entry.
     */
    public function logUpdate(
        Company $company,
        User $actor,
        int $actorId
    ): array {
        $data = $this->baseIndustryData($company) + [
            'updated_at' => now(),
            'updated_by' => $actor?->name,
        ];

        Log::log(
            Log::ACTION_UPDATE_COMPANY_INDUSTRY,
            $data,
            $actorId,
        );

        return $data;
    }

    /**
     * Log a company company deletion event.
     *
     * @param  Company $company The company that was deleted.
     * @param  User $actor The user who performed the action.
     * @param  int $actorId The ID of the user who performed the action.
     *
     * @return array The structured data written to the log entry.
     */
    public function logDeletion(
        Company $company,
        User $actor,
        int $actorId
    ): array {
        $data = $this->baseIndustryData($company) + [
            'deleted_at' => now(),
            'deleted_by' => $actor?->name,
        ];

        Log::log(
            Log::ACTION_DELETE_COMPANY_INDUSTRY,
            $data,
            $actorId,
        );

        return $data;
    }

    /**
     * Log company company force deletion (permanent).
     *
     * @param  Company $company The company that was force deleted.
     * @param  User $actor The user who performed the action.
     * @param  int $actorId The ID of the user who performed the action.
     *
     * @return array The structured data written to the log entry.
     */
    public function logForceDeletion(
        Company $company,
        User $actor,
        int $actorId
    ): array {
        $data = $this->baseIndustryData($company) + [
            'force_deleted_at' => now(),
            'force_deleted_by' => $actor?->name,
        ];

        Log::log(
            Log::ACTION_FORCE_DELETE_COMPANY_INDUSTRY,
            $data,
            $actorId,
        );

        return $data;
    }

    /**
     * Log a company company restoration event.
     *
     * @param  Company $company The company that was restored.
     * @param  User $actor The user who performed the action.
     * @param  int $actorId The ID of the user who performed the action.
     * or null for system-initiated restoration.
     *
     * @return array The structured data written to the log entry.
     */
    public function logRestoration(
        Company $company,
        User $actor,
        int $actorId
    ): array {
        $data = $this->baseIndustryData($company) + [
            'restored_at' => now(),
            'restored_by' => $actor?->name,
        ];

        Log::log(
            Log::ACTION_RESTORE_COMPANY_INDUSTRY,
            $data,
            $actorId,
        );

        return $data;
    }

    /**
     * Log a company company import event.
     *
     * @param  array $importData The data that was imported.
     * @param  User $actor The user who performed the action.
     * @param  int $actorId The ID of the user who performed the action.
     *
     * @return array The structured data written to the log entry.
     */
    public function logImport(
        array $importData,
        User $actor,
        int $actorId
    ): array {
        $data = [
            'imported_at' => now(),
            'imported_by' => $actor?->name,
            'imported_count' => count($importData),
            'imported_data_sample' => array_slice($importData, 0, 5),
        ];

        Log::log(
            Log::ACTION_IMPORT_COMPANY_INDUSTRY,
            $data,
            $actorId,
        );

        return $data;
    }

    /**
     * Log a company company export event.
     *
     * @param  array $exportData The data that was exported.
     * @param  User $actor The user who performed the action.
     * @param  int $actorId The ID of the user who performed the action.
     *
     * @return array The structured data written to the log entry.
     */
    public function logExport(
        array $exportData,
        User $actor,
        int $actorId
    ): array {
        $data = [
            'exported_at' => now(),
            'exported_by' => $actor?->name,
            'exported_count' => count($exportData),
            'exported_data_sample' => array_slice($exportData, 0, 5),
        ];

        Log::log(
            Log::ACTION_EXPORT_COMPANY_INDUSTRY,
            $data,
            $actorId,
        );

        return $data;
    }

    /**
     * Log a company company update event performed by a scheduled task (cron).
     *
     * @param  Company $company The company that was updated.
     *
     * @return array The structured data written to the log entry.
     */
    public function logUpdateByCron(
        Company $company,
    ): array {
        $data = $this->baseIndustryData($company) + [
            'updated_at' => now(),
            'updated_by' => 'System (Cron)',
        ];

        Log::log(
            Log::ACTION_COMPANY_INDUSTRY_UPDATED_BY_CRON,
            $data,
            null,
        );

        return $data;
    }

    /**
     * Get base company data for logging.
     *
     * @param  Company $company
     *
     * @return array
     */
    protected function baseIndustryData(Company $company): array
    {
        if (! $company) {
            return $this->getNullData();
        }

        return $this->getCompanyData($company);
    }

    /**
     * Get null data
     *
     * @return array
     */
    private function getNullData(): array
    {
        return [
            'id' => null,
            'name' => null,
            'industry_id' => null,
            'email' => null,
            'website' => null,
            'phone' => null,
            'address' => null,
            'city' => null,
            'region' => null,
            'postal_code' => null,
            'country' => null,
            'employee_count' => null,
            'annual_revenue' => null,
            'meta' => null,
        ];
    }

    /**
     * Get company data
     *
     * @param  Company $company
     *
     * @return array
     */
    private function getCompanyData(Company $company): array
    {
        return [
            'id' => $company->id,
            'name' => $company->name,
            'industry_id' => $company->industry_id,
            'email' => $company->email,
            'website' => $company->website,
            'phone' => $company->phone,
            'address' => $company->address,
            'city' => $company->city,
            'region' => $company->region,
            'postal_code' => $company->postal_code,
            'country' => $company->country,
            'employee_count' => $company->employee_count,
            'annual_revenue' => $company->annual_revenue,
            'meta' => $company->meta,
        ];
    }
}
