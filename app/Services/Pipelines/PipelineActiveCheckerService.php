<?php

namespace App\Services\Pipelines;

use App\Models\Pipeline;
use App\Models\User;
use App\Services\UserRoleCheckerService;

class PipelineActiveCheckerService
{
    public function __construct(
        protected UserRoleCheckerService $roleChecker
    ) {
    }

    /**
     * Check if pipeline is active (not soft-deleted).
     *
     * @param  Pipeline $pipeline
     *
     * @return bool
     */
    public function isActive(Pipeline $pipeline): bool
    {
        return ! $pipeline->trashed();
    }

    /**
     * Check if pipeline is soft-deleted.
     *
     * @param  Pipeline $pipeline
     *
     * @return bool
     */
    public function isTrashed(Pipeline $pipeline): bool
    {
        return $pipeline->trashed();
    }

    /**
     * Check if pipeline is active (not soft-deleted) and can be
     * updated/deleted.
     *
     * @param  Pipeline $pipeline
     *
     * @return bool
     */
    public function canBeModified(Pipeline $pipeline): bool
    {
        return $this->isActive($pipeline);
    }

    /**
     * Check if pipeline is soft-deleted and can be restored/force-deleted.
     *
     * @param  Pipeline $pipeline
     *
     * @return bool
     */
    public function canBeRestoredOrForceDeleted(
        Pipeline $pipeline
    ): bool {
        return $this->isTrashed($pipeline);
    }

    /**
     * Check if user can modify pipeline (update/delete) or restore/force-delete
     * pipeline based on its active status.
     *
     * @param  Pipeline $pipeline
     * @param  string $action The action being checked, either 'modify' or
     * 'restoreOrForceDelete'.
     * @param  User $user The user performing the action, used for admin check
     * in the callback.
     *
     * @return bool
     */
    public function canUserPerformAction(
        Pipeline $pipeline,
        string $action,
        User $user
    ): bool {
        if ($action === 'modify') {
            return $this->roleChecker->isAdmin($user) && $this->canBeModified(
                $pipeline
            );
        }

        if ($action === 'restoreOrForceDelete') {
            return $this->roleChecker->isAdmin($user) &&
                $this->canBeRestoredOrForceDeleted($pipeline);
        }

        throw new \InvalidArgumentException("Invalid action: {$action}");
    }
}
