<?php

namespace App\Services\Companies;

use App\Models\Company;
use App\Models\User;
use App\Services\UserRoleCheckerService;

class CompanyPolicyAuthorizationService
{
    public function __construct(
        protected CompanyActiveCheckerService $activeChecker,
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
     * Check if company is active (not soft-deleted).
     *
     * @param  Company $company
     *
     * @return bool
     */
    public function isActive(Company $company): bool
    {
        return $this->activeChecker->isActive($company);
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
        return $this->activeChecker->isTrashed($company);
    }

    /**
     * Determine whether the user can view the model.
     * Only admins can view company industries.
     *
     * @param  User $user
     * @param  Company $company
     *
     * @return bool
     */
    public function canView(User $user, Company $company): bool
    {
        return $this->isAdmin($user) && $this->activeChecker->isActive(
            $company
        );
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  User $user
     * @param  Company $company
     *
     * @return bool
     */
    public function canUpdate(User $user, Company $company): bool
    {
        return $this->isAdmin($user) && $this->activeChecker->isActive(
            $company
        );
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  User $user
     * @param  Company $company
     *
     * @return bool
     */
    public function canDelete(User $user, Company $company): bool
    {
        return $this->isAdmin($user) && $this->activeChecker->canBeModified(
            $company
        );
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  User $user
     * @param  Company $company
     *
     * @return bool
     */
    public function canRestore(User $user, Company $company): bool
    {
        return $this->isAdmin($user) &&
            $this->activeChecker->canBeRestoredOrForceDeleted($company);
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  User $user
     * @param  Company $company
     *
     * @return bool
     */
    public function canForceDelete(User $user, Company $company): bool
    {
        return $this->activeChecker->canUserPerformAction(
            $company,
            'restoreOrForceDelete',
            $user
        );
    }
}
