<?php

namespace App\Policies;

use App\Models\CompanyContact;
use App\Models\User;
use App\Services\CompanyContacts\CompanyContactPolicyAuthorisationService;

class CompanyContactPolicy
{
    /**
     * The authorisation service handling permission checks.
     *
     * @var CompanyContactPolicyAuthorisationService
     */
    protected CompanyContactPolicyAuthorisationService $authorisationService;

    /**
     * Inject the required service into the policy.
     *
     * @param CompanyContactPolicyAuthorisationService $authorisationService
     */
    public function __construct(
        CompanyContactPolicyAuthorisationService $authorisationService
    ) {
        $this->authorisationService = $authorisationService;
    }

    /**
     * Determine whether the user can view any models.
     *
     * Only admins can view the list of company contacts.
     *
     * @param  User $user
     *
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        return $this->authorisationService->isAdmin($user);
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  User $user
     * @param  CompanyContact $companyContact
     *
     * @return bool
     */
    public function view(User $user, CompanyContact $companyContact): bool
    {
        return $this->authorisationService->canView($user, $companyContact);
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  User $user
     *
     * @return bool
     */
    public function create(User $user): bool
    {
        return $this->authorisationService->isAdmin($user);
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  User $user
     * @param  CompanyContact $companyContact
     *
     * @return bool
     */
    public function update(User $user, CompanyContact $companyContact): bool
    {
        return $this->authorisationService->canUpdate($user, $companyContact);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  User $user
     * @param  CompanyContact $companyContact
     *
     * @return bool
     */
    public function delete(User $user, CompanyContact $companyContact): bool
    {
        return $this->authorisationService->canDelete($user, $companyContact);
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  User $user
     * @param  CompanyContact $companyContact
     *
     * @return bool
     */
    public function restore(User $user, CompanyContact $companyContact): bool
    {
        return $this->authorisationService->canRestore($user, $companyContact);
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  User $user
     * @param  CompanyContact $companyContact
     *
     * @return bool
     */
    public function forceDelete(
        User $user,
        CompanyContact $companyContact
    ): bool {
        return $this->authorisationService->canForceDelete(
            $user,
            $companyContact
        );
    }

    /**
     * Determine whether the user can bulk delete models.
     *
     * @param  User $user
     *
     * @return bool
     */
    public function bulkDelete(User $user): bool
    {
        return $this->authorisationService->isAdmin($user);
    }

    /**
     * Determine whether the user can bulk restore models.
     *
     * @param  User $user
     *
     * @return bool
     */
    public function bulkRestore(User $user): bool
    {
        return $this->authorisationService->isAdmin($user);
    }

    /**
     * Determine whether the user can import models.
     *
     * @param  User $user
     *
     * @return bool
     */
    public function import(User $user): bool
    {
        return $this->authorisationService->isAdmin($user);
    }

    /**
     * Determine whether the user can export models.
     *
     * @param  User $user
     *
     * @return bool
     */
    public function export(User $user): bool
    {
        return $this->authorisationService->isUser($user);
    }
}
