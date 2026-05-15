<?php

namespace App\Services\Products;

use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ProductDeleterService
{
    /**
     * Inject the required services into the deleter service.
     *
     * @param ProductLogService $logService
     */
    public function __construct(
        protected ProductLogService $logService
    ) {
    }

    /**
     * Soft delete a product.
     *
     * @param  Product $product
     * @param  int|null $deletedBy
     *
     * @return bool
     *
     * @throws \Exception
     */
    public function delete(
        Product $product,
        ?int $deletedBy = null
    ): bool {
        return DB::transaction(function () use ($product, $deletedBy) {
            $actor = User::findOrFail($deletedBy);
            $product->deleted_by = $deletedBy;
            $product->save();

            $result = $product->delete();

            $this->logService->logDeletion($product, $actor, $deletedBy);

            return $result;
        });
    }

    /**
     * Force delete a product (permanent deletion).
     *
     * @param  Product $product
     * @param  int|null $deletedBy
     *
     * @return bool
     *
     * @throws \Exception
     */
    public function forceDelete(
        Product $product,
        ?int $deletedBy = null
    ): bool {
        return DB::transaction(function () use ($product, $deletedBy) {
            $actor = User::findOrFail($deletedBy);
            $this->logService->logForceDeletion($product, $actor, $deletedBy);

            return $product->forceDelete();
        });
    }

    /**
     * Delete multiple products.
     *
     * @param  array $productIds
     * @param  int|null $deletedBy
     *
     * @return int Number of products deleted
     *
     * @throws \Exception
     */
    public function deleteMultiple(
        array $productIds,
        ?int $deletedBy = null
    ): int {
        $count = 0;

        DB::transaction(function () use ($productIds, $deletedBy, &$count) {
            $products = Product::whereIn('id', $productIds)->get();

            foreach ($products as $product) {
                if ($this->delete($product, $deletedBy)) {
                    $count++;
                }
            }
        });

        return $count;
    }
}
