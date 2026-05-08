<?php

namespace App\Services\CompanyPhones;

use App\Models\CompanyPhone;
use App\Models\User;
use App\Services\UserRoleCheckerService;

class CompanyPhonePolicyAuthorizationService
{
    public function __construct(
        protected CompanyPhoneActiveCheckerService $activeChecker,
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
     * @param  CompanyPhone $companyPhone
     *
     * @return bool
     */
    public function isActive(CompanyPhone $companyPhone): bool
    {
        return $this->activeChecker->isActive($companyPhone);
    }

    /**
     * Check if company is soft-deleted.
     *
     * @param  CompanyPhone $companyPhone
     *
     * @return bool
     */
    public function isTrashed(CompanyPhone $companyPhone): bool
    {
        return $this->activeChecker->isTrashed($companyPhone);
    }

    /**
     * Determine whether the user can view the model.
     * Only admins can view company phones.
     *
     * @param  User $user
     * @param  CompanyPhone $companyPhone
     *
     * @return bool
     */
    public function canView(User $user, CompanyPhone $companyPhone): bool
    {
        return $this->isAdmin($user) && $this->activeChecker->isActive(
            $companyPhone
        );
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  User $user
     * @param  CompanyPhone $companyPhone
     *
     * @return bool
     */
    public function canUpdate(User $user, CompanyPhone $companyPhone): bool
    {
        return $this->isAdmin($user) && $this->activeChecker->isActive(
            $companyPhone
        );
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  User $user
     * @param  CompanyPhone $companyPhone
     *
     * @return bool
     */
    public function canDelete(User $user, CompanyPhone $companyPhone): bool
    {
        return $this->isAdmin($user) && $this->activeChecker->canBeModified(
            $companyPhone
        );
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  User $user
     * @param  CompanyPhone $companyPhone
     *
     * @return bool
     */
    public function canRestore(User $user, CompanyPhone $companyPhone): bool
    {
        return $this->isAdmin($user) &&
            $this->activeChecker->canBeRestoredOrForceDeleted($companyPhone);
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  User $user
     * @param  CompanyPhone $companyPhone
     *
     * @return bool
     */
    public function canForceDelete(User $user, CompanyPhone $companyPhone): bool
    {
        return $this->activeChecker->canUserPerformAction(
            $companyPhone,
            'restoreOrForceDelete',
            $user
        );
    }
}
