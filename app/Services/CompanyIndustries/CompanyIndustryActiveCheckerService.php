<?php

namespace App\Services\CompanyIndustries;

use App\Models\CompanyIndustry;
use App\Models\User;
use App\Services\UserRoleCheckerService;

class CompanyIndustryActiveCheckerService
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
     * Check if industry is active (not soft-deleted).
     *
     * @param  CompanyIndustry $companyIndustry
     *
     * @return bool
     */
    public function isActive(CompanyIndustry $companyIndustry): bool
    {
        return ! $companyIndustry->trashed();
    }

    /**
     * Check if industry is soft-deleted.
     *
     * @param  CompanyIndustry $companyIndustry
     *
     * @return bool
     */
    public function isTrashed(CompanyIndustry $companyIndustry): bool
    {
        return $companyIndustry->trashed();
    }

    /**
     * Check if industry is active (not soft-deleted) and can be
     * updated/deleted.
     *
     * @param  CompanyIndustry $companyIndustry
     *
     * @return bool
     */
    public function canBeModified(CompanyIndustry $companyIndustry): bool
    {
        return $this->isActive($companyIndustry);
    }

    /**
     * Check if industry is soft-deleted and can be restored/force-deleted.
     *
     * @param  CompanyIndustry $companyIndustry
     *
     * @return bool
     */
    public function canBeRestoredOrForceDeleted(
        CompanyIndustry $companyIndustry
    ): bool {
        return $this->isTrashed($companyIndustry);
    }

    /**
     * Check if user can modify industry (update/delete) or restore/force-delete
     * industry based on its active status.
     *
     * @param  CompanyIndustry $companyIndustry
     * @param  string $action The action being checked, either 'modify' or
     * 'restoreOrForceDelete'.
     * @param  User $user The user performing the action, used for admin check
     * in the callback.
     *
     * @return bool
     */
    public function canUserPerformAction(
        CompanyIndustry $companyIndustry,
        string $action,
        User $user
    ): bool {
        if ($action === 'modify') {
            return $this->roleChecker->isAdmin($user) && $this->canBeModified(
                $companyIndustry
            );
        }

        if ($action === 'restoreOrForceDelete') {
            return $this->roleChecker->isAdmin($user) &&
                $this->canBeRestoredOrForceDeleted($companyIndustry);
        }

        throw new \InvalidArgumentException("Invalid action: {$action}");
    }
}
