<?php

namespace App\Services\Products;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use App\Models\User;

class ProductManagementService
{
    /**
     * Inject the required services into the management service.
     *
     * @param  ProductCreatorService $creator
     * @param  ProductUpdaterService $updater
     * @param  ProductDeleterService $destructor
     * @param  ProductRestorerService $restorer
     */
    public function __construct(
        protected ProductCreatorService $creator,
        protected ProductUpdaterService $updater,
        protected ProductDeleterService $destructor,
        protected ProductRestorerService $restorer,
    ) {
    }

    /**
     * Create a new product.
     *
     * @param StoreProductRequest $request
     *
     * @return Product
     */
    public function store(
        StoreProductRequest $request
    ): Product {
        return $this->creator->create(
            $request->validated(),
            $request->user()->id
        );
    }

    /**
     * Update an existing product.
     *
     * @param  UpdateProductRequest $request
     * @param  Product $product
     *
     * @return Product
     */
    public function update(
        UpdateProductRequest $request,
        Product $product
    ): Product {
        return $this->updater->update(
            $product,
            $request->validated(),
            $request->user()->id
        );
    }

    /**
     * Soft delete a product.
     *
     * @param  Product $product
     *
     * @return void
     */
    public function destroy(Product $product): void
    {
        $this->destructor->delete($product, auth()->id());
    }

    /**
     * Restore a soft-deleted product.
     *
     * @param  int $id
     *
     * @return Product
     */
    public function restore(int $id): Product
    {
        $product = Product::withTrashed()->findOrFail($id);
        return $this->restorer->restore($product, auth()->id());
    }

    /**
     * Force delete a product, permanently removing it from the
     * database.
     *
     * @param  int $id
     *
     * @return void
     */
    public function forceDelete(int $id): void
    {
        $product = Product::withTrashed()->findOrFail($id);
        $this->destructor->forceDelete($product, auth()->id());
    }

    /**
     * Bulk restore products.
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
            $product = Product::withTrashed()->findOrFail($id);
            $authoriseCallback($product);

            if ($product->trashed()) {
                $this->restorer->restore($product, $actor->id);
                $restored[] = $id;
            }
        }

        return $restored;
    }

    /**
     * Bulk soft delete products.
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
            $product = Product::findOrFail($id);
            $authoriseCallback($product);

            $this->destructor->delete($product, $actor->id);
            $deleted[] = $id;
        }

        return $deleted;
    }
}
