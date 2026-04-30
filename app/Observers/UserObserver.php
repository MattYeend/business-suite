<?php

namespace App\Observers;

use App\Models\User;

class UserObserver
{
    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        $this->syncRoleWithFlags($user);
    }

    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {
        // Only sync if role flags changed
        if ($user->wasChanged(['is_user', 'is_admin', 'is_super_admin'])) {
            $this->syncRoleWithFlags($user);
        }
    }

    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user): void
    {
        //
    }

    /**
     * Handle the User "restored" event.
     */
    public function restored(User $user): void
    {
        //
    }

    /**
     * Handle the User "force deleted" event.
     */
    public function forceDeleted(User $user): void
    {
        //
    }

    /**
     * Sync user's Spatie role based on boolean flags.
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
}
