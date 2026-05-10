<?php

namespace App\Services\Pipelines;

use App\Http\Requests\StorePipelineRequest;
use App\Http\Requests\UpdatePipelineRequest;
use App\Models\Pipeline;
use App\Models\User;

/**
 * Orchestrates pipeline lifecycle operations by delegating to focused
 * sub-services.
 *
 * Acts as the single entry point for pipeline create, update, delete,
 * and restore operations, keeping controllers decoupled from the underlying
 * service implementations.
 */

class PipelineManagementService
{
    /**
     * Inject the required services into the management service.
     *
     * @param  PipelineCreatorService $creator Handles pipeline
     * creation.
     * @param  PipelineUpdaterService $updater Handles pipeline
     * updates.
     * @param  PipelineDeleterService $destructor Handles
     * pipeline deletion.
     * @param  PipelineRestorerService $restorer Handles pipeline
     * restoration.
     *
     * @return void
     */
    public function __construct(
        protected PipelineCreatorService $creator,
        protected PipelineUpdaterService $updater,
        protected PipelineDeleterService $destructor,
        protected PipelineRestorerService $restorer,
    ) {
    }

    /**
     * Create a new pipeline.
     *
     * @param StorePipelineRequest $request Validated request
     * containing pipeline data.
     *
     * @return Pipeline The newly created pipeline.
     */
    public function store(
        StorePipelineRequest $request
    ): Pipeline {
        return $this->creator->create(
            $request->validated(),
            $request->user()->id
        );
    }

    /**
     * Update an existing pipeline.
     *
     * @param  UpdatePipelineRequest $request Validated
     * request containing updated pipeline data.
     * @param  Pipeline $companyAddress The pipeline
     * instance to update.
     *
     * @return Pipeline The updated pipeline.
     */
    public function update(
        UpdatePipelineRequest $request,
        Pipeline $companyAddress
    ): Pipeline {
        return $this->updater->update(
            $companyAddress,
            $request->validated(),
            $request->user()->id
        );
    }

    /**
     * Soft delete a pipeline.
     *
     * @param  Pipeline $companyAddress The pipeline to delete.
     *
     * @return void
     */
    public function destroy(Pipeline $companyAddress): void
    {
        $this->destructor->delete($companyAddress, auth()->id());
    }

    /**
     * Restore a soft-deleted pipeline.
     *
     * @param  int $id The ID of the pipeline to restore.
     *
     * @return Pipeline The restored pipeline.
     */
    public function restore(int $id): Pipeline
    {
        $companyAddress = Pipeline::withTrashed()->findOrFail($id);
        return $this->restorer->restore($companyAddress, auth()->id());
    }

    /**
     * Force delete a pipeline, permanently removing it from the
     * database.
     *
     * @param  int $id The ID of the pipeline to force delete.
     *
     * @return void
     */
    public function forceDelete(int $id): void
    {
        $companyAddress = Pipeline::withTrashed()->findOrFail($id);
        $this->destructor->forceDelete($companyAddress, auth()->id());
    }

    /**
     * Bulk restore pipelines.
     *
     * @param  array $ids The IDs of the pipeline to restore.
     * @param  User $actor The user performing the restoration, used for
     * logging.
     * @param  callable $authorizeCallback The callback to authorize
     * each pipeline.
     *
     * @return array The IDs of the pipeline that were restored.
     */
    public function bulkRestore(
        array $ids,
        User $actor,
        callable $authorizeCallback
    ): array {
        $restored = [];

        foreach ($ids as $id) {
            $pipeline = Pipeline::withTrashed()->findOrFail($id);
            $authorizeCallback($pipeline);

            if ($pipeline->trashed()) {
                $this->restorer->restore($pipeline, $actor->id);
                $restored[] = $id;
            }
        }

        return $restored;
    }

    /**
     * Bulk soft delete pipelines.
     *
     * @param  array $ids The IDs of the pipelines to delete.
     * @param  User $actor The user performing the deletion, used for logging.
     * @param  callable $authorizeCallback The callback to authorize each
     * pipeline.
     *
     * @return array The IDs of the pipelines that were deleted.
     */
    public function bulkDelete(
        array $ids,
        User $actor,
        callable $authorizeCallback
    ): array {
        $deleted = [];

        foreach ($ids as $id) {
            $pipeline = Pipeline::findOrFail($id);
            $authorizeCallback($pipeline);

            $this->destructor->delete($pipeline, $actor->id);
            $deleted[] = $id;
        }

        return $deleted;
    }
}
