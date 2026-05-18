<?php

namespace App\Services\Images;

class ImageDataPreparationService
{
    /**
     * Prepare image data for creation.
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
            'file_name' => $data['file_name'],
            'file_path' => $data['file_path'],
            'disk' => $data['disk'] ?? 'public',
            'mime_type' => $data['mime_type'] ?? null,
            'file_size' => $data['file_size'] ?? null,
            'width' => $data['width'] ?? null,
            'height' => $data['height'] ?? null,
            'alt_text' => $data['alt_text'] ?? null,
            'title' => $data['title'] ?? null,
            'description' => $data['description'] ?? null,
            'uploaded_by' => $data['uploaded_by'] ?? $createdBy,
            'is_real' => $data['is_real'] ?? true,
            'meta' => $data['meta'] ?? null,
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
            'file_name' => $data['file_name'] ?? null,
            'file_path' => $data['file_path'] ?? null,
            'disk' => $data['disk'] ?? null,
            'mime_type' => $data['mime_type'] ?? null,
            'file_size' => $data['file_size'] ?? null,
            'width' => $data['width'] ?? null,
            'height' => $data['height'] ?? null,
            'alt_text' => $data['alt_text'] ?? null,
            'title' => $data['title'] ?? null,
            'description' => $data['description'] ?? null,
            'uploaded_by' => $data['uploaded_by'] ?? null,
            'is_real' => $data['is_real'] ?? null,
            'meta' => $data['meta'] ?? null,
            'updated_by' => $updatedBy,
        ], fn ($value) => $value !== null);
    }

    /**
     * Prepare attachment data for imageables pivot table.
     *
     * @param  array $data
     *
     * @return array
     */
    public function prepareAttachmentData(array $data): array
    {
        return [
            'sort_order' => $data['sort_order'] ?? 0,
            'is_primary' => $data['is_primary'] ?? false,
            'usage_context' => $data['usage_context'] ?? null,
        ];
    }
}
