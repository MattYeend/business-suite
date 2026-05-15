<?php

namespace App\Services\Users;

use App\Models\User;
use App\Services\UserRoleCheckerService;

class UserActiveCheckerService
{
    /**
     * Inject the required services into the active checker service.
     *
     * @param UserRoleCheckerService $roleChecker
     */
    public function __construct(
        protected UserRoleCheckerService $roleChecker
    ) {
    }

    /**
     * Check if user is active (not soft-deleted).
     *
     * @param  User $user
     *
     * @return bool
     */
    public function isActive(User $user): bool
    {
        return ! $user->trashed();
    }

    /**
     * Check if user is soft-deleted.
     *
     * @param  User $user
     *
     * @return bool
     */
    public function isTrashed(User $user): bool
    {
        return $user->trashed();
    }

    /**
     * Check if user is active (not soft-deleted) and can be
     * updated/deleted.
     *
     * @param  User $user
     *
     * @return bool
     */
    public function canBeModified(User $user): bool
    {
        return $this->isActive($user);
    }

    /**
     * Check if user is soft-deleted and can be restored/force-deleted.
     *
     * @param  User $user
     *
     * @return bool
     */
    public function canBeRestoredOrForceDeleted(
        User $user
    ): bool {
        return $this->isTrashed($user);
    }

    /**
     * Determine whether an acting user can perform a given action on
     * a target user.
     *
     * @param  User $actor
     * @param  string $action
     * @param  User $model
     *
     * @return bool
     *
     * @throws \InvalidArgumentException If the action is not supported.
     */
    public function canUserPerformAction(
        User $actor,
        string $action,
        User $model
    ): bool {
        if ($action === 'modify') {
            return $this->roleChecker->isAdmin($actor)
                && $this->canBeModified($model);
        }

        if ($action === 'restoreOrForceDelete') {
            return $this->roleChecker->isAdmin($actor)
                && $this->canBeRestoredOrForceDeleted($model);
        }

        throw new \InvalidArgumentException("Invalid action: {$action}");
    }
}
