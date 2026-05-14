<?php

namespace App\Services\BillOfMaterials;

use App\Http\Requests\StoreBillOfMaterialRequest;
use App\Http\Requests\UpdateBillOfMaterialRequest;
use App\Models\BillOfMaterial;
use App\Models\User;

class BOMManagementService
{
    /**
     * Inject the required services into the management service.
     *
     * @param BOMCreatorService $creator
     * @param BOMUpdaterService $updater
     * @param BOMDeleterService $destructor
     * @param BOMRestorerService $restorer
     */
    public function __construct(
        protected BOMCreatorService $creator,
        protected BOMUpdaterService $updater,
        protected BOMDeleterService $destructor,
        protected BOMRestorerService $restorer,
    ) {
    }

    /**
     * Create a new BOM.
     *
     * @param  StoreBillOfMaterialRequest $request
     *
     * @return BillOfMaterial
     */
    public function store(
        StoreBillOfMaterialRequest $request
    ): BillOfMaterial {
        return $this->creator->create(
            $request->validated(),
            $request->user()->id
        );
    }

    /**
     * Update an existing BOM.
     *
     * @param  UpdateBillOfMaterialRequest $request
     * @param  BillOfMaterial $billOfMaterial
     *
     * @return BillOfMaterial
     */
    public function update(
        UpdateBillOfMaterialRequest $request,
        BillOfMaterial $billOfMaterial
    ): BillOfMaterial {
        return $this->updater->update(
            $billOfMaterial,
            $request->validated(),
            $request->user()->id
        );
    }

    /**
     * Soft delete a BOM.
     *
     * @param  BillOfMaterial $billOfMaterial
     *
     * @return void
     */
    public function destroy(BillOfMaterial $billOfMaterial): void
    {
        $this->destructor->delete($billOfMaterial, auth()->id());
    }

    /**
     * Restore a soft-deleted BOM.
     *
     * @param  int $id
     *
     * @return BillOfMaterial
     */
    public function restore(int $id): BillOfMaterial
    {
        $billOfMaterial = BillOfMaterial::withTrashed()->findOrFail($id);
        return $this->restorer->restore($billOfMaterial, auth()->id());
    }

    /**
     * Force delete a BOM, permanently removing it from the
     * database.
     *
     * @param  int $id
     *
     * @return void
     */
    public function forceDelete(int $id): void
    {
        $billOfMaterial = BillOfMaterial::withTrashed()->findOrFail($id);
        $this->destructor->forceDelete($billOfMaterial, auth()->id());
    }

    /**
     * Bulk restore BOMs.
     *
     * @param  array $ids
     * @param  User $actor
     * @param  callable $authoriseCallback
     *
     * @return array
     */
    public function bulkRestore(
        array $ids,
        User $actor,
        callable $authoriseCallback
    ): array {
        $restored = [];

        foreach ($ids as $id) {
            $billOfMaterial = BillOfMaterial::withTrashed()->findOrFail($id);
            $authoriseCallback($billOfMaterial);

            if ($billOfMaterial->trashed()) {
                $this->restorer->restore($billOfMaterial, $actor->id);
                $restored[] = $id;
            }
        }

        return $restored;
    }

    /**
     * Bulk soft delete BOMs.
     *
     * @param  array $ids
     * @param  User $actor
     * @param  callable $authoriseCallback
     *
     * @return array
     */
    public function bulkDelete(
        array $ids,
        User $actor,
        callable $authoriseCallback
    ): array {
        $deleted = [];

        foreach ($ids as $id) {
            $billOfMaterial = BillOfMaterial::findOrFail($id);
            $authoriseCallback($billOfMaterial);

            $this->destructor->delete($billOfMaterial, $actor->id);
            $deleted[] = $id;
        }

        return $deleted;
    }
}
