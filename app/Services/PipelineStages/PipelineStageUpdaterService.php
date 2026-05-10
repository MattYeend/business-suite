<?php

namespace App\Services\PipelineStages;

use App\Models\PipelineStage;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class PipelineStageUpdaterService
{
    public function __construct(
        protected PipelineStageDataPreparationService $dataPreparation,
        protected PipelineStageLogService $logService
    ) {
    }

    /**
     * Update an existing pipeline stage.
     *
     * @param  PipelineStage $stage
     * @param  array $data
     * @param  int|null $updatedBy
     *
     * @return PipelineStage
     *
     * @throws \Exception
     */
    public function update(
        PipelineStage $stage,
        array $data,
        ?int $updatedBy = null
    ): PipelineStage {
        return DB::transaction(function () use ($stage, $data, $updatedBy) {
            $actor = User::findOrFail($updatedBy);

            $this->updateCompanyAddressData($stage, $data, $updatedBy);
            $this->logService->logUpdate($stage, $actor, $updatedBy);

            return $stage->fresh();
        });
    }

    /**
     * Update pipeline stage data.
     *
     * @param  PipelineStage $stage
     * @param  array $data
     * @param  int|null $updatedBy
     *
     * @return void
     */
    protected function updateCompanyAddressData(
        PipelineStage $stage,
        array $data,
        ?int $updatedBy
    ): void {
        $fillableData = $this->dataPreparation->prepareForUpdate(
            $data,
            $updatedBy
        );
        $stage->update($fillableData);
        $stage->save();
    }
}
