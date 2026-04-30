<?php

namespace App\Observers;

use App\Models\User;

class UserObserver
{
    /**
     * Handle the User "created" event.
     *
     * @param  User $user
     *
     * @return void
     */
    public function created(User $user): void
    {
        $this->syncBaseRoleWithFlags($user);
    }

    /**
     * Handle the User "updated" event.
     *
     * @param  User $user
     *
     * @return void
     */
    public function updated(User $user): void
    {
        // Only sync if role flags changed
        if ($user->wasChanged(['is_user', 'is_admin', 'is_super_admin'])) {
            $this->syncBaseRoleWithFlags($user);
        }
    }

    /**
     * Handle the User "deleted" event.
     *
     * @param  User $user
     *
     * @return void
     */
    public function deleted(User $user): void
    {
        //
    }

    /**
     * Handle the User "restored" event.
     *
     * @param  User $user
     *
     * @return void
     */
    public function restored(User $user): void
    {
        //
    }

    /**
     * Handle the User "force deleted" event.
     *
     * @param  User $user
     *
     * @return void
     */
    public function forceDeleted(User $user): void
    {
        //
    }

    /**
     * Sync user's Spatie role based on boolean flags.
     *
     * @param  User $user
     *
     * @return void
     */
    private function syncRoleWithFlags(User $user): void
    {
        if ($user->is_super_admin) {
            $user->syncRoles(['super-admin']);
        } elseif ($user->is_admin) {
            $user->syncRoles(['admin']);
        } elseif ($user->is_user) {
            $user->syncRoles(['user']);
        } else {
            $user->syncRoles([]); // Remove all roles
        }
    }

    /**
     * Sync user's base Spatie role based on boolean flags.
     * This ADDS the base role without removing specialised roles.
     *
     * @param  User $user
     *
     * @return void
     */
    private function syncBaseRoleWithFlags(User $user): void
    {
        // Get current roles
        $currentRoles = $user->roles->pluck('name')->toArray();
        
        // Determine base role
        $baseRole = null;
        if ($user->is_super_admin) {
            $baseRole = 'super-admin';
        } elseif ($user->is_admin) {
            $baseRole = 'admin';
        } elseif ($user->is_user) {
            $baseRole = 'user';
        }

        // Remove any existing base roles (super-admin, admin, user)
        $baseRoles = ['super-admin', 'admin', 'user'];
        $specialisedRoles = array_diff($currentRoles, $baseRoles);
        
        // Sync: keep specialised roles + add new base role
        if ($baseRole) {
            $user->syncRoles(array_merge([$baseRole], $specialisedRoles));
        } else {
            $user->syncRoles($specialisedRoles); // Keep only specialised roles
        }
    }
}
