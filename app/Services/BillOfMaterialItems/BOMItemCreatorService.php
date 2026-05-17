<?php

namespace App\Services\BillOfMaterialItems;

use App\Models\BillOfMaterialItem;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;

class BOMItemCreatorService
{
    /**
     * Inject the required services into the creator service.
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
     * Create a new BOMItem.
     *
     * @param  array $data
     * @param  int $createdBy
     *
     * @return BillOfMaterialItem
     *
     * @throws ModelNotFoundException
     */
    public function create(array $data, int $createdBy): BillOfMaterialItem
    {
        $actor = User::findOrFail($createdBy);

        return DB::transaction(function () use ($data, $createdBy, $actor) {
            $billOfMaterialItem = $this->createCompany($data, $createdBy);
            $this->logService->logCreation(
                $billOfMaterialItem,
                $actor,
                $createdBy
            );

            return $billOfMaterialItem;
        });
    }

    /**
     * Create the BOMItem record.
     *
     * @param  array $data
     * @param  int $createdBy
     *
     * @return BillOfMaterialItem
     */
    protected function createCompany(
        array $data,
        int $createdBy
    ): BillOfMaterialItem {
        $companyData = $this->dataPreparation->prepareForCreation(
            $data,
            $createdBy
        );

        return BillOfMaterialItem::create($companyData);
    }
}
