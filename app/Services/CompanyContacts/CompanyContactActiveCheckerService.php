<?php

namespace App\Services\CompanyContacts;

use App\Models\CompanyContact;
use App\Models\User;
use App\Services\UserRoleCheckerService;

class CompanyContactActiveCheckerService
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
     * Check if contact is active (not soft-deleted).
     *
     * @param  CompanyContact $companyContact
     *
     * @return bool
     */
    public function isActive(CompanyContact $companyContact): bool
    {
        return ! $companyContact->trashed();
    }

    /**
     * Check if contact is soft-deleted.
     *
     * @param  CompanyContact $companyContact
     *
     * @return bool
     */
    public function isTrashed(CompanyContact $companyContact): bool
    {
        return $companyContact->trashed();
    }

    /**
     * Check if contact is active (not soft-deleted) and can be
     * updated/deleted.
     *
     * @param  CompanyContact $companyContact
     *
     * @return bool
     */
    public function canBeModified(CompanyContact $companyContact): bool
    {
        return $this->isActive($companyContact);
    }

    /**
     * Check if contact is soft-deleted and can be restored/force-deleted.
     *
     * @param  CompanyContact $companyContact
     *
     * @return bool
     */
    public function canBeRestoredOrForceDeleted(
        CompanyContact $companyContact
    ): bool {
        return $this->isTrashed($companyContact);
    }

    /**
     * Check if user can modify contact (update/delete) or restore/force-delete
     * contact based on its active status.
     *
     * @param  CompanyContact $companyContact
     * @param  string $action The action being checked, either 'modify' or
     * 'restoreOrForceDelete'.
     * @param  User $user The user performing the action, used for admin check
     * in the callback.
     *
     * @return bool
     */
    public function canUserPerformAction(
        CompanyContact $companyContact,
        string $action,
        User $user
    ): bool {
        if ($action === 'modify') {
            return $this->roleChecker->isAdmin($user) && $this->canBeModified(
                $companyContact
            );
        }

        if ($action === 'restoreOrForceDelete') {
            return $this->roleChecker->isAdmin($user) &&
                $this->canBeRestoredOrForceDeleted($companyContact);
        }

        throw new \InvalidArgumentException("Invalid action: {$action}");
    }
}
