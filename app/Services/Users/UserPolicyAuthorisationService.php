<?php

namespace App\Services\Users;

use App\Models\User;
use App\Services\UserRoleCheckerService;

/**
 * Handles user authorization checks for policies.
 */
class UserPolicyAuthorisationService
{
    public function __construct(
        protected UserActiveCheckerService $activeChecker,
        protected UserRoleCheckerService $roleChecker
    ) {
    }

    /**
     * Check if user is a regular user, admin, or super admin.
     *
     * @param  User $user
     *
     * @return bool
     */
    public function isUser(User $user): bool
    {
        return $this->roleChecker->isUser($user);
    }

    /**
     * Check if user is admin or super admin.
     *
     * @param  User $user
     *
     * @return bool
     */
    public function isAdmin(User $user): bool
    {
        return $this->roleChecker->isAdmin($user);
    }

    /**
     * Check if the user is a super admin.
     *
     * @param  User $user
     *
     * @return bool
     */
    public function isSuperAdmin(User $user): bool
    {
        return $this->roleChecker->isSuperAdmin($user);
    }

    /**
     * Check if admin is restricted from managing the target user.
     *
     * Regular admins cannot manage super admins.
     *
     * @param  User $user
     * @param  User $model
     *
     * @return bool
     */
    public function isRestrictedFromManaging(User $user, User $model): bool
    {
        return $this->roleChecker->isRestrictedFromManaging($user, $model);
    }

    /**
     * Check if user is trying to perform action on themselves.
     *
     * @param  User $user
     * @param  User $model
     *
     * @return bool
     */
    public function isSelf(User $user, User $model): bool
    {
        return $user->id === $model->id;
    }

    /**
     * Check if user can manage the target user.
     *
     * @param  User $user
     * @param  User $model
     *
     * @return bool
     */
    public function canManage(User $user, User $model): bool
    {
        return ! $this->isSelf($user, $model)
            && $this->roleChecker->isAdmin($user)
            && ! $this->roleChecker->isRestrictedFromManaging($user, $model);
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  User $user
     * @param  User $model
     *
     * @return bool
     */
    public function canRestore(User $user, User $model): bool
    {
        return $this->isAdmin($user)
            && $this->activeChecker->isTrashed($model);
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  User $user
     * @param  User $model
     *
     * @return bool
     */
    public function canForceDelete(User $user, User $model): bool
    {
        return $this->activeChecker->canUserPerformAction(
            $user,
            'restoreOrForceDelete',
            $model
        );
    }
}
