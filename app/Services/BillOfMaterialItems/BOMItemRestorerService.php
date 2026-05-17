<?php

namespace App\Services\BillOfMaterialItems;

use App\Models\BillOfMaterialItem;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class BOMItemRestorerService
{
    /**
     * Inject the required services into the resorer service.
     *
     * @param BOMItemLogService $logService
     */
    public function __construct(
        protected BOMItemLogService $logService
    ) {
    }

    /**
     * Restore a soft-deleted BOMItem.
     *
     * @param  BillOfMaterialItem $billOfMaterialItem
     * @param  int|null $restoredBy
     *
     * @return BillOfMaterialItem
     *
     * @throws \Exception
     */
    public function restore(
        BillOfMaterialItem $billOfMaterialItem,
        ?int $restoredBy = null
    ): BillOfMaterialItem {
        return DB::transaction(function () use (
            $billOfMaterialItem,
            $restoredBy
        ) {
            $actor = User::findOrFail($restoredBy);

            $billOfMaterialItem->restored_by = $restoredBy;
            $billOfMaterialItem->restored_at = now();
            $billOfMaterialItem->save();

            // restore() returns boolean, so we don't assign it
            $billOfMaterialItem->restore();

            $this->logService->logRestoration(
                $billOfMaterialItem,
                $actor,
                $restoredBy
            );

            // Return the fresh model instance
            return $billOfMaterialItem->fresh();
        });
    }

    /**
     * Restore multiple soft-deleted BOMItem.
     *
     * @param  array $billOfMaterialItemsIds
     * @param  int|null $restoredBy
     *
     * @return int Number of BOMItem restored
     *
     * @throws \Exception
     */
    public function restoreMultiple(
        array $billOfMaterialItemsIds,
        ?int $restoredBy = null
    ): int {
        $count = 0;

        DB::transaction(function () use (
            $billOfMaterialItemsIds,
            $restoredBy,
            &$count
        ) {
            /** @var Collection<int,BillOfMaterialItem> $billOfMaterials */
            $billOfMaterials = BillOfMaterialItem::withTrashed()
                ->whereIn('id', $billOfMaterialItemsIds)
                ->get();

            foreach ($billOfMaterials as $billOfMaterialItem) {
                if ($billOfMaterialItem->trashed()) {
                    $this->restore($billOfMaterialItem, $restoredBy);
                    $count++;
                }
            }
        });

        return $count;
    }
}
