<?php

namespace App\Services\Pipelines;

use App\Models\Pipeline;
use App\Models\User;
use App\Services\UserRoleCheckerService;

class PipelinePolicyAuthorisationService
{
    /**
     * Inject the required services into the policy authorisation service.
     *
     * @param PipelineActiveCheckerService $activeChecker
     * @param UserRoleCheckerService $roleChecker
     */
    public function __construct(
        protected PipelineActiveCheckerService $activeChecker,
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
     * Check if pipeline is active (not soft-deleted).
     *
     * @param  Pipeline $pipeline
     *
     * @return bool
     */
    public function isActive(Pipeline $pipeline): bool
    {
        return $this->activeChecker->isActive($pipeline);
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
        return $this->activeChecker->isTrashed($pipeline);
    }

    /**
     * Determine whether the user can view the model.
     * Only admins can view company addresses.
     *
     * @param  User $user
     * @param  Pipeline $pipeline
     *
     * @return bool
     */
    public function canView(User $user, Pipeline $pipeline): bool
    {
        return $this->isAdmin($user) && $this->activeChecker->isActive(
            $pipeline
        );
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  User $user
     * @param  Pipeline $pipeline
     *
     * @return bool
     */
    public function canUpdate(User $user, Pipeline $pipeline): bool
    {
        return $this->isAdmin($user) && $this->activeChecker->isActive(
            $pipeline
        );
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  User $user
     * @param  Pipeline $pipeline
     *
     * @return bool
     */
    public function canDelete(User $user, Pipeline $pipeline): bool
    {
        return $this->isAdmin($user) && $this->activeChecker->canBeModified(
            $pipeline
        );
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  User $user
     * @param  Pipeline $pipeline
     *
     * @return bool
     */
    public function canRestore(User $user, Pipeline $pipeline): bool
    {
        return $this->isAdmin($user) &&
            $this->activeChecker->canBeRestoredOrForceDeleted($pipeline);
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  User $user
     * @param  Pipeline $pipeline
     *
     * @return bool
     */
    public function canForceDelete(User $user, Pipeline $pipeline): bool
    {
        return $this->activeChecker->canUserPerformAction(
            $pipeline,
            'restoreOrForceDelete',
            $user
        );
    }
}
