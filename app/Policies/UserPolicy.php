<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /**
     * Determine if the user can view any users.
     */
    public function viewAny(User $user): bool
    {
        return $user->is_admin || $user->is_super_admin;
    }

    /**
     * Determine if the user can view the model.
     */
    public function view(User $user, User $model): bool
    {
        // Users can view themselves
        if ($user->id === $model->id) {
            return true;
        }

        // Admins can view all users
        return $user->is_admin || $user->is_super_admin;
    }

    /**
     * Determine if the user can create users.
     */
    public function create(User $user): bool
    {
        return $user->is_admin || $user->is_super_admin;
    }

    /**
     * Determine if the user can update the model.
     */
    public function update(User $user, User $model): bool
    {
        if ($this->isSelf($user, $model)) {
            return true;
        }

        if (! $this->isAdmin($user)) {
            return false;
        }

        return ! $this->isRestrictedFromManaging($user, $model);
    }

    /**
     * Determine if the user can delete the model.
     */
    public function delete(User $user, User $model): bool
    {
        if ($this->isSelf($user, $model)) {
            return false;
        }

        if (! $this->isAdmin($user)) {
            return false;
        }

        return ! $this->isRestrictedFromManaging($user, $model);
    }

    /**
     * Determine if the user can restore the model.
     */
    public function restore(User $user): bool
    {
        return $user->is_admin || $user->is_super_admin;
    }

    /**
     * Determine if the user can permanently delete the model.
     */
    public function forceDelete(User $user): bool
    {
        // Only super admins can force delete
        return $user->is_super_admin;
    }

    /**
     * Determine if the user is an admin or super admin.
     */
    private function isAdmin(User $user): bool
    {
        return $user->is_admin || $user->is_super_admin;
    }

    /**
     * Determine if the user is trying to perform an action on themselves.
     */
    private function isSelf(User $user, User $model): bool
    {
        return $user->id === $model->id;
    }

    /**
     * Determine if the user is a regular admin trying to manage a super admin.
     */
    private function isRestrictedFromManaging(User $user, User $model): bool
    {
        return $user->is_admin
            && ! $user->is_super_admin
            && $model->is_super_admin;
    }
}
