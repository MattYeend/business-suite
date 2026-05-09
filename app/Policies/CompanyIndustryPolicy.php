<?php

namespace App\Policies;

use App\Models\CompanyIndustry;
use App\Models\User;
use App\Services\CompanyIndustries\CompanyIndustryPolicyAuthorisationService;

class CompanyIndustryPolicy
{
    protected CompanyIndustryPolicyAuthorisationService $authorizationService;

    public function __construct(
        CompanyIndustryPolicyAuthorisationService $authorizationService
    ) {
        $this->authorizationService = $authorizationService;
    }

    /**
     * Determine whether the user can view any models.
     * Only admins can view the list of company industries.
     */
    public function viewAny(User $user): bool
    {
        return $this->authorizationService->isAdmin($user);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, CompanyIndustry $companyIndustry): bool
    {
        return $this->authorizationService->canView($user, $companyIndustry);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $this->authorizationService->isAdmin($user);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, CompanyIndustry $companyIndustry): bool
    {
        return $this->authorizationService->canUpdate($user, $companyIndustry);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, CompanyIndustry $companyIndustry): bool
    {
        return $this->authorizationService->canDelete($user, $companyIndustry);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, CompanyIndustry $companyIndustry): bool
    {
        return $this->authorizationService->canRestore($user, $companyIndustry);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(
        User $user,
        CompanyIndustry $companyIndustry
    ): bool {
        return $this->authorizationService->canForceDelete(
            $user,
            $companyIndustry
        );
    }

    /**
     * Determine whether the user can bulk delete models.
     */
    public function bulkDelete(User $user): bool
    {
        return $this->authorizationService->isAdmin($user);
    }

    /**
     * Determine whether the user can bulk restore models.
     */
    public function bulkRestore(User $user): bool
    {
        return $this->authorizationService->isAdmin($user);
    }

    /**
     * Determine whether the user can import models.
     */
    public function import(User $user): bool
    {
        return $this->authorizationService->isAdmin($user);
    }

    /**
     * Determine whether the user can export models.
     */
    public function export(User $user): bool
    {
        return $this->authorizationService->isUser($user);
    }
}
