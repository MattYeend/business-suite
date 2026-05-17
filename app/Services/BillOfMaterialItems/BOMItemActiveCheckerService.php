<?php

namespace App\Services\BillOfMaterialItems;

use App\Models\BillOfMaterialItem;
use App\Models\User;
use App\Services\UserRoleCheckerService;

class BOMItemActiveCheckerService
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
     * Check if BOMItem is active (not soft-deleted).
     *
     * @param  BillOfMaterialItem $billOfMaterialItem
     *
     * @return bool
     */
    public function isActive(BillOfMaterialItem $billOfMaterialItem): bool
    {
        return ! $billOfMaterialItem->trashed();
    }

    /**
     * Check if BOMItem is soft-deleted.
     *
     * @param  BillOfMaterialItem $billOfMaterialItem
     *
     * @return bool
     */
    public function isTrashed(BillOfMaterialItem $billOfMaterialItem): bool
    {
        return $billOfMaterialItem->trashed();
    }

    /**
     * Check if BOMItem is active (not soft-deleted) and can be
     * updated/deleted.
     *
     * @param  BillOfMaterialItem $billOfMaterialItem
     *
     * @return bool
     */
    public function canBeModified(BillOfMaterialItem $billOfMaterialItem): bool
    {
        return $this->isActive($billOfMaterialItem);
    }

    /**
     * Check if BOMItem is soft-deleted and can be restored/force-deleted.
     *
     * @param  BillOfMaterialItem $billOfMaterialItem
     *
     * @return bool
     */
    public function canBeRestoredOrForceDeleted(
        BillOfMaterialItem $billOfMaterialItem
    ): bool {
        return $this->isTrashed($billOfMaterialItem);
    }

    /**
     * Check if user can modify BOMItem (update/delete) or
     * restore/force-delete BOMItem based on its active status.
     *
     * @param  BillOfMaterialItem $billOfMaterialItem
     * @param  string $action
     * @param  User $user
     *
     * @return bool
     */
    public function canUserPerformAction(
        BillOfMaterialItem $billOfMaterialItem,
        string $action,
        User $user
    ): bool {
        if ($action === 'modify') {
            return $this->roleChecker->isAdmin($user) && $this->canBeModified(
                $billOfMaterialItem
            );
        }

        if ($action === 'restoreOrForceDelete') {
            return $this->roleChecker->isAdmin($user) &&
                $this->canBeRestoredOrForceDeleted($billOfMaterialItem);
        }

        throw new \InvalidArgumentException("Invalid action: {$action}");
    }
}
