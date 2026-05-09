<?php

namespace App\Services\CompanyPhones;

use App\Models\CompanyPhone;
use App\Models\Log;
use App\Models\User;

class CompanyPhoneLogService
{
    /**
     * Log company phone creation.
     *
     * @param  CompanyPhone $companyPhone The companyPhone that was created.
     * @param  User $actor The user who performed the action.
     * @param  int $actorId The ID of the user who performed the action.
     *
     * @return array
     */
    public function logCreation(
        CompanyPhone $companyPhone,
        User $actor,
        int $actorId
    ): array {
        $data = $this->basePhoneData($companyPhone) + [
            'created_at' => now(),
            'created_by' => $actor?->name,
        ];

        Log::log(
            Log::ACTION_CREATE_COMPANY_PHONE,
            $data,
            $actorId,
        );

        return $data;
    }

    /**
     * Log a company phone show event.
     *
     * @param  CompanyPhone $companyPhone The companyPhone that was shown.
     * @param  User $actor The user who performed the action.
     * @param  int $actorId The ID of the user who performed the action.
     *
     * @return array The structured data written to the log entry.
     */
    public function logShow(
        CompanyPhone $companyPhone,
        User $actor,
        int $actorId
    ): array {
        $data = $this->basePhoneData($companyPhone) + [
            'shown_at' => now(),
            'shown_by' => $actor?->name,
        ];

        Log::log(
            Log::ACTION_SHOW_COMPANY_PHONE,
            $data,
            $actorId,
        );

        return $data;
    }
    /**
     * Log a company phone update event.
     *
     * @param  CompanyPhone $companyPhone The companyPhone that was updated.
     * @param  User $actor The user who performed the action.
     * @param  int $actorId The ID of the user who performed the action.
     *
     * @return array The structured data written to the log entry.
     */
    public function logUpdate(
        CompanyPhone $companyPhone,
        User $actor,
        int $actorId
    ): array {
        $data = $this->basePhoneData($companyPhone) + [
            'updated_at' => now(),
            'updated_by' => $actor?->name,
        ];

        Log::log(
            Log::ACTION_UPDATE_COMPANY_PHONE,
            $data,
            $actorId,
        );

        return $data;
    }

    /**
     * Log a company phone deletion event.
     *
     * @param  CompanyPhone $companyPhone The companyPhone that was deleted.
     * @param  User $actor The user who performed the action.
     * @param  int $actorId The ID of the user who performed the action.
     *
     * @return array The structured data written to the log entry.
     */
    public function logDeletion(
        CompanyPhone $companyPhone,
        User $actor,
        int $actorId
    ): array {
        $data = $this->basePhoneData($companyPhone) + [
            'deleted_at' => now(),
            'deleted_by' => $actor?->name,
        ];

        Log::log(
            Log::ACTION_DELETE_COMPANY_PHONE,
            $data,
            $actorId,
        );

        return $data;
    }

    /**
     * Log company phone force deletion (permanent).
     *
     * @param  CompanyPhone $companyPhone The companyPhone that was
     * force deleted.
     * @param  User $actor The user who performed the action.
     * @param  int $actorId The ID of the user who performed the action.
     *
     * @return array The structured data written to the log entry.
     */
    public function logForceDeletion(
        CompanyPhone $companyPhone,
        User $actor,
        int $actorId
    ): array {
        $data = $this->basePhoneData($companyPhone) + [
            'force_deleted_at' => now(),
            'force_deleted_by' => $actor?->name,
        ];

        Log::log(
            Log::ACTION_FORCE_DELETE_COMPANY_PHONE,
            $data,
            $actorId,
        );

        return $data;
    }

    /**
     * Log a company phone restoration event.
     *
     * @param  CompanyPhone $companyPhone The companyPhone that was restored.
     * @param  User $actor The user who performed the action.
     * @param  int $actorId The ID of the user who performed the action.
     * or null for system-initiated restoration.
     *
     * @return array The structured data written to the log entry.
     */
    public function logRestoration(
        CompanyPhone $companyPhone,
        User $actor,
        int $actorId
    ): array {
        $data = $this->basePhoneData($companyPhone) + [
            'restored_at' => now(),
            'restored_by' => $actor?->name,
        ];

        Log::log(
            Log::ACTION_RESTORE_COMPANY_PHONE,
            $data,
            $actorId,
        );

        return $data;
    }

    /**
     * Log a company phone import event.
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
            Log::ACTION_IMPORT_COMPANY_PHONE,
            $data,
            $actorId,
        );

        return $data;
    }

    /**
     * Log a company phone companyPhone export event.
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
            Log::ACTION_EXPORT_COMPANY_PHONE,
            $data,
            $actorId,
        );

        return $data;
    }

    /**
     * Log a company phone companyPhone update event performed by a scheduled
     * task (cron).
     *
     * @param  CompanyPhone $companyPhone The companyPhone that was updated.
     *
     * @return array The structured data written to the log entry.
     */
    public function logUpdateByCron(
        CompanyPhone $companyPhone,
    ): array {
        $data = $this->basePhoneData($companyPhone) + [
            'updated_at' => now(),
            'updated_by' => 'System (Cron)',
        ];

        Log::log(
            Log::ACTION_COMPANY_PHONE_UPDATED_BY_CRON,
            $data,
            null,
        );

        return $data;
    }

    /**
     * Get base company phone data for logging.
     *
     * @param  CompanyPhone $companyPhone
     *
     * @return array
     */
    protected function basePhoneData(CompanyPhone $companyPhone): array
    {
        if (! $companyPhone) {
            return $this->getNullData();
        }

        return $this->getCompanyData($companyPhone);
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
            'company_id' => null,
            'type' => null,
            'number' => null,
            'is_primary' => null,
            'meta' => null,
        ];
    }

    /**
     * Get companyPhone data
     *
     * @param  CompanyPhone $companyPhone
     *
     * @return array
     */
    private function getCompanyData(CompanyPhone $companyPhone): array
    {
        return [
            'id' => $companyPhone->id,
            'company_id' => $companyPhone->company_id,
            'type' => $companyPhone->type,
            'number' => $companyPhone->number,
            'is_primary' => $companyPhone->is_primary,
            'meta' => $companyPhone->meta,
        ];
    }
}
