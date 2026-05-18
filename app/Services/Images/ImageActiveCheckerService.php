<?php

namespace App\Services\Images;

use App\Models\Image;
use App\Models\User;
use App\Services\UserRoleCheckerService;

class ImageActiveCheckerService
{
    /**
     * Inject the required services into the active checker service.
     *
     * @param UserRoleCheckerService $roleChecker
     */
    public function __construct(
        protected UserRoleCheckerService $roleChecker
    ) {
    }
    /**
     * Check if image is active (not soft-deleted).
     *
     * @param  Image $image
     *
     * @return bool
     */
    public function isActive(Image $image): bool
    {
        return ! $image->trashed();
    }

    /**
     * Check if image is soft-deleted.
     *
     * @param  Image $image
     *
     * @return bool
     */
    public function isTrashed(Image $image): bool
    {
        return $image->trashed();
    }

    /**
     * Check if image is active (not soft-deleted) and can be
     * updated/deleted.
     *
     * @param  Image $image
     *
     * @return bool
     */
    public function canBeModified(Image $image): bool
    {
        return $this->isActive($image);
    }

    /**
     * Check if image is soft-deleted and can be restored/force-deleted.
     *
     * @param  Image $image
     *
     * @return bool
     */
    public function canBeRestoredOrForceDeleted(
        Image $image
    ): bool {
        return $this->isTrashed($image);
    }

    /**
     * Check if user can modify image (update/delete) or restore/force-delete
     * image based on its active status.
     *
     * @param  Image $image
     * @param  string $action The action being checked, either 'modify' or
     * 'restoreOrForceDelete'.
     * @param  User $user The user performing the action, used for admin check
     * in the callback.
     *
     * @return bool
     */
    public function canUserPerformAction(
        Image $image,
        string $action,
        User $user
    ): bool {
        if ($action === 'modify') {
            return $this->roleChecker->isAdmin($user) && $this->canBeModified(
                $image
            );
        }

        if ($action === 'restoreOrForceDelete') {
            return $this->roleChecker->isAdmin($user) &&
                $this->canBeRestoredOrForceDeleted($image);
        }

        throw new \InvalidArgumentException("Invalid action: {$action}");
    }
}
