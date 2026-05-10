<?php

namespace App\Policies;

use App\Models\Pipeline;
use App\Models\User;
use App\Services\Pipelines\PipelinePolicyAuthorisationService;

class PipelinePolicy
{
    public function __construct(
        protected PipelinePolicyAuthorisationService $authorizationService
    ) {
    }

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $this->authorizationService->isAdmin($user);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Pipeline $pipeline): bool
    {
        return $this->authorizationService->canView($user, $pipeline);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $this->authorizationService->isAdmin($user);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Pipeline $pipeline): bool
    {
        return $this->authorizationService->canUpdate($user, $pipeline);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Pipeline $pipeline): bool
    {
        return $this->authorizationService->canDelete($user, $pipeline);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Pipeline $pipeline): bool
    {
        return $this->authorizationService->canRestore($user, $pipeline);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Pipeline $pipeline): bool
    {
        return $this->authorizationService->canForceDelete(
            $user,
            $pipeline
        );
    }

    /**
     * Determine whether the user can bulk delete models.
     */
    public function bulkDelete(User $user): bool
    {
        return $this->authorizationService->isAdmin($user);
    }

    /**
     * Determine whether the user can bulk restore models.
     */
    public function bulkRestore(User $user): bool
    {
        return $this->authorizationService->isAdmin($user);
    }

    /**
     * Determine whether the user can import models.
     */
    public function import(User $user): bool
    {
        return $this->authorizationService->isAdmin($user);
    }

    /**
     * Determine whether the user can export models.
     */
    public function export(User $user): bool
    {
        return $this->authorizationService->isUser($user);
    }
}
