<?php

namespace App\Services\Parts;

use App\Models\Part;
use App\Models\User;
use App\Services\UserRoleCheckerService;

class PartActiveCheckerService
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
     * Check if part is active (not soft-deleted).
     *
     * @param  Part $part
     *
     * @return bool
     */
    public function isActive(Part $part): bool
    {
        return ! $part->trashed();
    }

    /**
     * Check if part is soft-deleted.
     *
     * @param  Part $part
     *
     * @return bool
     */
    public function isTrashed(Part $part): bool
    {
        return $part->trashed();
    }

    /**
     * Check if part is active (not soft-deleted) and can be
     * updated/deleted.
     *
     * @param  Part $part
     *
     * @return bool
     */
    public function canBeModified(Part $part): bool
    {
        return $this->isActive($part);
    }

    /**
     * Check if part is soft-deleted and can be restored/force-deleted.
     *
     * @param  Part $part
     *
     * @return bool
     */
    public function canBeRestoredOrForceDeleted(
        Part $part
    ): bool {
        return $this->isTrashed($part);
    }

    /**
     * Check if user can modify part (update/delete) or restore/force-delete
     * part based on its active status.
     *
     * @param  Part $part
     * @param  string $action
     * @param  User $user
     *
     * @return bool
     */
    public function canUserPerformAction(
        Part $part,
        string $action,
        User $user
    ): bool {
        if ($action === 'modify') {
            return $this->roleChecker->isAdmin($user) && $this->canBeModified(
                $part
            );
        }

        if ($action === 'restoreOrForceDelete') {
            return $this->roleChecker->isAdmin($user) &&
                $this->canBeRestoredOrForceDeleted($part);
        }

        throw new \InvalidArgumentException("Invalid action: {$action}");
    }
}
