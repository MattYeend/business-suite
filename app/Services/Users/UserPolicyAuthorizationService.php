<?php

namespace App\Services\Users;

use App\Models\User;

/**
 * Handles user authorization checks for policies.
 */
class UserPolicyAuthorizationService
{
    /**
     * Check if user is admin or super admin.
     *
     * @param  User $user
     *
     * @return bool
     */
    public function isAdmin(User $user): bool
    {
        return $user->is_admin || $user->is_super_admin;
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
        return $user->is_admin
            && ! $user->is_super_admin
            && $model->is_super_admin;
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
        if ($this->isSelf($user, $model)) {
            return false;
        }

        if (! $this->isAdmin($user)) {
            return false;
        }

        return ! $this->isRestrictedFromManaging($user, $model);
    }
}
