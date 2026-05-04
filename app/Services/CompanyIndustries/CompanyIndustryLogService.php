<?php

namespace App\Services\CompanyIndustries;

use App\Models\CompanyIndustry;
use App\Models\Log;
use App\Models\User;

class CompanyIndustryLogService
{
    /**
     * Log company industry creation.
     *
     * @param  CompanyIndustry $industry The industry that was created.
     * @param  User $actor The user who performed the action.
     * @param  int $actorId The ID of the user who performed the action.
     *
     * @return array
     */
    public function logCreation(
        CompanyIndustry $industry,
        User $actor,
        int $actorId
    ): array {
        $data = $this->baseIndustryData($industry) + [
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
     * Log a company industry show event.
     *
     * @param  CompanyIndustry $industry The industry that was shown.
     * @param  User $actor The user who performed the action.
     * @param  int $actorId The ID of the user who performed the action.
     *
     * @return array The structured data written to the log entry.
     */
    public function logShow(
        CompanyIndustry $industry,
        User $actor,
        int $actorId
    ): array {
        $data = $this->baseIndustryData($industry) + [
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
     * Log a company industry update event.
     *
     * @param  CompanyIndustry $industry The industry that was updated.
     * @param  User $actor The user who performed the action.
     * @param  int $actorId The ID of the user who performed the action.
     *
     * @return array The structured data written to the log entry.
     */
    public function logUpdate(
        CompanyIndustry $industry,
        User $actor,
        int $actorId
    ): array {
        $data = $this->baseIndustryData($industry) + [
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
     * Log a company industry deletion event.
     *
     * @param  CompanyIndustry $industry The industry that was deleted.
     * @param  User $actor The user who performed the action.
     * @param  int $actorId The ID of the user who performed the action.
     *
     * @return array The structured data written to the log entry.
     */
    public function logDeletion(
        CompanyIndustry $industry,
        User $actor,
        int $actorId
    ): array {
        $data = $this->baseIndustryData($industry) + [
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
     * Log company industry force deletion (permanent).
     *
     * @param  CompanyIndustry $industry The industry that was force deleted.
     * @param  User $actor The user who performed the action.
     * @param  int $actorId The ID of the user who performed the action.
     *
     * @return array The structured data written to the log entry.
     */
    public function logForceDeletion(
        CompanyIndustry $industry,
        User $actor,
        int $actorId
    ): array {
        $data = $this->baseIndustryData($industry) + [
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
     * Log a company industry restoration event.
     *
     * @param  CompanyIndustry $industry The industry that was restored.
     * @param  User $actor The user who performed the action.
     * @param  int $actorId The ID of the user who performed the action.
     * or null for system-initiated restoration.
     *
     * @return array The structured data written to the log entry.
     */
    public function logRestoration(
        CompanyIndustry $industry,
        User $actor,
        int $actorId
    ): array {
        $data = $this->baseIndustryData($industry) + [
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
     * Log a company industry import event.
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
     * Log a company industry export event.
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
     * Log a company industry update event performed by a scheduled task (cron).
     *
     * @param  CompanyIndustry $industry The industry that was updated.
     *
     * @return array The structured data written to the log entry.
     */
    public function logUpdateByCron(
        CompanyIndustry $industry,
    ): array {
        $data = $this->baseIndustryData($industry) + [
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
     * Get base industry data for logging.
     *
     * @param  CompanyIndustry $industry
     *
     * @return array
     */
    protected function baseIndustryData(CompanyIndustry $industry): array
    {
        if (! $industry) {
            return [
                'id' => null,
                'name' => null,
                'slug' => null,
                'meta' => null,
            ];
        }

        return [
            'id' => $industry->id,
            'name' => $industry->name,
            'slug' => $industry->slug,
            'meta' => $industry->meta,
        ];
    }
}
