<?php

namespace App\Services\Pipelines;

use App\Models\Pipeline;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class PipelineRestorerService
{
    public function __construct(
        protected PipelineLogService $logService
    ) {
    }

    /**
     * Restore a soft-deleted pipeline.
     *
     * @param  Pipeline $pipeline
     * @param  int|null $restoredBy
     *
     * @return Pipeline
     *
     * @throws \Exception
     */
    public function restore(
        Pipeline $pipeline,
        ?int $restoredBy = null
    ): Pipeline {
        return DB::transaction(function () use ($pipeline, $restoredBy) {
            $actor = User::findOrFail($restoredBy);

            $pipeline->restored_by = $restoredBy;
            $pipeline->restored_at = now();
            $pipeline->save();

            $pipeline->restore();

            $this->logService->logRestoration($pipeline, $actor, $restoredBy);

            return $pipeline->fresh();
        });
    }

    /**
     * Restore multiple soft-deleted pipelines.
     *
     * @param  array $pipelineIds
     * @param  int|null $restoredBy
     *
     * @return int Number of pipelines restored
     *
     * @throws \Exception
     */
    public function restoreMultiple(
        array $pipelineIds,
        ?int $restoredBy = null
    ): int {
        $count = 0;

        DB::transaction(function () use ($pipelineIds, $restoredBy, &$count) {
            /** @var Collection<int,Pipeline> $pipelines */
            $pipelines = Pipeline::withTrashed()
                ->whereIn('id', $pipelineIds)
                ->get();

            foreach ($pipelines as $pipeline) {
                if ($pipeline->trashed()) {
                    $this->restore($pipeline, $restoredBy);
                    $count++;
                }
            }
        });

        return $count;
    }
}
