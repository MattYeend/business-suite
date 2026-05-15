<?php

namespace App\Services\Parts;

use App\Models\Part;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;

class PartCreatorService
{
    /**
     * Inject the required services into the creator service.
     *
     * @param PartDataPreparationService $dataPreparation
     * @param PartLogService $logService
     */
    public function __construct(
        protected PartDataPreparationService $dataPreparation,
        protected PartLogService $logService
    ) {
    }

    /**
     * Create a new part.
     *
     * @param  array $data
     * @param  int $createdBy
     *
     * @return Part
     *
     * @throws ModelNotFoundException
     */
    public function create(array $data, int $createdBy): Part
    {
        $actor = User::findOrFail($createdBy);

        return DB::transaction(function () use ($data, $createdBy, $actor) {
            $part = $this->createCompany($data, $createdBy);
            $this->logService->logCreation($part, $actor, $createdBy);

            return $part;
        });
    }

    /**
     * Create the part record.
     *
     * @param  array $data
     * @param  int $createdBy
     *
     * @return Part
     */
    protected function createCompany(
        array $data,
        int $createdBy
    ): Part {
        $companyData = $this->dataPreparation->prepareForCreation(
            $data,
            $createdBy
        );

        return Part::create($companyData);
    }
}
