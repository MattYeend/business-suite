<?php

namespace App\Services\PipelineStages;

use App\Models\PipelineStage;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class PipelineStageDeleterService
{
    public function __construct(
        protected PipelineStageLogService $logService
    ) {
    }

    /**
     * Soft delete a pipeline stage.
     *
     * @param  PipelineStage $stage
     * @param  int|null $deletedBy
     *
     * @return bool
     *
     * @throws \Exception
     */
    public function delete(
        PipelineStage $stage,
        ?int $deletedBy = null
    ): bool {
        return DB::transaction(function () use ($stage, $deletedBy) {
            $actor = User::findOrFail($deletedBy);
            $stage->deleted_by = $deletedBy;
            $stage->save();

            $result = $stage->delete();

            $this->logService->logDeletion($stage, $actor, $deletedBy);

            return $result;
        });
    }

    /**
     * Force delete a pipeline stage (permanent deletion).
     *
     * @param  PipelineStage $stage
     * @param  int|null $deletedBy
     *
     * @return bool
     *
     * @throws \Exception
     */
    public function forceDelete(
        PipelineStage $stage,
        ?int $deletedBy = null
    ): bool {
        return DB::transaction(function () use ($stage, $deletedBy) {
            $actor = User::findOrFail($deletedBy);
            $this->logService->logForceDeletion($stage, $actor, $deletedBy);

            return $stage->forceDelete();
        });
    }

    /**
     * Delete multiple pipeline stages.
     *
     * @param  array $stageIds
     * @param  int|null $deletedBy
     *
     * @return int Number of pipeline stages deleted
     *
     * @throws \Exception
     */
    public function deleteMultiple(
        array $stageIds,
        ?int $deletedBy = null
    ): int {
        $count = 0;

        DB::transaction(function () use ($stageIds, $deletedBy, &$count) {
            $stages = PipelineStage::whereIn('id', $stageIds)->get();

            foreach ($stages as $stage) {
                if ($this->delete($stage, $deletedBy)) {
                    $count++;
                }
            }
        });

        return $count;
    }
}
