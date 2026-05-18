<?php

namespace App\Services\Images;

use App\Models\Image;

class ImageFormatterService
{
    /**
     * Format a single image with all data.
     *
     * @param  Image $image
     *
     * @return array
     */
    public function format(Image $image): array
    {
        return array_merge(
            $this->getBaseData($image),
            $this->getMetaInformation($image),
            $this->getDateData($image),
        );
    }

    /**
     * Get the image base data
     *
     * @param  Image $image
     *
     * @return array
     */
    private function getBaseData(Image $image): array
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
        ];
    }

    /**
     * Get the image's meta information
     *
     * @param  Image $image
     *
     * @return array
     */
    private function getMetaInformation(Image $image): array
    {
        return [
            'alt_text' => $image->alt_text,
            'title' => $image->title,
            'description' => $image->description,
            'meta' => $image->meta,
        ];
    }

    /**
     * Get the image's date data.
     *
     * @param  Image $image
     *
     * @return array
     */
    private function getDateData(Image $image): array
    {
        return [
            'uploaded_by' => $image->uploaded_by,
            'created_at' => $image->created_at,
            'updated_at' => $image->updated_at,
            'deleted_at' => $image->deleted_at,
            'restored_at' => $image->restored_at,
            'created_by' => $image->created_by,
            'updated_by' => $image->updated_by,
            'deleted_by' => $image->deleted_by,
            'restored_by' => $image->restored_by,
        ];
    }
}
