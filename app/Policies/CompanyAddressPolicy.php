<?php

namespace App\Policies;

use App\Models\CompanyAddress;
use App\Models\User;
use App\Services\CompanyAddresses\CompanyAddressPolicyAuthorisationService;

class CompanyAddressPolicy
{
    /**
     * The authorisation service handling permission checks.
     *
     * @var CompanyAddressPolicyAuthorisationService
     */
    protected CompanyAddressPolicyAuthorisationService $authorisationService;

    /**
     * Inject the required service into the policy.
     *
     * @param CompanyAddressPolicyAuthorisationService $authorisationService
     */
    public function __construct(
        CompanyAddressPolicyAuthorisationService $authorisationService
    ) {
        $this->authorisationService = $authorisationService;
    }

    /**
     * Determine whether the user can view any models.
     *
     * Only admins can view the list of company addresses.
     *
     * @param  User $user The user attempting the action
     *
     * @return bool True if the user is an admin
     */
    public function viewAny(User $user): bool
    {
        return $this->authorisationService->isAdmin($user);
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  User $user
     * @param  CompanyAddress $companyAddress
     *
     * @return bool
     */
    public function view(User $user, CompanyAddress $companyAddress): bool
    {
        return $this->authorisationService->canView($user, $companyAddress);
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
     * @param  CompanyAddress $companyAddress
     *
     * @return bool
     */
    public function update(User $user, CompanyAddress $companyAddress): bool
    {
        return $this->authorisationService->canUpdate($user, $companyAddress);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  User $user
     * @param  CompanyAddress $companyAddress
     *
     * @return bool
     */
    public function delete(User $user, CompanyAddress $companyAddress): bool
    {
        return $this->authorisationService->canDelete($user, $companyAddress);
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  User $user
     * @param  CompanyAddress $companyAddress
     *
     * @return bool
     */
    public function restore(User $user, CompanyAddress $companyAddress): bool
    {
        return $this->authorisationService->canRestore($user, $companyAddress);
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  User $user
     * @param  CompanyAddress $companyAddress
     *
     * @return bool
     */
    public function forceDelete(
        User $user,
        CompanyAddress $companyAddress
    ): bool {
        return $this->authorisationService->canForceDelete(
            $user,
            $companyAddress
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
