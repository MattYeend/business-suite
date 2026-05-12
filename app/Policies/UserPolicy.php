<?php

namespace App\Policies;

use App\Models\User;
use App\Services\Users\UserPolicyAuthorisationService;

class UserPolicy
{
    /**
     * Inject the required service into the policy.
     *
     * @param  UserPolicyAuthorisationService $authorisationService
     */
    public function __construct(
        protected UserPolicyAuthorisationService $authorisationService
    ) {
    }

    /**
     * Determine if the user can view any users.
     *
     * Only admins can view the list of users.
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
     * Determine if the user can view the model.
     *
     * Users can view their own profile or, if admin, any user's profile.
     *
     * @param  User $user
     * @param  User $model
     *
     * @return bool
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
     * Determine if the user can update the model.
     *
     * Users can update their own profile. Admins can update other users
     * unless the target user is restricted from management (e.g., super-admin).
     *
     * @param  User $user
     * @param  User $model
     *
     * @return bool
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
     *
     * Admins can delete users unless restricted by role hierarchy.
     *
     * @param  User $user
     * @param  User $model
     *
     * @return bool
     */
    public function delete(User $user, User $model): bool
    {
        return $this->authorisationService->canManage($user, $model);
    }

    /**
     * Determine if the user can restore the model.
     *
     * @param  User $user
     * @param  User $model
     *
     * @return bool
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
     *
     * @param  User $user
     * @param  User $model
     *
     * @return bool
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
