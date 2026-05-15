<?php

namespace App\Services\PipelineStages;

use App\Models\PipelineStage;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class PipelineStageRestorerService
{
    /**
     * Inject the required services into the resorer service.
     *
     * @param PipelineStageLogService $logService
     */
    public function __construct(
        protected PipelineStageLogService $logService
    ) {
    }

    /**
     * Restore a soft-deleted pipeline stage.
     *
     * @param  PipelineStage $stage
     * @param  int|null $restoredBy
     *
     * @return PipelineStage
     *
     * @throws \Exception
     */
    public function restore(
        PipelineStage $stage,
        ?int $restoredBy = null
    ): PipelineStage {
        return DB::transaction(function () use ($stage, $restoredBy) {
            $actor = User::findOrFail($restoredBy);

            $stage->restored_by = $restoredBy;
            $stage->restored_at = now();
            $stage->save();

            $stage->restore();

            $this->logService->logRestoration($stage, $actor, $restoredBy);

            return $stage->fresh();
        });
    }

    /**
     * Restore multiple soft-deleted pipeline stages.
     *
     * @param  array $stageIds
     * @param  int|null $restoredBy
     *
     * @return int Number of stages restored
     *
     * @throws \Exception
     */
    public function restoreMultiple(
        array $stageIds,
        ?int $restoredBy = null
    ): int {
        $count = 0;

        DB::transaction(function () use ($stageIds, $restoredBy, &$count) {
            /** @var Collection<int,PipelineStage> $stages */
            $stages = PipelineStage::withTrashed()
                ->whereIn('id', $stageIds)
                ->get();

            foreach ($stages as $stage) {
                if ($stage->trashed()) {
                    $this->restore($stage, $restoredBy);
                    $count++;
                }
            }
        });

        return $count;
    }
}
