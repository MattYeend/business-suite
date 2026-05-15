<?php

namespace App\Services\Pipelines;

use App\Models\Pipeline;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class PipelineDeleterService
{
    /**
     * Inject the required services into the deleter service.
     *
     * @param PipelineLogService $logService
     */
    public function __construct(
        protected PipelineLogService $logService
    ) {
    }

    /**
     * Soft delete a pipeline.
     *
     * @param  Pipeline $pipeline
     * @param  int|null $deletedBy
     *
     * @return bool
     *
     * @throws \Exception
     */
    public function delete(
        Pipeline $pipeline,
        ?int $deletedBy = null
    ): bool {
        return DB::transaction(function () use ($pipeline, $deletedBy) {
            $actor = User::findOrFail($deletedBy);
            $pipeline->deleted_by = $deletedBy;
            $pipeline->save();

            $result = $pipeline->delete();

            $this->logService->logDeletion($pipeline, $actor, $deletedBy);

            return $result;
        });
    }

    /**
     * Force delete a pipeline (permanent deletion).
     *
     * @param  Pipeline $pipeline
     * @param  int|null $deletedBy
     *
     * @return bool
     *
     * @throws \Exception
     */
    public function forceDelete(
        Pipeline $pipeline,
        ?int $deletedBy = null
    ): bool {
        return DB::transaction(function () use ($pipeline, $deletedBy) {
            $actor = User::findOrFail($deletedBy);
            $this->logService->logForceDeletion($pipeline, $actor, $deletedBy);

            return $pipeline->forceDelete();
        });
    }

    /**
     * Delete multiple addresses.
     *
     * @param  array $pipelineIds
     * @param  int|null $deletedBy
     *
     * @return int Number of addresses deleted
     *
     * @throws \Exception
     */
    public function deleteMultiple(
        array $pipelineIds,
        ?int $deletedBy = null
    ): int {
        $count = 0;

        DB::transaction(function () use ($pipelineIds, $deletedBy, &$count) {
            $addresses = Pipeline::whereIn('id', $pipelineIds)->get();

            foreach ($addresses as $pipeline) {
                if ($this->delete($pipeline, $deletedBy)) {
                    $count++;
                }
            }
        });

        return $count;
    }
}
