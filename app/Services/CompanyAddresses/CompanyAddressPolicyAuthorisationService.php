<?php

namespace App\Services\CompanyAddresses;

use App\Models\CompanyAddress;
use App\Models\User;
use App\Services\UserRoleCheckerService;

class CompanyAddressPolicyAuthorisationService
{
    /**
     * Inject the required services into the policy authorisation service.
     *
     * @param CompanyAddressActiveCheckerService $activeChecker
     * @param UserRoleCheckerService $roleChecker
     */
    public function __construct(
        protected CompanyAddressActiveCheckerService $activeChecker,
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
     * Check if address is active (not soft-deleted).
     *
     * @param  CompanyAddress $companyAddress
     *
     * @return bool
     */
    public function isActive(CompanyAddress $companyAddress): bool
    {
        return $this->activeChecker->isActive($companyAddress);
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
        return $this->activeChecker->isTrashed($companyAddress);
    }

    /**
     * Determine whether the user can view the model.
     * Only admins can view company addresses.
     *
     * @param  User $user
     * @param  CompanyAddress $address
     *
     * @return bool
     */
    public function canView(User $user, CompanyAddress $address): bool
    {
        return $this->isAdmin($user) && $this->activeChecker->isActive(
            $address
        );
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  User $user
     * @param  CompanyAddress $address
     *
     * @return bool
     */
    public function canUpdate(User $user, CompanyAddress $address): bool
    {
        return $this->isAdmin($user) && $this->activeChecker->isActive(
            $address
        );
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  User $user
     * @param  CompanyAddress $address
     *
     * @return bool
     */
    public function canDelete(User $user, CompanyAddress $address): bool
    {
        return $this->isAdmin($user) && $this->activeChecker->canBeModified(
            $address
        );
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  User $user
     * @param  CompanyAddress $address
     *
     * @return bool
     */
    public function canRestore(User $user, CompanyAddress $address): bool
    {
        return $this->isAdmin($user) &&
            $this->activeChecker->canBeRestoredOrForceDeleted($address);
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  User $user
     * @param  CompanyAddress $address
     *
     * @return bool
     */
    public function canForceDelete(User $user, CompanyAddress $address): bool
    {
        return $this->activeChecker->canUserPerformAction(
            $address,
            'restoreOrForceDelete',
            $user
        );
    }
}
