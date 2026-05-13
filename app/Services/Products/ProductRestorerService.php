<?php

namespace App\Services\Products;

use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class ProductRestorerService
{
    /**
     * Inject the required services into the resorer service.
     *
     * @param  ProductLogService $logService
     */
    public function __construct(
        protected ProductLogService $logService
    ) {
    }

    /**
     * Restore a soft-deleted product.
     *
     * @param  Product $product
     * @param  int|null $restoredBy
     *
     * @return Product
     *
     * @throws \Exception
     */
    public function restore(
        Product $product,
        ?int $restoredBy = null
    ): Product {
        return DB::transaction(function () use ($product, $restoredBy) {
            $actor = User::findOrFail($restoredBy);

            $product->restored_by = $restoredBy;
            $product->restored_at = now();
            $product->save();

            // restore() returns boolean, so we don't assign it
            $product->restore();

            $this->logService->logRestoration($product, $actor, $restoredBy);

            // Return the fresh model instance
            return $product->fresh();
        });
    }

    /**
     * Restore multiple soft-deleted products.
     *
     * @param  array $productIds
     * @param  int|null $restoredBy
     *
     * @return int Number of products restored
     *
     * @throws \Exception
     */
    public function restoreMultiple(
        array $productIds,
        ?int $restoredBy = null
    ): int {
        $count = 0;

        DB::transaction(function () use ($productIds, $restoredBy, &$count) {
            /** @var Collection<int,Product> $products */
            $products = Product::withTrashed()
                ->whereIn('id', $productIds)
                ->get();

            foreach ($products as $product) {
                if ($product->trashed()) {
                    $this->restore($product, $restoredBy);
                    $count++;
                }
            }
        });

        return $count;
    }
}
