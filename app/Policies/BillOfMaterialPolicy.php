<?php

namespace App\Policies;

use App\Models\BillOfMaterial;
use App\Models\User;
use App\Services\BillOfMaterials\BOMPolicyAuthorisationService;

class BillOfMaterialPolicy
{
    /**
     * Inject the required service into the policy.
     *
     * @param BOMPolicyAuthorisationService $authorisationService
     */
    public function __construct(
        protected BOMPolicyAuthorisationService $authorisationService
    ) {
        $this->authorisationService = $authorisationService;
    }

    /**
     * Determine whether the user can view any models.
     *
     * Only admins can view the list of Bill Of Materials.
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
     * @param  BillOfMaterial $billOfMaterial
     *
     * @return bool
     */
    public function view(User $user, BillOfMaterial $billOfMaterial): bool
    {
        return $this->authorisationService->canView($user, $billOfMaterial);
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
     * @param  BillOfMaterial $billOfMaterial
     *
     * @return bool
     */
    public function update(User $user, BillOfMaterial $billOfMaterial): bool
    {
        return $this->authorisationService->canUpdate($user, $billOfMaterial);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  User $user
     * @param  BillOfMaterial $billOfMaterial
     *
     * @return bool
     */
    public function delete(User $user, BillOfMaterial $billOfMaterial): bool
    {
        return $this->authorisationService->canDelete($user, $billOfMaterial);
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  User $user
     * @param  BillOfMaterial $billOfMaterial
     *
     * @return bool
     */
    public function restore(User $user, BillOfMaterial $billOfMaterial): bool
    {
        return $this->authorisationService->canRestore($user, $billOfMaterial);
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  User $user
     * @param  BillOfMaterial $billOfMaterial
     *
     * @return bool
     */
    public function forceDelete(
        User $user,
        BillOfMaterial $billOfMaterial
    ): bool {
        return $this->authorisationService->canForceDelete(
            $user,
            $billOfMaterial
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
