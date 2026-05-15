<?php

namespace App\Services\Pipelines;

use App\Http\Requests\StorePipelineRequest;
use App\Http\Requests\UpdatePipelineRequest;
use App\Models\Pipeline;
use App\Models\User;

class PipelineManagementService
{
    /**
     * Inject the required services into the management service.
     *
     * @param PipelineCreatorService $creator
     * @param PipelineUpdaterService $updater
     * @param PipelineDeleterService $destructor
     * @param PipelineRestorerService $restorer
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
     * @param StorePipelineRequest $request
     *
     * @return Pipeline
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
     * @param  UpdatePipelineRequest $request
     * @param  Pipeline $pipeline
     *
     * @return Pipeline
     */
    public function update(
        UpdatePipelineRequest $request,
        Pipeline $pipeline
    ): Pipeline {
        return $this->updater->update(
            $pipeline,
            $request->validated(),
            $request->user()->id
        );
    }

    /**
     * Soft delete a pipeline.
     *
     * @param  Pipeline $pipeline
     *
     * @return void
     */
    public function destroy(Pipeline $pipeline): void
    {
        $this->destructor->delete($pipeline, auth()->id());
    }

    /**
     * Restore a soft-deleted pipeline.
     *
     * @param  int $id
     *
     * @return Pipeline
     */
    public function restore(int $id): Pipeline
    {
        $pipeline = Pipeline::withTrashed()->findOrFail($id);
        return $this->restorer->restore($pipeline, auth()->id());
    }

    /**
     * Force delete a pipeline, permanently removing it from the
     * database.
     *
     * @param  int $id
     *
     * @return void
     */
    public function forceDelete(int $id): void
    {
        $pipeline = Pipeline::withTrashed()->findOrFail($id);
        $this->destructor->forceDelete($pipeline, auth()->id());
    }

    /**
     * Bulk restore pipelines.
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
            $pipeline = Pipeline::withTrashed()->findOrFail($id);
            $authoriseCallback($pipeline);

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
            $pipeline = Pipeline::findOrFail($id);
            $authoriseCallback($pipeline);

            $this->destructor->delete($pipeline, $actor->id);
            $deleted[] = $id;
        }

        return $deleted;
    }
}
