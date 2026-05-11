<?php

namespace App\Services\Companies;

use App\Models\Company;
use App\Models\User;
use App\Services\UserRoleCheckerService;

class CompanyActiveCheckerService
{
    /**
     * Inject the required services into the active checker service.
     *
     * @param  UserRoleCheckerService $roleChecker
     *
     * @return void
     */
    public function __construct(
        protected UserRoleCheckerService $roleChecker
    ) {
    }
    /**
     * Check if company is active (not soft-deleted).
     *
     * @param  Company $company
     *
     * @return bool
     */
    public function isActive(Company $company): bool
    {
        return ! $company->trashed();
    }

    /**
     * Check if company is soft-deleted.
     *
     * @param  Company $company
     *
     * @return bool
     */
    public function isTrashed(Company $company): bool
    {
        return $company->trashed();
    }

    /**
     * Check if company is active (not soft-deleted) and can be
     * updated/deleted.
     *
     * @param  Company $company
     *
     * @return bool
     */
    public function canBeModified(Company $company): bool
    {
        return $this->isActive($company);
    }

    /**
     * Check if company is soft-deleted and can be restored/force-deleted.
     *
     * @param  Company $company
     *
     * @return bool
     */
    public function canBeRestoredOrForceDeleted(
        Company $company
    ): bool {
        return $this->isTrashed($company);
    }

    /**
     * Check if user can modify company (update/delete) or restore/force-delete
     * company based on its active status.
     *
     * @param  Company $company
     * @param  string $action The action being checked, either 'modify' or
     * 'restoreOrForceDelete'.
     * @param  User $user The user performing the action, used for admin check
     * in the callback.
     *
     * @return bool
     */
    public function canUserPerformAction(
        Company $company,
        string $action,
        User $user
    ): bool {
        if ($action === 'modify') {
            return $this->roleChecker->isAdmin($user) && $this->canBeModified(
                $company
            );
        }

        if ($action === 'restoreOrForceDelete') {
            return $this->roleChecker->isAdmin($user) &&
                $this->canBeRestoredOrForceDeleted($company);
        }

        throw new \InvalidArgumentException("Invalid action: {$action}");
    }
}
