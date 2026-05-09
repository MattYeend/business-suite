<?php

namespace App\Services\CompanyAddresses;

use App\Models\CompanyAddress;
use App\Models\Log;
use App\Models\User;

class CompanyAddressLogService
{
    /**
     * Log company address creation.
     *
     * @param  CompanyAddress $address The address that was created.
     * @param  User $actor The user who performed the action.
     * @param  int $actorId The ID of the user who performed the action.
     *
     * @return array
     */
    public function logCreation(
        CompanyAddress $address,
        User $actor,
        int $actorId
    ): array {
        $data = $this->baseAddressData($address) + [
            'created_at' => now(),
            'created_by' => $actor?->name,
        ];

        Log::log(
            Log::ACTION_CREATE_COMPANY_ADDRESS,
            $data,
            $actorId,
        );

        return $data;
    }

    /**
     * Log a company address show event.
     *
     * @param  CompanyAddress $address The address that was shown.
     * @param  User $actor The user who performed the action.
     * @param  int $actorId The ID of the user who performed the action.
     *
     * @return array The structured data written to the log entry.
     */
    public function logShow(
        CompanyAddress $address,
        User $actor,
        int $actorId
    ): array {
        $data = $this->baseAddressData($address) + [
            'shown_at' => now(),
            'shown_by' => $actor?->name,
        ];

        Log::log(
            Log::ACTION_SHOW_COMPANY_ADDRESS,
            $data,
            $actorId,
        );

        return $data;
    }
    /**
     * Log a company address update event.
     *
     * @param  CompanyAddress $address The address that was updated.
     * @param  User $actor The user who performed the action.
     * @param  int $actorId The ID of the user who performed the action.
     *
     * @return array The structured data written to the log entry.
     */
    public function logUpdate(
        CompanyAddress $address,
        User $actor,
        int $actorId
    ): array {
        $data = $this->baseAddressData($address) + [
            'updated_at' => now(),
            'updated_by' => $actor?->name,
        ];

        Log::log(
            Log::ACTION_UPDATE_COMPANY_ADDRESS,
            $data,
            $actorId,
        );

        return $data;
    }

    /**
     * Log a company address deletion event.
     *
     * @param  CompanyAddress $address The address that was deleted.
     * @param  User $actor The user who performed the action.
     * @param  int $actorId The ID of the user who performed the action.
     *
     * @return array The structured data written to the log entry.
     */
    public function logDeletion(
        CompanyAddress $address,
        User $actor,
        int $actorId
    ): array {
        $data = $this->baseAddressData($address) + [
            'deleted_at' => now(),
            'deleted_by' => $actor?->name,
        ];

        Log::log(
            Log::ACTION_DELETE_COMPANY_ADDRESS,
            $data,
            $actorId,
        );

        return $data;
    }

    /**
     * Log company address force deletion (permanent).
     *
     * @param  CompanyAddress $address The address that was force deleted.
     * @param  User $actor The user who performed the action.
     * @param  int $actorId The ID of the user who performed the action.
     *
     * @return array The structured data written to the log entry.
     */
    public function logForceDeletion(
        CompanyAddress $address,
        User $actor,
        int $actorId
    ): array {
        $data = $this->baseAddressData($address) + [
            'force_deleted_at' => now(),
            'force_deleted_by' => $actor?->name,
        ];

        Log::log(
            Log::ACTION_FORCE_DELETE_COMPANY_ADDRESS,
            $data,
            $actorId,
        );

        return $data;
    }

    /**
     * Log a company address restoration event.
     *
     * @param  CompanyAddress $address The address that was restored.
     * @param  User $actor The user who performed the action.
     * @param  int $actorId The ID of the user who performed the action.
     * or null for system-initiated restoration.
     *
     * @return array The structured data written to the log entry.
     */
    public function logRestoration(
        CompanyAddress $address,
        User $actor,
        int $actorId
    ): array {
        $data = $this->baseAddressData($address) + [
            'restored_at' => now(),
            'restored_by' => $actor?->name,
        ];

        Log::log(
            Log::ACTION_RESTORE_COMPANY_ADDRESS,
            $data,
            $actorId,
        );

        return $data;
    }

    /**
     * Log a company address import event.
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
            Log::ACTION_IMPORT_COMPANY_ADDRESS,
            $data,
            $actorId,
        );

        return $data;
    }

    /**
     * Log a company address export event.
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
            Log::ACTION_EXPORT_COMPANY_ADDRESS,
            $data,
            $actorId,
        );

        return $data;
    }

    /**
     * Log a company address update event performed by a scheduled task (cron).
     *
     * @param  CompanyAddress $address The address that was updated.
     *
     * @return array The structured data written to the log entry.
     */
    public function logUpdateByCron(
        CompanyAddress $address,
    ): array {
        $data = $this->baseAddressData($address) + [
            'updated_at' => now(),
            'updated_by' => 'System (Cron)',
        ];

        Log::log(
            Log::ACTION_COMPANY_ADDRESS_UPDATED_BY_CRON,
            $data,
            null,
        );

        return $data;
    }

    /**
     * Get base address data for logging.
     *
     * @param  CompanyAddress $address
     *
     * @return array
     */
    protected function baseAddressData(CompanyAddress $address): array
    {
        if (! $address) {
            return $this->getNullData();
        }

        return $this->getAddressData($address);
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
            'address_line_1' => null,
            'address_line_2' => null,
            'type' => null,
            'city' => null,
            'county' => null,
            'postal_code' => null,
            'country' => null,
            'company_id' => null,
            'meta' => null,
        ];
    }

    /**
     * Get address data
     *
     * @param  CompanyAddress $address
     *
     * @return array
     */
    private function getAddressData(CompanyAddress $address): array
    {
        return [
            'id' => $address->id,
            'address_line_1' => $address->address_line_1,
            'address_line_2' => $address->address_line_2,
            'type' => $address->type,
            'city' => $address->city,
            'county' => $address->county,
            'postal_code' => $address->postal_code,
            'country' => $address->country,
            'company_id' => $address->company_id,
            'meta' => $address->meta,
        ];
    }
}
