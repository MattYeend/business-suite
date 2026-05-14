<?php

namespace App\Services\BillOfMaterials;

use App\Models\BillOfMaterial;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class BOMUpdaterService
{
    /**
     * Inject the required services into the updater service.
     *
     * @param  BOMDataPreparationService $dataPreparation
     * @param  BOMLogService $logService
     */
    public function __construct(
        protected BOMDataPreparationService $dataPreparation,
        protected BOMLogService $logService
    ) {
    }

    /**
     * Update an existing BOM.
     *
     * @param  BillOfMaterial $billOfMaterial
     * @param  array $data
     * @param  int|null $updatedBy
     *
     * @return BillOfMaterial
     *
     * @throws \Exception
     */
    public function update(
        BillOfMaterial $billOfMaterial,
        array $data,
        ?int $updatedBy = null
    ): BillOfMaterial {
        return DB::transaction(function () use ($billOfMaterial, $data, $updatedBy) {
            $actor = User::findOrFail($updatedBy);

            $this->updateCompanyData($billOfMaterial, $data, $updatedBy);
            $this->logService->logUpdate($billOfMaterial, $actor, $updatedBy);

            return $billOfMaterial->fresh();
        });
    }

    /**
     * Update BOM data.
     *
     * @param  BillOfMaterial $billOfMaterial
     * @param  array $data
     * @param  int|null $updatedBy
     *
     * @return void
     */
    protected function updateCompanyData(
        BillOfMaterial $billOfMaterial,
        array $data,
        ?int $updatedBy
    ): void {
        $fillableData = $this->dataPreparation->prepareForUpdate(
            $data,
            $updatedBy
        );
        $billOfMaterial->update($fillableData);
        $billOfMaterial->save();
    }
}
