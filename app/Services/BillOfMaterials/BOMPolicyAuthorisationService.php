<?php

namespace App\Services\BillOfMaterials;

use App\Models\BillOfMaterial;
use App\Models\User;
use App\Services\UserRoleCheckerService;

class BOMPolicyAuthorisationService
{
    /**
     * Inject the required services into the policy authorisation service.
     *
     * @param  BOMActiveCheckerService $activeChecker
     * @param  UserRoleCheckerService $roleChecker
     */
    public function __construct(
        protected BOMActiveCheckerService $activeChecker,
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
     * Check if BOM is active (not soft-deleted).
     *
     * @param  BillOfMaterial $billOfMaterial
     *
     * @return bool
     */
    public function isActive(BillOfMaterial $billOfMaterial): bool
    {
        return $this->activeChecker->isActive($billOfMaterial);
    }

    /**
     * Check if BOM is soft-deleted.
     *
     * @param  BillOfMaterial $billOfMaterial
     *
     * @return bool
     */
    public function isTrashed(BillOfMaterial $billOfMaterial): bool
    {
        return $this->activeChecker->isTrashed($billOfMaterial);
    }

    /**
     * Determine whether the user can view the model.
     * Only admins can view companies.
     *
     * @param  User $user
     * @param  BillOfMaterial $billOfMaterial
     *
     * @return bool
     */
    public function canView(User $user, BillOfMaterial $billOfMaterial): bool
    {
        return $this->isAdmin($user) && $this->activeChecker->isActive(
            $billOfMaterial
        );
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  User $user
     * @param  BillOfMaterial $billOfMaterial
     *
     * @return bool
     */
    public function canUpdate(User $user, BillOfMaterial $billOfMaterial): bool
    {
        return $this->isAdmin($user) && $this->activeChecker->isActive(
            $billOfMaterial
        );
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  User $user
     * @param  BillOfMaterial $billOfMaterial
     *
     * @return bool
     */
    public function canDelete(User $user, BillOfMaterial $billOfMaterial): bool
    {
        return $this->isAdmin($user) && $this->activeChecker->canBeModified(
            $billOfMaterial
        );
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  User $user
     * @param  BillOfMaterial $billOfMaterial
     *
     * @return bool
     */
    public function canRestore(User $user, BillOfMaterial $billOfMaterial): bool
    {
        return $this->isAdmin($user) &&
            $this->activeChecker->canBeRestoredOrForceDeleted($billOfMaterial);
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  User $user
     * @param  BillOfMaterial $billOfMaterial
     *
     * @return bool
     */
    public function canForceDelete(User $user, BillOfMaterial $billOfMaterial): bool
    {
        return $this->activeChecker->canUserPerformAction(
            $billOfMaterial,
            'restoreOrForceDelete',
            $user
        );
    }
}
