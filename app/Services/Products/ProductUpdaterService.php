<?php

namespace App\Services\Products;

use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ProductUpdaterService
{
    /**
     * Inject the required services into the updater service.
     *
     * @param  ProductDataPreparationService $dataPreparation
     * @param  ProductLogService $logService
     */
    public function __construct(
        protected ProductDataPreparationService $dataPreparation,
        protected ProductLogService $logService
    ) {
    }

    /**
     * Update an existing product.
     *
     * @param  Product $product
     * @param  array $data
     * @param  int|null $updatedBy
     *
     * @return Product
     *
     * @throws \Exception
     */
    public function update(
        Product $product,
        array $data,
        ?int $updatedBy = null
    ): Product {
        return DB::transaction(function () use ($product, $data, $updatedBy) {
            $actor = User::findOrFail($updatedBy);

            $this->updateCompanyData($product, $data, $updatedBy);
            $this->logService->logUpdate($product, $actor, $updatedBy);

            return $product->fresh();
        });
    }

    /**
     * Update product data.
     *
     * @param  Product $product
     * @param  array $data
     * @param  int|null $updatedBy
     *
     * @return void
     */
    protected function updateCompanyData(
        Product $product,
        array $data,
        ?int $updatedBy
    ): void {
        $fillableData = $this->dataPreparation->prepareForUpdate(
            $data,
            $updatedBy
        );
        $product->update($fillableData);
        $product->save();
    }
}
