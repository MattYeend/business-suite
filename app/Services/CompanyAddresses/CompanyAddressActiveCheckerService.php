<?php

namespace App\Services\CompanyAddresses;

use App\Models\CompanyAddress;
use App\Models\User;
use App\Services\UserRoleCheckerService;

class CompanyAddressActiveCheckerService
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
     * Check if address is active (not soft-deleted).
     *
     * @param  CompanyAddress $companyAddress
     *
     * @return bool
     */
    public function isActive(CompanyAddress $companyAddress): bool
    {
        return ! $companyAddress->trashed();
    }

    /**
     * Check if address is soft-deleted.
     *
     * @param  CompanyAddress $companyAddress
     *
     * @return bool
     */
    public function isTrashed(CompanyAddress $companyAddress): bool
    {
        return $companyAddress->trashed();
    }

    /**
     * Check if address is active (not soft-deleted) and can be
     * updated/deleted.
     *
     * @param  CompanyAddress $companyAddress
     *
     * @return bool
     */
    public function canBeModified(CompanyAddress $companyAddress): bool
    {
        return $this->isActive($companyAddress);
    }

    /**
     * Check if address is soft-deleted and can be restored/force-deleted.
     *
     * @param  CompanyAddress $companyAddress
     *
     * @return bool
     */
    public function canBeRestoredOrForceDeleted(
        CompanyAddress $companyAddress
    ): bool {
        return $this->isTrashed($companyAddress);
    }

    /**
     * Check if user can modify address (update/delete) or restore/force-delete
     * address based on its active status.
     *
     * @param  CompanyAddress $companyAddress
     * @param  string $action The action being checked, either 'modify' or
     * 'restoreOrForceDelete'.
     * @param  User $user The user performing the action, used for admin check
     * in the callback.
     *
     * @return bool
     */
    public function canUserPerformAction(
        CompanyAddress $companyAddress,
        string $action,
        User $user
    ): bool {
        if ($action === 'modify') {
            return $this->roleChecker->isAdmin($user) && $this->canBeModified(
                $companyAddress
            );
        }

        if ($action === 'restoreOrForceDelete') {
            return $this->roleChecker->isAdmin($user) &&
                $this->canBeRestoredOrForceDeleted($companyAddress);
        }

        throw new \InvalidArgumentException("Invalid action: {$action}");
    }
}
