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
        // Users can update themselves
        if ($user->id === $model->id) {
            return true;
        }

        // Admins can update other users
        if ($user->is_admin || $user->is_super_admin) {
            // Regular admins cannot modify super admins
            if (
                $user->is_admin &&
                ! $user->is_super_admin &&
                $model->is_super_admin
            ) {
                return false;
            }

            return true;
        }

        return false;
    }

    /**
     * Determine if the user can delete the model.
     */
    public function delete(User $user, User $model): bool
    {
        // Users cannot delete themselves
        if ($user->id === $model->id) {
            return false;
        }

        // Only admins and super admins can delete
        if ($user->is_admin || $user->is_super_admin) {
            // Regular admins cannot delete super admins
            if (
                $user->is_admin &&
                ! $user->is_super_admin &&
                $model->is_super_admin
            ) {
                return false;
            }

            return true;
        }

        return false;
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
}
