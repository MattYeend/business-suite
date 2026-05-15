<?php

namespace App\Services\PipelineStages;

use App\Models\PipelineStage;
use App\Models\User;
use App\Services\UserRoleCheckerService;

class PipelineStageActiveCheckerService
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
     * Check if stage is active (not soft-deleted).
     *
     * @param  PipelineStage $pipelineStage
     *
     * @return bool
     */
    public function isActive(PipelineStage $pipelineStage): bool
    {
        return ! $pipelineStage->trashed();
    }

    /**
     * Check if stage is soft-deleted.
     *
     * @param  PipelineStage $pipelineStage
     *
     * @return bool
     */
    public function isTrashed(PipelineStage $pipelineStage): bool
    {
        return $pipelineStage->trashed();
    }

    /**
     * Check if stage is active (not soft-deleted) and can be
     * updated/deleted.
     *
     * @param  PipelineStage $pipelineStage
     *
     * @return bool
     */
    public function canBeModified(PipelineStage $pipelineStage): bool
    {
        return $this->isActive($pipelineStage);
    }

    /**
     * Check if stage is soft-deleted and can be restored/force-deleted.
     *
     * @param  PipelineStage $pipelineStage
     *
     * @return bool
     */
    public function canBeRestoredOrForceDeleted(
        PipelineStage $pipelineStage
    ): bool {
        return $this->isTrashed($pipelineStage);
    }

    /**
     * Check if user can modify stage (update/delete) or restore/force-delete
     * stage based on its active status.
     *
     * @param  PipelineStage $pipelineStage
     * @param  string $action The action being checked, either 'modify' or
     * 'restoreOrForceDelete'.
     * @param  User $user The user performing the action, used for admin check
     * in the callback.
     *
     * @return bool
     */
    public function canUserPerformAction(
        PipelineStage $pipelineStage,
        string $action,
        User $user
    ): bool {
        if ($action === 'modify') {
            return $this->roleChecker->isAdmin($user) && $this->canBeModified(
                $pipelineStage
            );
        }

        if ($action === 'restoreOrForceDelete') {
            return $this->roleChecker->isAdmin($user) &&
                $this->canBeRestoredOrForceDeleted($pipelineStage);
        }

        throw new \InvalidArgumentException("Invalid action: {$action}");
    }
}
