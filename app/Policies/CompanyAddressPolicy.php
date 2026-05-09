<?php

namespace App\Policies;

use App\Models\CompanyAddress;
use App\Models\User;
use App\Services\CompanyAddresses\CompanyAddressPolicyAuthorizationService;

class CompanyAddressPolicy
{
    protected CompanyAddressPolicyAuthorizationService $authorizationService;

    public function __construct(
        CompanyAddressPolicyAuthorizationService $authorizationService
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
    public function view(User $user, CompanyAddress $companyAddress): bool
    {
        return $this->authorizationService->canView($user, $companyAddress);
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
    public function update(User $user, CompanyAddress $companyAddress): bool
    {
        return $this->authorizationService->canUpdate($user, $companyAddress);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, CompanyAddress $companyAddress): bool
    {
        return $this->authorizationService->canDelete($user, $companyAddress);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, CompanyAddress $companyAddress): bool
    {
        return $this->authorizationService->canRestore($user, $companyAddress);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(
        User $user,
        CompanyAddress $companyAddress
    ): bool {
        return $this->authorizationService->canForceDelete(
            $user,
            $companyAddress
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
