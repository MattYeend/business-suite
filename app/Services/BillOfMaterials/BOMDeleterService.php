<?php

namespace App\Services\BillOfMaterials;

use App\Models\BillOfMaterial;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class BOMDeleterService
{
    /**
     * Inject the required services into the deleter service.
     *
     * @param  BOMLogService $logService
     */
    public function __construct(
        protected BOMLogService $logService
    ) {
    }

    /**
     * Soft delete a BOM.
     *
     * @param  BillOfMaterial $billOfMaterial
     * @param  int|null $deletedBy
     *
     * @return bool
     *
     * @throws \Exception
     */
    public function delete(
        BillOfMaterial $billOfMaterial,
        ?int $deletedBy = null
    ): bool {
        return DB::transaction(function () use ($billOfMaterial, $deletedBy) {
            $actor = User::findOrFail($deletedBy);
            $billOfMaterial->deleted_by = $deletedBy;
            $billOfMaterial->save();

            $result = $billOfMaterial->delete();

            $this->logService->logDeletion($billOfMaterial, $actor, $deletedBy);

            return $result;
        });
    }

    /**
     * Force delete a BOM (permanent deletion).
     *
     * @param  BillOfMaterial $billOfMaterial
     * @param  int|null $deletedBy
     *
     * @return bool
     *
     * @throws \Exception
     */
    public function forceDelete(
        BillOfMaterial $billOfMaterial,
        ?int $deletedBy = null
    ): bool {
        return DB::transaction(function () use ($billOfMaterial, $deletedBy) {
            $actor = User::findOrFail($deletedBy);
            $this->logService->logForceDeletion($billOfMaterial, $actor, $deletedBy);

            return $billOfMaterial->forceDelete();
        });
    }

    /**
     * Delete multiple BOMs.
     *
     * @param  array $billOfMaterialIds
     * @param  int|null $deletedBy
     *
     * @return int Number of BOMs deleted
     *
     * @throws \Exception
     */
    public function deleteMultiple(
        array $billOfMaterialIds,
        ?int $deletedBy = null
    ): int {
        $count = 0;

        DB::transaction(function () use ($billOfMaterialIds, $deletedBy, &$count) {
            $BOMs = BillOfMaterial::whereIn('id', $billOfMaterialIds)->get();

            foreach ($BOMs as $billOfMaterial) {
                if ($this->delete($billOfMaterial, $deletedBy)) {
                    $count++;
                }
            }
        });

        return $count;
    }
}
