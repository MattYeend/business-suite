<?php

namespace App\Services\PipelineStages;

use App\Http\Requests\StorePipelineStageRequest;
use App\Http\Requests\UpdatePipelineStageRequest;
use App\Models\PipelineStage;
use App\Models\User;

/**
 * Orchestrates pipeline stage lifecycle operations by delegating to focused
 * sub-services.
 *
 * Acts as the single entry point for pipeline stage create, update, delete,
 * and restore operations, keeping controllers decoupled from the underlying
 * service implementations.
 */
class PipelineStageManagementService
{
    /**
     * Inject the required services into the management service.
     *
     * @param  PipelineStageCreatorService $creator Handles pipeline stage
     * creation.
     * @param  PipelineStageUpdaterService $updater Handles pipeline stage
     * updates.
     * @param  PipelineStageDeleterService $destructor Handles pipeline
     * stage deletion.
     * @param  PipelineStageRestorerService $restorer Handles pipeline stage
     * restoration.
     *
     * @return void
     */
    public function __construct(
        protected PipelineStageCreatorService $creator,
        protected PipelineStageUpdaterService $updater,
        protected PipelineStageDeleterService $destructor,
        protected PipelineStageRestorerService $restorer,
    ) {
    }

    /**
     * Create a new pipeline stage.
     *
     * @param StorePipelineStageRequest $request Validated request
     * containing pipeline stage data.
     *
     * @return PipelineStage The newly created pipeline stage.
     */
    public function store(
        StorePipelineStageRequest $request
    ): PipelineStage {
        return $this->creator->create(
            $request->validated(),
            $request->user()->id
        );
    }

    /**
     * Update an existing pipeline stage.
     *
     * @param  UpdatePipelineStageRequest $request Validated
     * request containing updated pipeline stage data.
     * @param  PipelineStage $companyAddress The pipeline stage
     * instance to update.
     *
     * @return PipelineStage The updated pipeline stage.
     */
    public function update(
        UpdatePipelineStageRequest $request,
        PipelineStage $companyAddress
    ): PipelineStage {
        return $this->updater->update(
            $companyAddress,
            $request->validated(),
            $request->user()->id
        );
    }

    /**
     * Soft delete a pipeline stage.
     *
     * @param  PipelineStage $companyAddress The pipeline stage to delete.
     *
     * @return void
     */
    public function destroy(PipelineStage $companyAddress): void
    {
        $this->destructor->delete($companyAddress, auth()->id());
    }

    /**
     * Restore a soft-deleted pipeline stage.
     *
     * @param  int $id The ID of the pipeline stage to restore.
     *
     * @return PipelineStage The restored pipeline stage.
     */
    public function restore(int $id): PipelineStage
    {
        $companyAddress = PipelineStage::withTrashed()->findOrFail($id);
        return $this->restorer->restore($companyAddress, auth()->id());
    }

    /**
     * Force delete a pipeline stage, permanently removing it from the
     * database.
     *
     * @param  int $id The ID of the pipeline stage to force delete.
     *
     * @return void
     */
    public function forceDelete(int $id): void
    {
        $companyAddress = PipelineStage::withTrashed()->findOrFail($id);
        $this->destructor->forceDelete($companyAddress, auth()->id());
    }

    /**
     * Bulk restore pipeline stages.
     *
     * @param  array $ids The IDs of the pipeline stages to restore.
     * @param  User $actor The user performing the restoration, used for
     * logging.
     * @param  callable $authorizeCallback The callback to authorize
     * each pipeline stage.
     *
     * @return array The IDs of the pipeline stages that were restored.
     */
    public function bulkRestore(
        array $ids,
        User $actor,
        callable $authorizeCallback
    ): array {
        $restored = [];

        foreach ($ids as $id) {
            $stage = PipelineStage::withTrashed()->findOrFail($id);
            $authorizeCallback($stage);

            if ($stage->trashed()) {
                $this->restorer->restore($stage, $actor->id);
                $restored[] = $id;
            }
        }

        return $restored;
    }

    /**
     * Bulk soft delete pipeline stages.
     *
     * @param  array $ids The IDs of the pipeline stages to delete.
     * @param  User $actor The user performing the deletion, used for logging.
     * @param  callable $authorizeCallback The callback to authorize each
     * pipeline stage.
     *
     * @return array The IDs of the pipeline stages that were deleted.
     */
    public function bulkDelete(
        array $ids,
        User $actor,
        callable $authorizeCallback
    ): array {
        $deleted = [];

        foreach ($ids as $id) {
            $stage = PipelineStage::findOrFail($id);
            $authorizeCallback($stage);

            $this->destructor->delete($stage, $actor->id);
            $deleted[] = $id;
        }

        return $deleted;
    }
}
