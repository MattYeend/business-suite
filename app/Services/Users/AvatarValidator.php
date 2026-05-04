<?php

namespace App\Services\Users;

/**
 * Validates avatar upload data.
 */
class AvatarValidator
{
    /**
     * Check if avatar should be removed.
     *
     * @param  mixed $avatar
     *
     * @return bool
     */
    public function shouldRemove(mixed $avatar): bool
    {
        return $avatar === null || $avatar === '';
    }

    /**
     * Check if avatar is a valid upload.
     *
     * @param  mixed $avatar
     *
     * @return bool
     */
    public function isValidUpload(mixed $avatar): bool
    {
        return is_object($avatar) && method_exists($avatar, 'isValid');
    }
}
