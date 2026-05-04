<?php

namespace App\Services\Users;

use App\Models\User;

/**
 * Handles avatar upload, replacement, and removal logic.
 */
class UserAvatarHandlerService
{
    public function __construct(
        protected UserAvatarService $avatarService
    ) {
    }

    /**
     * Handle avatar upload for new user.
     *
     * @param  User $user
     * @param  mixed $avatar
     *
     * @return void
     */
    public function handleUpload(User $user, mixed $avatar): void
    {
        if (! $this->isValidUpload($avatar)) {
            return;
        }

        $this->uploadAndSave($user, $avatar);
    }

    /**
     * Handle avatar update (upload, replace, or remove).
     *
     * @param  User $user
     * @param  mixed $avatar
     *
     * @return void
     */
    public function handleUpdate(User $user, mixed $avatar): void
    {
        if ($this->shouldRemove($avatar)) {
            $this->remove($user);
            return;
        }

        if ($this->isValidUpload($avatar)) {
            $this->replaceAndSave($user, $avatar);
        }
    }

    /**
     * Remove avatar from user.
     *
     * @param  User $user
     *
     * @return void
     */
    public function remove(User $user): void
    {
        $this->avatarService->delete($user);
        $user->update(['avatar' => null]);
    }

    /**
     * Check if avatar should be removed.
     *
     * @param  mixed $avatar
     *
     * @return bool
     */
    private function shouldRemove(mixed $avatar): bool
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
    private function isValidUpload(mixed $avatar): bool
    {
        return is_object($avatar) && method_exists($avatar, 'isValid');
    }

    /**
     * Upload avatar and save to user.
     *
     * @param  User $user
     * @param  mixed $avatar
     *
     * @return void
     */
    private function uploadAndSave(User $user, mixed $avatar): void
    {
        $path = $this->avatarService->upload($avatar, $user);
        $user->update(['avatar' => $path]);
    }

    /**
     * Replace avatar and save to user.
     *
     * @param  User $user
     * @param  mixed $avatar
     *
     * @return void
     */
    private function replaceAndSave(User $user, mixed $avatar): void
    {
        $path = $this->avatarService->replace($avatar, $user);
        $user->update(['avatar' => $path]);
    }
}
