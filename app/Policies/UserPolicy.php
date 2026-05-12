<?php

namespace App\Policies;

use App\Models\User;
use App\Services\Users\UserPolicyAuthorisationService;

class UserPolicy
{
    public function __construct(
        protected UserPolicyAuthorisationService $authorisationService
    ) {
    }

    /**
     * Determine if the user can view any users.
     */
    public function viewAny(User $user): bool
    {
        return $this->authorisationService->isAdmin($user);
    }

    /**
     * Determine if the user can view the model.
     */
    public function view(User $user, User $model): bool
    {
        if ($this->authorisationService->isSelf($user, $model)) {
            return true;
        }

        return $this->authorisationService->isAdmin($user);
    }

    /**
     * Determine if the user can create users.
     */
    public function create(User $user): bool
    {
        return $this->authorisationService->isAdmin($user);
    }

    /**
     * Determine if the user can update the model.
     */
    public function update(User $user, User $model): bool
    {
        if ($this->authorisationService->isSelf($user, $model)) {
            return true;
        }

        return $this->authorisationService->isAdmin($user)
            && ! $this->authorisationService->isRestrictedFromManaging(
                $user,
                $model
            );
    }

    /**
     * Determine if the user can delete the model.
     */
    public function delete(User $user, User $model): bool
    {
        return $this->authorisationService->canManage($user, $model);
    }

    /**
     * Determine if the user can restore the model.
     */
    public function restore(User $user, User $model): bool
    {
        return $this->authorisationService->canRestore(
            $user,
            $model
        );
    }

    /**
     * Determine if the user can permanently delete the model.
     */
    public function forceDelete(User $user, User $model): bool
    {
        return $this->authorisationService->canForceDelete(
            $user,
            $model
        );
    }

    /**
     * Determine whether the user can bulk delete models.
     */
    public function bulkDelete(User $user): bool
    {
        return $this->authorisationService->isAdmin($user);
    }

    /**
     * Determine whether the user can bulk restore models.
     */
    public function bulkRestore(User $user): bool
    {
        return $this->authorisationService->isAdmin($user);
    }

    /**
     * Determine whether the user can import models.
     */
    public function import(User $user): bool
    {
        return $this->authorisationService->isAdmin($user);
    }

    /**
     * Determine whether the user can export models.
     */
    public function export(User $user): bool
    {
        return $this->authorisationService->isUser($user);
    }
}
