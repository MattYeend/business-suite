<?php

namespace App\Services\Images;

use App\Models\Image;
use App\Models\User;
use App\Services\UserRoleCheckerService;

class ImagePolicyAuthorisationService
{
    /**
     * Inject the required services into the policy authorisation service.
     *
     * @param ImageActiveCheckerService $activeChecker
     * @param UserRoleCheckerService $roleChecker
     */
    public function __construct(
        protected ImageActiveCheckerService $activeChecker,
        protected UserRoleCheckerService $roleChecker
    ) {
    }

    /**
     * Check if user is a regular user, admin, or super admin.
     *
     * @param  User $user
     *
     * @return bool
     */
    public function isUser(User $user): bool
    {
        return $this->roleChecker->isUser($user);
    }

    /**
     * Check if user is admin or super admin.
     *
     * @param  User $user
     *
     * @return bool
     */
    public function isAdmin(User $user): bool
    {
        return $this->roleChecker->isAdmin($user);
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
        return $this->activeChecker->isActive($image);
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
        return $this->activeChecker->isTrashed($image);
    }

    /**
     * Determine whether the user can view the model.
     * Only admins can view images.
     *
     * @param  User $user
     * @param  Image $image
     *
     * @return bool
     */
    public function canView(User $user, Image $image): bool
    {
        return $this->isAdmin($user) && $this->activeChecker->isActive(
            $image
        );
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  User $user
     * @param  Image $image
     *
     * @return bool
     */
    public function canUpdate(User $user, Image $image): bool
    {
        return $this->isAdmin($user) && $this->activeChecker->isActive(
            $image
        );
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  User $user
     * @param  Image $image
     *
     * @return bool
     */
    public function canDelete(User $user, Image $image): bool
    {
        return $this->isAdmin($user) && $this->activeChecker->canBeModified(
            $image
        );
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  User $user
     * @param  Image $image
     *
     * @return bool
     */
    public function canRestore(User $user, Image $image): bool
    {
        return $this->isAdmin($user) &&
            $this->activeChecker->canBeRestoredOrForceDeleted($image);
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  User $user
     * @param  Image $image
     *
     * @return bool
     */
    public function canForceDelete(User $user, Image $image): bool
    {
        return $this->activeChecker->canUserPerformAction(
            $image,
            'restoreOrForceDelete',
            $user
        );
    }
}
