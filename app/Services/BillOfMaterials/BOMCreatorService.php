<?php

namespace App\Services\BillOfMaterials;

use App\Models\BillOfMaterial;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;

class BOMCreatorService
{
    /**
     * Inject the required services into the creator service.
     *
     * @param  BOMDataPreparationService $dataPreparation
     * @param  BOMLogService $logService
     *
     * @return void
     */
    public function __construct(
        protected BOMDataPreparationService $dataPreparation,
        protected BOMLogService $logService
    ) {
    }

    /**
     * Create a new BOM.
     *
     * @param  array $data
     * @param  int $createdBy
     *
     * @return BillOfMaterial
     *
     * @throws ModelNotFoundException
     */
    public function create(array $data, int $createdBy): BillOfMaterial
    {
        $actor = User::findOrFail($createdBy);

        return DB::transaction(function () use ($data, $createdBy, $actor) {
            $billOfMaterial = $this->createCompany($data, $createdBy);
            $this->logService->logCreation($billOfMaterial, $actor, $createdBy);

            return $billOfMaterial;
        });
    }

    /**
     * Create the BOM record.
     *
     * @param  array $data
     * @param  int $createdBy
     *
     * @return BillOfMaterial
     */
    protected function createCompany(
        array $data,
        int $createdBy
    ): BillOfMaterial {
        $companyData = $this->dataPreparation->prepareForCreation(
            $data,
            $createdBy
        );

        return BillOfMaterial::create($companyData);
    }
}
