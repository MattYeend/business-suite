<?php

namespace App\Services\BillOfMaterials;

use App\Models\BillOfMaterial;
use App\Models\User;
use App\Services\UserRoleCheckerService;

class BOMActiveCheckerService
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
     * Check if BOM is active (not soft-deleted).
     *
     * @param  BillOfMaterial $billOfMaterial
     *
     * @return bool
     */
    public function isActive(BillOfMaterial $billOfMaterial): bool
    {
        return ! $billOfMaterial->trashed();
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
        return $billOfMaterial->trashed();
    }

    /**
     * Check if BOM is active (not soft-deleted) and can be
     * updated/deleted.
     *
     * @param  BillOfMaterial $billOfMaterial
     *
     * @return bool
     */
    public function canBeModified(BillOfMaterial $billOfMaterial): bool
    {
        return $this->isActive($billOfMaterial);
    }

    /**
     * Check if BOM is soft-deleted and can be restored/force-deleted.
     *
     * @param  BillOfMaterial $billOfMaterial
     *
     * @return bool
     */
    public function canBeRestoredOrForceDeleted(
        BillOfMaterial $billOfMaterial
    ): bool {
        return $this->isTrashed($billOfMaterial);
    }

    /**
     * Check if user can modify BOM (update/delete) or
     * restore/force-delete BOM based on its active status.
     *
     * @param  BillOfMaterial $billOfMaterial
     * @param  string $action
     * @param  User $user
     *
     * @return bool
     */
    public function canUserPerformAction(
        BillOfMaterial $billOfMaterial,
        string $action,
        User $user
    ): bool {
        if ($action === 'modify') {
            return $this->roleChecker->isAdmin($user) && $this->canBeModified(
                $billOfMaterial
            );
        }

        if ($action === 'restoreOrForceDelete') {
            return $this->roleChecker->isAdmin($user) &&
                $this->canBeRestoredOrForceDeleted($billOfMaterial);
        }

        throw new \InvalidArgumentException("Invalid action: {$action}");
    }
}
