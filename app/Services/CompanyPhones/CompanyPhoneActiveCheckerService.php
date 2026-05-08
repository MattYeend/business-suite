<?php

namespace App\Services\CompanyPhones;

use App\Models\CompanyPhone;
use App\Models\User;
use App\Services\UserRoleCheckerService;

class CompanyPhoneActiveCheckerService
{
    public function __construct(
        protected UserRoleCheckerService $roleChecker
    ) {
    }
    /**
     * Check if company phone is active (not soft-deleted).
     *
     * @param  CompanyPhone $companyPhone
     *
     * @return bool
     */
    public function isActive(CompanyPhone $companyPhone): bool
    {
        return ! $companyPhone->trashed();
    }

    /**
     * Check if company phone is soft-deleted.
     *
     * @param  CompanyPhone $companyPhone
     *
     * @return bool
     */
    public function isTrashed(CompanyPhone $companyPhone): bool
    {
        return $companyPhone->trashed();
    }

    /**
     * Check if company phone is active (not soft-deleted) and can be
     * updated/deleted.
     *
     * @param  CompanyPhone $companyPhone
     *
     * @return bool
     */
    public function canBeModified(CompanyPhone $companyPhone): bool
    {
        return $this->isActive($companyPhone);
    }

    /**
     * Check if company phone is soft-deleted and can be restored/force-deleted.
     *
     * @param  CompanyPhone $companyPhone
     *
     * @return bool
     */
    public function canBeRestoredOrForceDeleted(
        CompanyPhone $companyPhone
    ): bool {
        return $this->isTrashed($companyPhone);
    }

    /**
     * Check if user can modify company phone (update/delete) or restore/force-delete
     * company phone based on its active status.
     *
     * @param  CompanyPhone $companyPhone
     * @param  string $action The action being checked, either 'modify' or
     * 'restoreOrForceDelete'.
     * @param  User $user The user performing the action, used for admin check
     * in the callback.
     *
     * @return bool
     */
    public function canUserPerformAction(
        CompanyPhone $companyPhone,
        string $action,
        User $user
    ): bool {
        if ($action === 'modify') {
            return $this->roleChecker->isAdmin($user) && $this->canBeModified(
                $companyPhone
            );
        }

        if ($action === 'restoreOrForceDelete') {
            return $this->roleChecker->isAdmin($user) &&
                $this->canBeRestoredOrForceDeleted($companyPhone);
        }

        throw new \InvalidArgumentException("Invalid action: {$action}");
    }
}
