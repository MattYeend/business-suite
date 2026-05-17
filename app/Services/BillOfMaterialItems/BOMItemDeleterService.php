<?php

namespace App\Services\BillOfMaterialItems;

use App\Models\BillOfMaterialItem;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class BOMItemDeleterService
{
    /**
     * Inject the required services into the deleter service.
     *
     * @param BOMItemLogService $logService
     */
    public function __construct(
        protected BOMItemLogService $logService
    ) {
    }

    /**
     * Soft delete a BOMItem.
     *
     * @param  BillOfMaterialItem $billOfMaterialItem
     * @param  int|null $deletedBy
     *
     * @return bool
     *
     * @throws \Exception
     */
    public function delete(
        BillOfMaterialItem $billOfMaterialItem,
        ?int $deletedBy = null
    ): bool {
        return DB::transaction(function () use ($billOfMaterialItem, $deletedBy) {
            $actor = User::findOrFail($deletedBy);
            $billOfMaterialItem->deleted_by = $deletedBy;
            $billOfMaterialItem->save();

            $result = $billOfMaterialItem->delete();

            $this->logService->logDeletion($billOfMaterialItem, $actor, $deletedBy);

            return $result;
        });
    }

    /**
     * Force delete a BOMItem (permanent deletion).
     *
     * @param  BillOfMaterialItem $billOfMaterialItem
     * @param  int|null $deletedBy
     *
     * @return bool
     *
     * @throws \Exception
     */
    public function forceDelete(
        BillOfMaterialItem $billOfMaterialItem,
        ?int $deletedBy = null
    ): bool {
        return DB::transaction(function () use ($billOfMaterialItem, $deletedBy) {
            $actor = User::findOrFail($deletedBy);
            $this->logService->logForceDeletion(
                $billOfMaterialItem,
                $actor,
                $deletedBy
            );

            return $billOfMaterialItem->forceDelete();
        });
    }

    /**
     * Delete multiple BOMItems.
     *
     * @param  array $billOfMaterialItemIds
     * @param  int|null $deletedBy
     *
     * @return int Number of BOMItems deleted
     *
     * @throws \Exception
     */
    public function deleteMultiple(
        array $billOfMaterialItemIds,
        ?int $deletedBy = null
    ): int {
        $count = 0;

        DB::transaction(function () use (
            $billOfMaterialItemIds,
            $deletedBy,
            &$count
        ) {
            $BOMItems = BillOfMaterialItem::whereIn('id', $billOfMaterialItemIds)->get();

            foreach ($BOMItems as $billOfMaterialItem) {
                if ($this->delete($billOfMaterialItem, $deletedBy)) {
                    $count++;
                }
            }
        });

        return $count;
    }
}
