<?php

namespace App\Services\PipelineStages;

use App\Http\Requests\StorePipelineStageRequest;
use App\Http\Requests\UpdatePipelineStageRequest;
use App\Models\PipelineStage;
use App\Models\User;

class PipelineStageManagementService
{
    /**
     * Inject the required services into the management service.
     *
     * @param PipelineStageCreatorService $creator
     * @param PipelineStageUpdaterService $updater
     * @param PipelineStageDeleterService $destructor
     * @param PipelineStageRestorerService $restorer
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
     * @param StorePipelineStageRequest $request
     *
     * @return PipelineStage
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
     * @param  UpdatePipelineStageRequest $request
     * @param  PipelineStage $stage
     *
     * @return PipelineStage
     */
    public function update(
        UpdatePipelineStageRequest $request,
        PipelineStage $stage
    ): PipelineStage {
        return $this->updater->update(
            $stage,
            $request->validated(),
            $request->user()->id
        );
    }

    /**
     * Soft delete a pipeline stage.
     *
     * @param  PipelineStage $stage
     *
     * @return void
     */
    public function destroy(PipelineStage $stage): void
    {
        $this->destructor->delete($stage, auth()->id());
    }

    /**
     * Restore a soft-deleted pipeline stage.
     *
     * @param  int $id
     *
     * @return PipelineStage
     */
    public function restore(int $id): PipelineStage
    {
        $stage = PipelineStage::withTrashed()->findOrFail($id);
        return $this->restorer->restore($stage, auth()->id());
    }

    /**
     * Force delete a pipeline stage, permanently removing it from the
     * database.
     *
     * @param  int $id 
     *
     * @return void
     */
    public function forceDelete(int $id): void
    {
        $stage = PipelineStage::withTrashed()->findOrFail($id);
        $this->destructor->forceDelete($stage, auth()->id());
    }

    /**
     * Bulk restore pipeline stages.
     *
     * @param  array $ids
     * @param  User $actor
     * @param  callable $authoriseCallback
     *
     * @return array
     */
    public function bulkRestore(
        array $ids,
        User $actor,
        callable $authoriseCallback
    ): array {
        $restored = [];

        foreach ($ids as $id) {
            $stage = PipelineStage::withTrashed()->findOrFail($id);
            $authoriseCallback($stage);

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
     * @param  array $ids
     * @param  User $actor
     * @param  callable $authoriseCallback
     *
     * @return array
     */
    public function bulkDelete(
        array $ids,
        User $actor,
        callable $authoriseCallback
    ): array {
        $deleted = [];

        foreach ($ids as $id) {
            $stage = PipelineStage::findOrFail($id);
            $authoriseCallback($stage);

            $this->destructor->delete($stage, $actor->id);
            $deleted[] = $id;
        }

        return $deleted;
    }
}
