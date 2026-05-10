<?php

namespace App\Services\Pipelines;

use App\Models\Pipeline;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class PipelineUpdaterService
{
    public function __construct(
        protected PipelineDataPreparationService $dataPreparation,
        protected PipelineLogService $logService
    ) {
    }

    /**
     * Update an existing pipeline.
     *
     * @param  Pipeline $pipeline
     * @param  array $data
     * @param  int|null $updatedBy
     *
     * @return Pipeline
     *
     * @throws \Exception
     */
    public function update(
        Pipeline $pipeline,
        array $data,
        ?int $updatedBy = null
    ): Pipeline {
        return DB::transaction(function () use ($pipeline, $data, $updatedBy) {
            $actor = User::findOrFail($updatedBy);

            $this->updateCompanyAddressData($pipeline, $data, $updatedBy);
            $this->logService->logUpdate($pipeline, $actor, $updatedBy);

            return $pipeline->fresh();
        });
    }

    /**
     * Update pipeline data.
     *
     * @param  Pipeline $pipeline
     * @param  array $data
     * @param  int|null $updatedBy
     *
     * @return void
     */
    protected function updateCompanyAddressData(
        Pipeline $pipeline,
        array $data,
        ?int $updatedBy
    ): void {
        $fillableData = $this->dataPreparation->prepareForUpdate(
            $data,
            $updatedBy
        );
        $pipeline->update($fillableData);
        $pipeline->save();
    }
}
