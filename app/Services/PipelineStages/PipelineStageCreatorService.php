<?php

namespace App\Services\PipelineStages;

use App\Models\PipelineStage;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;

class PipelineStageCreatorService
{
    public function __construct(
        protected PipelineStageDataPreparationService $dataPreparation,
        protected PipelineStageLogService $logService
    ) {
    }

    /**
     * Create a new pipeline stage.
     *
     * @param  array $data
     * @param  int $createdBy
     *
     * @return PipelineStage
     *
     * @throws ModelNotFoundException
     */
    public function create(array $data, int $createdBy): PipelineStage
    {
        $actor = User::findOrFail($createdBy);

        return DB::transaction(function () use ($data, $createdBy, $actor) {
            $stage = $this->createPipelineStage($data, $createdBy);
            $this->logService->logCreation($stage, $actor, $createdBy);

            return $stage;
        });
    }

    /**
     * Create the pipeline stage record.
     *
     * @param  array $data
     * @param  int $createdBy
     *
     * @return PipelineStage
     */
    protected function createPipelineStage(
        array $data,
        int $createdBy
    ): PipelineStage {
        $stageData = $this->dataPreparation->prepareForCreation(
            $data,
            $createdBy
        );

        return PipelineStage::create($stageData);
    }
}
