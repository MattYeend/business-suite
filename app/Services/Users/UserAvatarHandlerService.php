<?php

namespace App\Services\Users;

use App\Models\User;

/**
 * Handles avatar upload, replacement, and removal logic.
 */
class UserAvatarHandlerService
{
    /**
     * Inject the required services into the avatar helper service.
     *
     * @param  UserAvatarService $avatarService
     * @param  UserAvatarValidatorService $validator
     */
    public function __construct(
        protected UserAvatarService $avatarService,
        protected UserAvatarValidatorService $validator
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
        if ($this->validator->isValidUpload($avatar)) {
            $path = $this->avatarService->upload($avatar, $user);
            $user->update(['avatar' => $path]);
        }
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
        if ($this->validator->shouldRemove($avatar)) {
            $this->remove($user);
            return;
        }

        if ($this->validator->isValidUpload($avatar)) {
            $path = $this->avatarService->replace($avatar, $user);
            $user->update(['avatar' => $path]);
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
}
