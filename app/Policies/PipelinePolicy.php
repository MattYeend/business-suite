<?php

namespace App\Policies;

use App\Models\Pipeline;
use App\Models\User;
use App\Services\Pipelines\PipelinePolicyAuthorisationService;

class PipelinePolicy
{
    /**
     * Inject the required service into the policy.
     *
     * @param  PipelinePolicyAuthorisationService $authorisationService
     */
    public function __construct(
        protected PipelinePolicyAuthorisationService $authorisationService
    ) {
    }

    /**
     * Determine whether the user can view any models.
     *
     * Only admins can view the list of pipelines.
     *
     * @param  User $user
     *
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        return $this->authorisationService->isAdmin($user);
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  User $user
     * @param  Pipeline $pipeline
     *
     * @return bool
     */
    public function view(User $user, Pipeline $pipeline): bool
    {
        return $this->authorisationService->canView($user, $pipeline);
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  User $user
     *
     * @return bool
     */
    public function create(User $user): bool
    {
        return $this->authorisationService->isAdmin($user);
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  User $user
     * @param  Pipeline $pipeline
     *
     * @return bool
     */
    public function update(User $user, Pipeline $pipeline): bool
    {
        return $this->authorisationService->canUpdate($user, $pipeline);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  User $user
     * @param  Pipeline $pipeline
     *
     * @return bool
     */
    public function delete(User $user, Pipeline $pipeline): bool
    {
        return $this->authorisationService->canDelete($user, $pipeline);
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  User $user
     * @param  Pipeline $pipeline
     *
     * @return bool
     */
    public function restore(User $user, Pipeline $pipeline): bool
    {
        return $this->authorisationService->canRestore($user, $pipeline);
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  User $user
     * @param  Pipeline $pipeline
     *
     * @return bool True if the user has permission to force delete this pipeline
     */
    public function forceDelete(User $user, Pipeline $pipeline): bool
    {
        return $this->authorisationService->canForceDelete(
            $user,
            $pipeline
        );
    }

    /**
     * Determine whether the user can bulk delete models.
     *
     * @param  User $user
     *
     * @return bool
     */
    public function bulkDelete(User $user): bool
    {
        return $this->authorisationService->isAdmin($user);
    }

    /**
     * Determine whether the user can bulk restore models.
     *
     * @param  User $user
     *
     * @return bool
     */
    public function bulkRestore(User $user): bool
    {
        return $this->authorisationService->isAdmin($user);
    }

    /**
     * Determine whether the user can import models.
     *
     * @param  User $user
     *
     * @return bool
     */
    public function import(User $user): bool
    {
        return $this->authorisationService->isAdmin($user);
    }

    /**
     * Determine whether the user can export models.
     *
     * @param  User $user
     *
     * @return bool
     */
    public function export(User $user): bool
    {
        return $this->authorisationService->isUser($user);
    }
}