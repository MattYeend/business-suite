<?php

namespace App\Services\CompanyIndustries;

use App\Models\CompanyIndustry;
use App\Models\User;
use App\Services\UserRoleCheckerService;

class CompanyIndustryPolicyAuthorisationService
{
    /**
     * Inject the required services into the policy authorisation service.
     *
     * @param CompanyIndustryActiveCheckerService $activeChecker
     * @param UserRoleCheckerService $roleChecker
     */
    public function __construct(
        protected CompanyIndustryActiveCheckerService $activeChecker,
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
     * Check if industry is active (not soft-deleted).
     *
     * @param  CompanyIndustry $companyIndustry
     *
     * @return bool
     */
    public function isActive(CompanyIndustry $companyIndustry): bool
    {
        return $this->activeChecker->isActive($companyIndustry);
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
        return $this->activeChecker->isTrashed($companyIndustry);
    }

    /**
     * Determine whether the user can view the model.
     * Only admins can view company industries.
     *
     * @param  User $user
     * @param  CompanyIndustry $industry
     *
     * @return bool
     */
    public function canView(User $user, CompanyIndustry $industry): bool
    {
        return $this->isAdmin($user) && $this->activeChecker->isActive(
            $industry
        );
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  User $user
     * @param  CompanyIndustry $industry
     *
     * @return bool
     */
    public function canUpdate(User $user, CompanyIndustry $industry): bool
    {
        return $this->isAdmin($user) && $this->activeChecker->isActive(
            $industry
        );
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  User $user
     * @param  CompanyIndustry $industry
     *
     * @return bool
     */
    public function canDelete(User $user, CompanyIndustry $industry): bool
    {
        return $this->isAdmin($user) && $this->activeChecker->canBeModified(
            $industry
        );
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  User $user
     * @param  CompanyIndustry $industry
     *
     * @return bool
     */
    public function canRestore(User $user, CompanyIndustry $industry): bool
    {
        return $this->isAdmin($user) &&
            $this->activeChecker->canBeRestoredOrForceDeleted($industry);
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  User $user
     * @param  CompanyIndustry $industry
     *
     * @return bool
     */
    public function canForceDelete(User $user, CompanyIndustry $industry): bool
    {
        return $this->activeChecker->canUserPerformAction(
            $industry,
            'restoreOrForceDelete',
            $user
        );
    }
}
