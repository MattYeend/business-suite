<?php

namespace App\Services\Pipelines;

use App\Models\Pipeline;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;

class PipelineCreatorService
{
    /**
     * Inject the required services into the creator service.
     *
     * @param PipelineDataPreparationService $dataPreparation
     * @param PipelineLogService $logService
     */
    public function __construct(
        protected PipelineDataPreparationService $dataPreparation,
        protected PipelineLogService $logService
    ) {
    }

    /**
     * Create a new pipeline.
     *
     * @param  array $data
     * @param  int $createdBy
     *
     * @return Pipeline
     *
     * @throws ModelNotFoundException
     */
    public function create(array $data, int $createdBy): Pipeline
    {
        $actor = User::findOrFail($createdBy);

        return DB::transaction(function () use ($data, $createdBy, $actor) {
            $address = $this->createPipeline($data, $createdBy);
            $this->logService->logCreation($address, $actor, $createdBy);

            return $address;
        });
    }

    /**
     * Create the pipeline record.
     *
     * @param  array $data
     * @param  int $createdBy
     *
     * @return Pipeline
     */
    protected function createPipeline(
        array $data,
        int $createdBy
    ): Pipeline {
        $pipelineData = $this->dataPreparation->prepareForCreation(
            $data,
            $createdBy
        );

        return Pipeline::create($pipelineData);
    }
}
