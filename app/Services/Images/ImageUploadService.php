<?php

namespace App\Services\Images;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageUploadService
{
    /**
     * Handle file upload and return file data
     *
     * @param  UploadedFile $file
     * @param  string $disk
     *
     * @return array
     */
    public function upload(UploadedFile $file, string $disk = 'public'): array
    {
        // Generate a unique filename with UUID
        $uuid = Str::uuid();
        $extension = $file->getClientOriginalExtension();
        $fileName = $file->getClientOriginalName();

        // Create path with year/month structure
        $year = date('Y');
        $month = date('m');
        $path = "images/{$year}/{$month}";

        // Store the file
        $storedPath = $file->storeAs($path, "{$uuid}.{$extension}", $disk);

        // Get image dimensions if it's an image
        $dimensions = $this->getImageDimensions($file);

        return [
            'file_name' => $fileName,
            'file_path' => $storedPath,
            'disk' => $disk,
            'mime_type' => $file->getMimeType(),
            'file_size' => $file->getSize(),
            'width' => $dimensions['width'] ?? null,
            'height' => $dimensions['height'] ?? null,
        ];
    }

    /**
     * Delete a file from storage
     *
     * @param  string $path
     * @param  string $disk
     *
     * @return bool
     */
    public function delete(string $path, string $disk = 'public'): bool
    {
        return Storage::disk($disk)->delete($path);
    }

    /**
     * Get image dimensions
     *
     * @param  UploadedFile $file
     *
     * @return array
     */
    protected function getImageDimensions(UploadedFile $file): array
    {
        try {
            [$width, $height] = getimagesize($file->getRealPath());
            return compact('width', 'height');
        } catch (\Exception $e) {
            return [];
        }
    }
}
