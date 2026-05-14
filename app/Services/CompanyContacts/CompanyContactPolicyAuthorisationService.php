<?php

namespace App\Services\CompanyContacts;

use App\Models\CompanyContact;
use App\Models\User;
use App\Services\UserRoleCheckerService;

class CompanyContactPolicyAuthorisationService
{
    /**
     * Inject the required services into the policy authorisation service.
     *
     * @param CompanyContactActiveCheckerService $activeChecker
     * @param UserRoleCheckerService $roleChecker
     */
    public function __construct(
        protected CompanyContactActiveCheckerService $activeChecker,
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
     * Check if contact is active (not soft-deleted).
     *
     * @param  CompanyContact $companyContact
     *
     * @return bool
     */
    public function isActive(CompanyContact $companyContact): bool
    {
        return $this->activeChecker->isActive($companyContact);
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
        return $this->activeChecker->isTrashed($companyContact);
    }

    /**
     * Determine whether the user can view the model.
     * Only admins can view company contacts.
     *
     * @param  User $user
     * @param  CompanyContact $contact
     *
     * @return bool
     */
    public function canView(User $user, CompanyContact $contact): bool
    {
        return $this->isAdmin($user) && $this->activeChecker->isActive(
            $contact
        );
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  User $user
     * @param  CompanyContact $contact
     *
     * @return bool
     */
    public function canUpdate(User $user, CompanyContact $contact): bool
    {
        return $this->isAdmin($user) && $this->activeChecker->isActive(
            $contact
        );
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  User $user
     * @param  CompanyContact $contact
     *
     * @return bool
     */
    public function canDelete(User $user, CompanyContact $contact): bool
    {
        return $this->isAdmin($user) && $this->activeChecker->canBeModified(
            $contact
        );
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  User $user
     * @param  CompanyContact $contact
     *
     * @return bool
     */
    public function canRestore(User $user, CompanyContact $contact): bool
    {
        return $this->isAdmin($user) &&
            $this->activeChecker->canBeRestoredOrForceDeleted($contact);
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  User $user
     * @param  CompanyContact $contact
     *
     * @return bool
     */
    public function canForceDelete(User $user, CompanyContact $contact): bool
    {
        return $this->activeChecker->canUserPerformAction(
            $contact,
            'restoreOrForceDelete',
            $user
        );
    }
}
