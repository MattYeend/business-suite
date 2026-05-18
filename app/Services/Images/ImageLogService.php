<?php

namespace App\Services\Images;

use App\Models\Image;
use App\Models\Log;
use App\Models\User;

class ImageLogService
{
    /**
     * Log image creation.
     *
     * @param  Image $image The image that was created.
     * @param  User $actor The user who performed the action.
     * @param  int $actorId The ID of the user who performed the action.
     *
     * @return array
     */
    public function logCreation(
        Image $image,
        User $actor,
        int $actorId
    ): array {
        $data = $this->baseCompanyData($image) + [
            'created_at' => now(),
            'created_by' => $actor?->name,
        ];

        Log::log(
            Log::ACTION_CREATE_IMAGE,
            $data,
            $actorId,
        );

        return $data;
    }

    /**
     * Log a image show event.
     *
     * @param  Image $image The image that was shown.
     * @param  User $actor The user who performed the action.
     * @param  int $actorId The ID of the user who performed the action.
     *
     * @return array The structured data written to the log entry.
     */
    public function logShow(
        Image $image,
        User $actor,
        int $actorId
    ): array {
        $data = $this->baseCompanyData($image) + [
            'shown_at' => now(),
            'shown_by' => $actor?->name,
        ];

        Log::log(
            Log::ACTION_SHOW_IMAGE,
            $data,
            $actorId,
        );

        return $data;
    }
    /**
     * Log a image update event.
     *
     * @param  Image $image The image that was updated.
     * @param  User $actor The user who performed the action.
     * @param  int $actorId The ID of the user who performed the action.
     *
     * @return array The structured data written to the log entry.
     */
    public function logUpdate(
        Image $image,
        User $actor,
        int $actorId
    ): array {
        $data = $this->baseCompanyData($image) + [
            'updated_at' => now(),
            'updated_by' => $actor?->name,
        ];

        Log::log(
            Log::ACTION_UPDATE_IMAGE,
            $data,
            $actorId,
        );

        return $data;
    }

    /**
     * Log a image deletion event.
     *
     * @param  Image $image The image that was deleted.
     * @param  User $actor The user who performed the action.
     * @param  int $actorId The ID of the user who performed the action.
     *
     * @return array The structured data written to the log entry.
     */
    public function logDeletion(
        Image $image,
        User $actor,
        int $actorId
    ): array {
        $data = $this->baseCompanyData($image) + [
            'deleted_at' => now(),
            'deleted_by' => $actor?->name,
        ];

        Log::log(
            Log::ACTION_DELETE_IMAGE,
            $data,
            $actorId,
        );

        return $data;
    }

    /**
     * Log image force deletion (permanent).
     *
     * @param  Image $image The image that was force deleted.
     * @param  User $actor The user who performed the action.
     * @param  int $actorId The ID of the user who performed the action.
     *
     * @return array The structured data written to the log entry.
     */
    public function logForceDeletion(
        Image $image,
        User $actor,
        int $actorId
    ): array {
        $data = $this->baseCompanyData($image) + [
            'force_deleted_at' => now(),
            'force_deleted_by' => $actor?->name,
        ];

        Log::log(
            Log::ACTION_FORCE_DELETE_IMAGE,
            $data,
            $actorId,
        );

        return $data;
    }

    /**
     * Log a image restoration event.
     *
     * @param  Image $image The image that was restored.
     * @param  User $actor The user who performed the action.
     * @param  int $actorId The ID of the user who performed the action.
     * or null for system-initiated restoration.
     *
     * @return array The structured data written to the log entry.
     */
    public function logRestoration(
        Image $image,
        User $actor,
        int $actorId
    ): array {
        $data = $this->baseCompanyData($image) + [
            'restored_at' => now(),
            'restored_by' => $actor?->name,
        ];

        Log::log(
            Log::ACTION_RESTORE_IMAGE,
            $data,
            $actorId,
        );

        return $data;
    }

    /**
     * Log a image import event.
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
            Log::ACTION_IMPORT_IMAGE,
            $data,
            $actorId,
        );

        return $data;
    }

    /**
     * Log a image export event.
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
            Log::ACTION_EXPORT_IMAGE,
            $data,
            $actorId,
        );

        return $data;
    }

    /**
     * Log a image update event performed by a scheduled task (cron).
     *
     * @param  Image $image The image that was updated.
     *
     * @return array The structured data written to the log entry.
     */
    public function logUpdateByCron(
        Image $image,
    ): array {
        $data = $this->baseCompanyData($image) + [
            'updated_at' => now(),
            'updated_by' => 'System (Cron)',
        ];

        Log::log(
            Log::ACTION_IMAGE_UPDATED_BY_CRON,
            $data,
            null,
        );

        return $data;
    }

    /**
     * Get base image data for logging.
     *
     * @param  Image $image
     *
     * @return array
     */
    protected function baseCompanyData(Image $image): array
    {
        if (! $image) {
            return $this->getNullData();
        }

        return $this->getCompanyData($image);
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
            'file_name' => null,
            'file_path' => null,
            'disk' => null,
            'mime_type' => null,
            'file_size' => null,
            'width' => null,
            'height' => null,
            'alt_text' => null,
            'title' => null,
            'description' => null,
            'meta' => null,
        ];
    }

    /**
     * Get image data
     *
     * @param  Image $image
     *
     * @return array
     */
    private function getCompanyData(Image $image): array
    {
        return [
            'id' => $image->id,
            'file_name' => $image->file_name,
            'file_path' => $image->file_path,
            'disk' => $image->disk,
            'mime_type' => $image->mime_type,
            'file_size' => $image->file_size,
            'width' => $image->width,
            'height' => $image->height,
            'alt_text' => $image->alt_text,
            'title' => $image->title,
            'description' => $image->description,
            'meta' => $image->meta,
        ];
    }
}
