<?php

namespace App\Services\Users;

use Illuminate\Http\UploadedFile;

/**
 * Validates avatar file uploads.
 */
class UserAvatarValidationService
{
    /**
     * Allowed MIME types for avatars.
     */
    private const ALLOWED_MIMES = [
        'image/jpeg',
        'image/png',
        'image/gif',
        'image/webp',
    ];

    /**
     * Maximum file size in bytes (2MB).
     */
    private const MAX_SIZE = 2048 * 1024;

    /**
     * Validate the uploaded file.
     *
     * @param  UploadedFile $file
     *
     * @return void
     *
     * @throws \InvalidArgumentException
     */
    public function validate(UploadedFile $file): void
    {
        $this->validateMimeType($file);
        $this->validateFileSize($file);
    }

    /**
     * Validate file MIME type.
     *
     * @param  UploadedFile $file
     *
     * @return void
     *
     * @throws \InvalidArgumentException
     */
    private function validateMimeType(UploadedFile $file): void
    {
        if (! in_array($file->getMimeType(), self::ALLOWED_MIMES)) {
            throw new \InvalidArgumentException(
                'Invalid file type. Allowed types: jpg, jpeg, png, gif, webp'
            );
        }
    }

    /**
     * Validate file size.
     *
     * @param  UploadedFile $file
     *
     * @return void
     *
     * @throws \InvalidArgumentException
     */
    private function validateFileSize(UploadedFile $file): void
    {
        if ($file->getSize() > self::MAX_SIZE) {
            throw new \InvalidArgumentException('File size exceeds 2MB limit');
        }
    }
}
