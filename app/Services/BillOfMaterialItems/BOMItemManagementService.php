<?php

namespace App\Services\BillOfMaterialItems;

use App\Http\Requests\StoreBillOfMaterialItemRequest;
use App\Http\Requests\UpdateBillOfMaterialItemRequest;
use App\Models\BillOfMaterialItem;
use App\Models\User;

class BOMItemManagementService
{
    /**
     * Inject the required services into the management service.
     *
     * @param BOMItemCreatorService $creator
     * @param BOMItemUpdaterService $updater
     * @param BOMItemDeleterService $destructor
     * @param BOMItemRestorerService $restorer
     */
    public function __construct(
        protected BOMItemCreatorService $creator,
        protected BOMItemUpdaterService $updater,
        protected BOMItemDeleterService $destructor,
        protected BOMItemRestorerService $restorer,
    ) {
    }

    /**
     * Create a new BOMItem.
     *
     * @param  StoreBillOfMaterialItemRequest $request
     *
     * @return BillOfMaterialItem
     */
    public function store(
        StoreBillOfMaterialItemRequest $request
    ): BillOfMaterialItem {
        return $this->creator->create(
            $request->validated(),
            $request->user()->id
        );
    }

    /**
     * Update an existing BOMItem.
     *
     * @param  UpdateBillOfMaterialItemRequest $request
     * @param  BillOfMaterialItem $billOfMaterialItem
     *
     * @return BillOfMaterialItem
     */
    public function update(
        UpdateBillOfMaterialItemRequest $request,
        BillOfMaterialItem $billOfMaterialItem
    ): BillOfMaterialItem {
        return $this->updater->update(
            $billOfMaterialItem,
            $request->validated(),
            $request->user()->id
        );
    }

    /**
     * Soft delete a BOMItem.
     *
     * @param  BillOfMaterialItem $billOfMaterialItem
     *
     * @return void
     */
    public function destroy(BillOfMaterialItem $billOfMaterialItem): void
    {
        $this->destructor->delete($billOfMaterialItem, auth()->id());
    }

    /**
     * Restore a soft-deleted BOMItem.
     *
     * @param  int $id
     *
     * @return BillOfMaterialItem
     */
    public function restore(int $id): BillOfMaterialItem
    {
        $billOfMaterialItem = BillOfMaterialItem::withTrashed()->findOrFail($id);
        return $this->restorer->restore($billOfMaterialItem, auth()->id());
    }

    /**
     * Force delete a BOMItem, permanently removing it from the
     * database.
     *
     * @param  int $id
     *
     * @return void
     */
    public function forceDelete(int $id): void
    {
        $billOfMaterialItem = BillOfMaterialItem::withTrashed()->findOrFail($id);
        $this->destructor->forceDelete($billOfMaterialItem, auth()->id());
    }

    /**
     * Bulk restore BOMItems.
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
            $billOfMaterialItem = BillOfMaterialItem::withTrashed()->findOrFail($id);
            $authoriseCallback($billOfMaterialItem);

            if ($billOfMaterialItem->trashed()) {
                $this->restorer->restore($billOfMaterialItem, $actor->id);
                $restored[] = $id;
            }
        }

        return $restored;
    }

    /**
     * Bulk soft delete BOMItems.
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
            $billOfMaterialItem = BillOfMaterialItem::findOrFail($id);
            $authoriseCallback($billOfMaterialItem);

            $this->destructor->delete($billOfMaterialItem, $actor->id);
            $deleted[] = $id;
        }

        return $deleted;
    }
}
