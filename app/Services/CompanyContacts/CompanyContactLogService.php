<?php

namespace App\Services\CompanyContacts;

use App\Models\CompanyContact;
use App\Models\Log;
use App\Models\User;

class CompanyContactLogService
{
    /**
     * Log company contact creation.
     *
     * @param  CompanyContact $contact The contact that was created.
     * @param  User $actor The user who performed the action.
     * @param  int $actorId The ID of the user who performed the action.
     *
     * @return array
     */
    public function logCreation(
        CompanyContact $contact,
        User $actor,
        int $actorId
    ): array {
        $data = $this->baseIndustryData($contact) + [
            'created_at' => now(),
            'created_by' => $actor?->name,
        ];

        Log::log(
            Log::ACTION_CREATE_COMPANY_CONTACT,
            $data,
            $actorId,
        );

        return $data;
    }

    /**
     * Log a company contact show event.
     *
     * @param  CompanyContact $contact The contact that was shown.
     * @param  User $actor The user who performed the action.
     * @param  int $actorId The ID of the user who performed the action.
     *
     * @return array The structured data written to the log entry.
     */
    public function logShow(
        CompanyContact $contact,
        User $actor,
        int $actorId
    ): array {
        $data = $this->baseIndustryData($contact) + [
            'shown_at' => now(),
            'shown_by' => $actor?->name,
        ];

        Log::log(
            Log::ACTION_SHOW_COMPANY_CONTACT,
            $data,
            $actorId,
        );

        return $data;
    }
    /**
     * Log a company contact update event.
     *
     * @param  CompanyContact $contact The contact that was updated.
     * @param  User $actor The user who performed the action.
     * @param  int $actorId The ID of the user who performed the action.
     *
     * @return array The structured data written to the log entry.
     */
    public function logUpdate(
        CompanyContact $contact,
        User $actor,
        int $actorId
    ): array {
        $data = $this->baseIndustryData($contact) + [
            'updated_at' => now(),
            'updated_by' => $actor?->name,
        ];

        Log::log(
            Log::ACTION_UPDATE_COMPANY_CONTACT,
            $data,
            $actorId,
        );

        return $data;
    }

    /**
     * Log a company contact deletion event.
     *
     * @param  CompanyContact $contact The contact that was deleted.
     * @param  User $actor The user who performed the action.
     * @param  int $actorId The ID of the user who performed the action.
     *
     * @return array The structured data written to the log entry.
     */
    public function logDeletion(
        CompanyContact $contact,
        User $actor,
        int $actorId
    ): array {
        $data = $this->baseIndustryData($contact) + [
            'deleted_at' => now(),
            'deleted_by' => $actor?->name,
        ];

        Log::log(
            Log::ACTION_DELETE_COMPANY_CONTACT,
            $data,
            $actorId,
        );

        return $data;
    }

    /**
     * Log company contact force deletion (permanent).
     *
     * @param  CompanyContact $contact The contact that was force deleted.
     * @param  User $actor The user who performed the action.
     * @param  int $actorId The ID of the user who performed the action.
     *
     * @return array The structured data written to the log entry.
     */
    public function logForceDeletion(
        CompanyContact $contact,
        User $actor,
        int $actorId
    ): array {
        $data = $this->baseIndustryData($contact) + [
            'force_deleted_at' => now(),
            'force_deleted_by' => $actor?->name,
        ];

        Log::log(
            Log::ACTION_FORCE_DELETE_COMPANY_CONTACT,
            $data,
            $actorId,
        );

        return $data;
    }

    /**
     * Log a company contact restoration event.
     *
     * @param  CompanyContact $contact The contact that was restored.
     * @param  User $actor The user who performed the action.
     * @param  int $actorId The ID of the user who performed the action.
     * or null for system-initiated restoration.
     *
     * @return array The structured data written to the log entry.
     */
    public function logRestoration(
        CompanyContact $contact,
        User $actor,
        int $actorId
    ): array {
        $data = $this->baseIndustryData($contact) + [
            'restored_at' => now(),
            'restored_by' => $actor?->name,
        ];

        Log::log(
            Log::ACTION_RESTORE_COMPANY_CONTACT,
            $data,
            $actorId,
        );

        return $data;
    }

    /**
     * Log a company contact import event.
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
            Log::ACTION_IMPORT_COMPANY_CONTACT,
            $data,
            $actorId,
        );

        return $data;
    }

    /**
     * Log a company contact export event.
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
            Log::ACTION_EXPORT_COMPANY_CONTACT,
            $data,
            $actorId,
        );

        return $data;
    }

    /**
     * Log a company contact update event performed by a scheduled task (cron).
     *
     * @param  CompanyContact $contact The contact that was updated.
     *
     * @return array The structured data written to the log entry.
     */
    public function logUpdateByCron(
        CompanyContact $contact,
    ): array {
        $data = $this->baseIndustryData($contact) + [
            'updated_at' => now(),
            'updated_by' => 'System (Cron)',
        ];

        Log::log(
            Log::ACTION_COMPANY_CONTACT_UPDATED_BY_CRON,
            $data,
            null,
        );

        return $data;
    }

    /**
     * Get base contact data for logging.
     *
     * @param  CompanyContact $contact
     *
     * @return array
     */
    protected function baseIndustryData(CompanyContact $contact): array
    {
        if (! $contact) {
            return [
                'id' => null,
                'first_name' => null,
                'last_name' => null,
                'email' => null,
                'phone' => null,
                'mobile' => null,
                'job_title' => null,
                'company_id' => null,
                'meta' => null,
            ];
        }

        return [
            'id' => $contact->id,
            'first_name' => $contact->first_name,
            'last_name' => $contact->last_name,
            'email' => $contact->email,
            'phone' => $contact->phone,
            'mobile' => $contact->mobile,
            'job_title' => $contact->job_title,
            'company_id' => $contact->company_id,
            'meta' => $contact->meta,
        ];
    }
}
