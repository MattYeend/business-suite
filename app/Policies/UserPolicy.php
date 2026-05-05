<?php

namespace App\Policies;

use App\Models\User;
use App\Services\Users\UserPolicyAuthorizationService;

class UserPolicy
{
    public function __construct(
        protected UserPolicyAuthorizationService $authorizationService
    ) {
    }

    /**
     * Determine if the user can view any users.
     */
    public function viewAny(User $user): bool
    {
        return $this->authorizationService->isAdmin($user);
    }

    /**
     * Determine if the user can view the model.
     */
    public function view(User $user, User $model): bool
    {
        if ($this->authorizationService->isSelf($user, $model)) {
            return true;
        }

        return $this->authorizationService->isAdmin($user);
    }

    /**
     * Determine if the user can create users.
     */
    public function create(User $user): bool
    {
        return $this->authorizationService->isAdmin($user);
    }

    /**
     * Determine if the user can update the model.
     */
    public function update(User $user, User $model): bool
    {
        if ($this->authorizationService->isSelf($user, $model)) {
            return true;
        }

        return $this->authorizationService->isAdmin($user)
            && ! $this->authorizationService->isRestrictedFromManaging(
                $user,
                $model
            );
    }

    /**
     * Determine if the user can delete the model.
     */
    public function delete(User $user, User $model): bool
    {
        return $this->authorizationService->canManage($user, $model);
    }

    /**
     * Determine if the user can restore the model.
     */
    public function restore(User $user, User $model): bool
    {
        return $this->authorizationService->isAdmin($user)
        && ! $this->authorizationService->isRestrictedFromManaging(
            $user,
            $model
        );
    }

    /**
     * Determine if the user can permanently delete the model.
     */
    public function forceDelete(User $user, User $model): bool
    {
        return ! $this->authorizationService->isRestrictedFromManaging(
            $user,
            $model
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
