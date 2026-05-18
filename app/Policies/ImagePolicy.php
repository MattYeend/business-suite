<?php

namespace App\Policies;

use App\Models\Image;
use App\Models\User;
use App\Services\Images\ImagePolicyAuthorisationService;

class ImagePolicy
{
    /**
     * The authorisation service handling permission checks.
     *
     * @var ImagePolicyAuthorisationService
     */
    protected ImagePolicyAuthorisationService $authorisationService;

    /**
     * Inject the required service into the policy.
     *
     * @param  ImagePolicyAuthorisationService $authorisationService
     */
    public function __construct(
        ImagePolicyAuthorisationService $authorisationService
    ) {
        $this->authorisationService = $authorisationService;
    }

    /**
     * Determine whether the user can view any models.
     *
     * Only admins can view the list of companies.
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
     * @param  User$user
     * @param  Image $company
     *
     * @return bool
     */
    public function view(User $user, Image $company): bool
    {
        return $this->authorisationService->canView($user, $company);
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
     * @param  Image $company
     *
     * @return bool
     */
    public function update(User $user, Image $company): bool
    {
        return $this->authorisationService->canUpdate($user, $company);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  User $user
     * @param  Image $company
     *
     * @return bool
     */
    public function delete(User $user, Image $company): bool
    {
        return $this->authorisationService->canDelete($user, $company);
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  User $user
     * @param  Image $company
     *
     * @return bool
     */
    public function restore(User $user, Image $company): bool
    {
        return $this->authorisationService->canRestore($user, $company);
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  User $user
     * @param  Image $company
     *
     * @return bool
     */
    public function forceDelete(User $user, Image $company): bool
    {
        return $this->authorisationService->canForceDelete(
            $user,
            $company
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
