<?php

namespace App\Policies;

use App\Models\CompanyIndustry;
use App\Models\User;
use App\Services\CompanyIndustries\CompanyIndustryPolicyAuthorisationService;

class CompanyIndustryPolicy
{
    /**
     * The authorization service handling permission checks.
     *
     * @var CompanyIndustryPolicyAuthorisationService
     */
    protected CompanyIndustryPolicyAuthorisationService $authorisationService;

    /**
     * Inject the required service into the policy.
     *
     * @param CompanyIndustryPolicyAuthorisationService $authorisationService
     */
    public function __construct(
        CompanyIndustryPolicyAuthorisationService $authorisationService
    ) {
        $this->authorisationService = $authorisationService;
    }

    /**
     * Determine whether the user can view any models.
     *
     * Only admins can view the list of company industries.
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
     * @param  CompanyIndustry $companyIndustry
     *
     * @return bool
     */
    public function view(User $user, CompanyIndustry $companyIndustry): bool
    {
        return $this->authorisationService->canView($user, $companyIndustry);
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
     * @param  CompanyIndustry $companyIndustry
     *
     * @return bool
     */
    public function update(User $user, CompanyIndustry $companyIndustry): bool
    {
        return $this->authorisationService->canUpdate($user, $companyIndustry);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  User $user
     * @param  CompanyIndustry $companyIndustry
     *
     * @return bool
     */
    public function delete(User $user, CompanyIndustry $companyIndustry): bool
    {
        return $this->authorisationService->canDelete($user, $companyIndustry);
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  User $user
     * @param  CompanyIndustry $companyIndustry
     *
     * @return bool
     */
    public function restore(User $user, CompanyIndustry $companyIndustry): bool
    {
        return $this->authorisationService->canRestore($user, $companyIndustry);
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  User $user
     * @param  CompanyIndustry $companyIndustry
     *
     * @return bool
     */
    public function forceDelete(
        User $user,
        CompanyIndustry $companyIndustry
    ): bool {
        return $this->authorisationService->canForceDelete(
            $user,
            $companyIndustry
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
