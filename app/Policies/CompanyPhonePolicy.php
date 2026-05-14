<?php

namespace App\Policies;

use App\Models\CompanyPhone;
use App\Models\User;
use App\Services\CompanyPhones\CompanyPhonePolicyAuthorisationService;

class CompanyPhonePolicy
{
    /**
     * The authorisation service handling permission checks.
     *
     * @var CompanyPhonePolicyAuthorisationService
     */
    protected CompanyPhonePolicyAuthorisationService $authorisationService;

    /**
     * Inject the required service into the policy.
     *
     * @param  CompanyPhonePolicyAuthorisationService $authorisationService
     */
    public function __construct(
        CompanyPhonePolicyAuthorisationService $authorisationService
    ) {
        $this->authorisationService = $authorisationService;
    }

    /**
     * Determine whether the user can view any models.
     *
     * Only admins can view the list of company phones.
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
     * @param  CompanyPhone $companyPhone
     *
     * @return bool
     */
    public function view(User $user, CompanyPhone $companyPhone): bool
    {
        return $this->authorisationService->canView($user, $companyPhone);
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
     * @param  CompanyPhone $companyPhone
     *
     * @return bool True if the user has permission to update this phone
     */
    public function update(User $user, CompanyPhone $companyPhone): bool
    {
        return $this->authorisationService->canUpdate($user, $companyPhone);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  User $user
     * @param  CompanyPhone $companyPhone
     *
     * @return bool
     */
    public function delete(User $user, CompanyPhone $companyPhone): bool
    {
        return $this->authorisationService->canDelete($user, $companyPhone);
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  User $user
     * @param  CompanyPhone $companyPhone
     *
     * @return bool
     */
    public function restore(User $user, CompanyPhone $companyPhone): bool
    {
        return $this->authorisationService->canRestore($user, $companyPhone);
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  User $user
     * @param  CompanyPhone $companyPhone
     *
     * @return bool
     */
    public function forceDelete(User $user, CompanyPhone $companyPhone): bool
    {
        return $this->authorisationService->canForceDelete(
            $user,
            $companyPhone
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
