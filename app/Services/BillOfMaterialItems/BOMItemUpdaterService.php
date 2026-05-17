<?php

namespace App\Services\BillOfMaterialItems;

use App\Models\BillOfMaterialItem;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class BOMItemUpdaterService
{
    /**
     * Inject the required services into the updater service.
     *
     * @param BOMItemDataPreparationService $dataPreparation
     * @param BOMItemLogService $logService
     */
    public function __construct(
        protected BOMItemDataPreparationService $dataPreparation,
        protected BOMItemLogService $logService
    ) {
    }

    /**
     * Update an existing BOMItem.
     *
     * @param  BillOfMaterialItem $billOfMaterialItem
     * @param  array $data
     * @param  int|null $updatedBy
     *
     * @return BillOfMaterialItem
     *
     * @throws \Exception
     */
    public function update(
        BillOfMaterialItem $billOfMaterialItem,
        array $data,
        ?int $updatedBy = null
    ): BillOfMaterialItem {
        return DB::transaction(function () use (
            $billOfMaterialItem,
            $data,
            $updatedBy
        ) {
            $actor = User::findOrFail($updatedBy);

            $this->updateCompanyData($billOfMaterialItem, $data, $updatedBy);
            $this->logService->logUpdate(
                $billOfMaterialItem,
                $actor,
                $updatedBy
            );

            return $billOfMaterialItem->fresh();
        });
    }

    /**
     * Update BOMItem data.
     *
     * @param  BillOfMaterialItem $billOfMaterialItem
     * @param  array $data
     * @param  int|null $updatedBy
     *
     * @return void
     */
    protected function updateCompanyData(
        BillOfMaterialItem $billOfMaterialItem,
        array $data,
        ?int $updatedBy
    ): void {
        $fillableData = $this->dataPreparation->prepareForUpdate(
            $data,
            $updatedBy
        );
        $billOfMaterialItem->update($fillableData);
        $billOfMaterialItem->save();
    }
}
