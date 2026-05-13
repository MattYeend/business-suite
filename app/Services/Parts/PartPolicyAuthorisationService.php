<?php

namespace App\Services\Parts;

use App\Models\Part;
use App\Models\User;
use App\Services\UserRoleCheckerService;

class PartPolicyAuthorisationService
{
    /**
     * Inject the required services into the policy authorisation service.
     *
     * @param  PartActiveCheckerService $activeChecker
     * @param  UserRoleCheckerService $roleChecker
     */
    public function __construct(
        protected PartActiveCheckerService $activeChecker,
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
     * Check if part is active (not soft-deleted).
     *
     * @param  Part $part
     *
     * @return bool
     */
    public function isActive(Part $part): bool
    {
        return $this->activeChecker->isActive($part);
    }

    /**
     * Check if part is soft-deleted.
     *
     * @param  Part $part
     *
     * @return bool
     */
    public function isTrashed(Part $part): bool
    {
        return $this->activeChecker->isTrashed($part);
    }

    /**
     * Determine whether the user can view the model.
     * Only admins can view companies.
     *
     * @param  User $user
     * @param  Part $part
     *
     * @return bool
     */
    public function canView(User $user, Part $part): bool
    {
        return $this->isAdmin($user) && $this->activeChecker->isActive(
            $part
        );
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  User $user
     * @param  Part $part
     *
     * @return bool
     */
    public function canUpdate(User $user, Part $part): bool
    {
        return $this->isAdmin($user) && $this->activeChecker->isActive(
            $part
        );
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  User $user
     * @param  Part $part
     *
     * @return bool
     */
    public function canDelete(User $user, Part $part): bool
    {
        return $this->isAdmin($user) && $this->activeChecker->canBeModified(
            $part
        );
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  User $user
     * @param  Part $part
     *
     * @return bool
     */
    public function canRestore(User $user, Part $part): bool
    {
        return $this->isAdmin($user) &&
            $this->activeChecker->canBeRestoredOrForceDeleted($part);
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  User $user
     * @param  Part $part
     *
     * @return bool
     */
    public function canForceDelete(User $user, Part $part): bool
    {
        return $this->activeChecker->canUserPerformAction(
            $part,
            'restoreOrForceDelete',
            $user
        );
    }
}
