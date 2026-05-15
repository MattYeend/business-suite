<?php

namespace App\Services\PipelineStages;

use App\Models\PipelineStage;
use App\Models\User;
use App\Services\UserRoleCheckerService;

class PipelineStagePolicyAuthorisationService
{
    /**
     * Inject the required services into the policy authorisation service.
     *
     * @param PipelineStageActiveCheckerService $activeChecker
     * @param UserRoleCheckerService $roleChecker
     */
    public function __construct(
        protected PipelineStageActiveCheckerService $activeChecker,
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
     * Check if stage is active (not soft-deleted).
     *
     * @param  PipelineStage $pipelineStage
     *
     * @return bool
     */
    public function isActive(PipelineStage $pipelineStage): bool
    {
        return $this->activeChecker->isActive($pipelineStage);
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
        return $this->activeChecker->isTrashed($pipelineStage);
    }

    /**
     * Determine whether the user can view the model.
     * Only admins can view pipeline stages.
     *
     * @param  User $user
     * @param  PipelineStage $stage
     *
     * @return bool
     */
    public function canView(User $user, PipelineStage $stage): bool
    {
        return $this->isAdmin($user) && $this->activeChecker->isActive(
            $stage
        );
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  User $user
     * @param  PipelineStage $stage
     *
     * @return bool
     */
    public function canUpdate(User $user, PipelineStage $stage): bool
    {
        return $this->isAdmin($user) && $this->activeChecker->isActive(
            $stage
        );
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  User $user
     * @param  PipelineStage $stage
     *
     * @return bool
     */
    public function canDelete(User $user, PipelineStage $stage): bool
    {
        return $this->isAdmin($user) && $this->activeChecker->canBeModified(
            $stage
        );
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  User $user
     * @param  PipelineStage $stage
     *
     * @return bool
     */
    public function canRestore(User $user, PipelineStage $stage): bool
    {
        return $this->isAdmin($user) &&
            $this->activeChecker->canBeRestoredOrForceDeleted($stage);
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  User $user
     * @param  PipelineStage $stage
     *
     * @return bool
     */
    public function canForceDelete(User $user, PipelineStage $stage): bool
    {
        return $this->activeChecker->canUserPerformAction(
            $stage,
            'restoreOrForceDelete',
            $user
        );
    }
}
