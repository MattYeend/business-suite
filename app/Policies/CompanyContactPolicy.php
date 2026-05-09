<?php

namespace App\Policies;

use App\Models\CompanyContact;
use App\Models\User;
use App\Services\CompanyContacts\CompanyContactPolicyAuthorisationService;

class CompanyContactPolicy
{
    protected CompanyContactPolicyAuthorisationService $authorizationService;

    public function __construct(
        CompanyContactPolicyAuthorisationService $authorizationService
    ) {
        $this->authorizationService = $authorizationService;
    }

    /**
     * Determine whether the user can view any models.
     * Only admins can view the list of company contacts.
     */
    public function viewAny(User $user): bool
    {
        return $this->authorizationService->isAdmin($user);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, CompanyContact $companyContact): bool
    {
        return $this->authorizationService->canView($user, $companyContact);
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
    public function update(User $user, CompanyContact $companyContact): bool
    {
        return $this->authorizationService->canUpdate($user, $companyContact);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, CompanyContact $companyContact): bool
    {
        return $this->authorizationService->canDelete($user, $companyContact);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, CompanyContact $companyContact): bool
    {
        return $this->authorizationService->canRestore($user, $companyContact);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(
        User $user,
        CompanyContact $companyContact
    ): bool {
        return $this->authorizationService->canForceDelete(
            $user,
            $companyContact
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
