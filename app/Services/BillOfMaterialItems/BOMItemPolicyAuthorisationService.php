<?php

namespace App\Services\BillOfMaterialItems;

use App\Models\BillOfMaterialItem;
use App\Models\User;
use App\Services\UserRoleCheckerService;

class BOMItemPolicyAuthorisationService
{
    /**
     * Inject the required services into the policy authorisation service.
     *
     * @param BOMItemActiveCheckerService $activeChecker
     * @param UserRoleCheckerService $roleChecker
     */
    public function __construct(
        protected BOMItemActiveCheckerService $activeChecker,
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
     * Check if BOMItem is active (not soft-deleted).
     *
     * @param  BillOfMaterialItem $billOfMaterialItem
     *
     * @return bool
     */
    public function isActive(BillOfMaterialItem $billOfMaterialItem): bool
    {
        return $this->activeChecker->isActive($billOfMaterialItem);
    }

    /**
     * Check if BOM is soft-deleted.
     *
     * @param  BillOfMaterialItem $billOfMaterialItem
     *
     * @return bool
     */
    public function isTrashed(BillOfMaterialItem $billOfMaterialItem): bool
    {
        return $this->activeChecker->isTrashed($billOfMaterialItem);
    }

    /**
     * Determine whether the user can view the model.
     * Only admins can view companies.
     *
     * @param  User $user
     * @param  BillOfMaterialItem $billOfMaterialItem
     *
     * @return bool
     */
    public function canView(User $user, BillOfMaterialItem $billOfMaterialItem): bool
    {
        return $this->isAdmin($user) && $this->activeChecker->isActive(
            $billOfMaterialItem
        );
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  User $user
     * @param  BillOfMaterialItem $billOfMaterialItem
     *
     * @return bool
     */
    public function canUpdate(User $user, BillOfMaterialItem $billOfMaterialItem): bool
    {
        return $this->isAdmin($user) && $this->activeChecker->isActive(
            $billOfMaterialItem
        );
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  User $user
     * @param  BillOfMaterialItem $billOfMaterialItem
     *
     * @return bool
     */
    public function canDelete(User $user, BillOfMaterialItem $billOfMaterialItem): bool
    {
        return $this->isAdmin($user) && $this->activeChecker->canBeModified(
            $billOfMaterialItem
        );
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  User $user
     * @param  BillOfMaterialItem $billOfMaterialItem
     *
     * @return bool
     */
    public function canRestore(User $user, BillOfMaterialItem $billOfMaterialItem): bool
    {
        return $this->isAdmin($user) &&
            $this->activeChecker->canBeRestoredOrForceDeleted($billOfMaterialItem);
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  User $user
     * @param  BillOfMaterialItem $billOfMaterialItem
     *
     * @return bool
     */
    public function canForceDelete(
        User $user,
        BillOfMaterialItem $billOfMaterialItem
    ): bool {
        return $this->activeChecker->canUserPerformAction(
            $billOfMaterialItem,
            'restoreOrForceDelete',
            $user
        );
    }
}
