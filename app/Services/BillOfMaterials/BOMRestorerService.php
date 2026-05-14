<?php

namespace App\Services\BillOfMaterials;

use App\Models\BillOfMaterial;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class BOMRestorerService
{
    /**
     * Inject the required services into the resorer service.
     *
     * @param  BOMLogService $logService
     */
    public function __construct(
        protected BOMLogService $logService
    ) {
    }

    /**
     * Restore a soft-deleted BOM.
     *
     * @param  BillOfMaterial $billOfMaterial
     * @param  int|null $restoredBy
     *
     * @return BillOfMaterial
     *
     * @throws \Exception
     */
    public function restore(
        BillOfMaterial $billOfMaterial,
        ?int $restoredBy = null
    ): BillOfMaterial {
        return DB::transaction(function () use ($billOfMaterial, $restoredBy) {
            $actor = User::findOrFail($restoredBy);

            $billOfMaterial->restored_by = $restoredBy;
            $billOfMaterial->restored_at = now();
            $billOfMaterial->save();

            // restore() returns boolean, so we don't assign it
            $billOfMaterial->restore();

            $this->logService->logRestoration($billOfMaterial, $actor, $restoredBy);

            // Return the fresh model instance
            return $billOfMaterial->fresh();
        });
    }

    /**
     * Restore multiple soft-deleted BOM.
     *
     * @param  array $billOfMaterialIds
     * @param  int|null $restoredBy
     *
     * @return int Number of BOM restored
     *
     * @throws \Exception
     */
    public function restoreMultiple(
        array $billOfMaterialIds,
        ?int $restoredBy = null
    ): int {
        $count = 0;

        DB::transaction(function () use ($billOfMaterialIds, $restoredBy, &$count) {
            /** @var Collection<int,BillOfMaterial> $billOfMaterials */
            $billOfMaterials = BillOfMaterial::withTrashed()
                ->whereIn('id', $billOfMaterialIds)
                ->get();

            foreach ($billOfMaterials as $billOfMaterial) {
                if ($billOfMaterial->trashed()) {
                    $this->restore($billOfMaterial, $restoredBy);
                    $count++;
                }
            }
        });

        return $count;
    }
}
