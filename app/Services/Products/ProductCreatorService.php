<?php

namespace App\Services\Products;

use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;

class ProductCreatorService
{
    /**
     * Inject the required services into the creator service.
     *
     * @param ProductDataPreparationService $dataPreparation
     * @param ProductLogService $logService
     */
    public function __construct(
        protected ProductDataPreparationService $dataPreparation,
        protected ProductLogService $logService
    ) {
    }

    /**
     * Create a new product.
     *
     * @param  array $data
     * @param  int $createdBy
     *
     * @return Product
     *
     * @throws ModelNotFoundException
     */
    public function create(array $data, int $createdBy): Product
    {
        $actor = User::findOrFail($createdBy);

        return DB::transaction(function () use ($data, $createdBy, $actor) {
            $product = $this->createCompany($data, $createdBy);
            $this->logService->logCreation($product, $actor, $createdBy);

            return $product;
        });
    }

    /**
     * Create the product record.
     *
     * @param  array $data
     * @param  int $createdBy
     *
     * @return Product
     */
    protected function createCompany(
        array $data,
        int $createdBy
    ): Product {
        $companyData = $this->dataPreparation->prepareForCreation(
            $data,
            $createdBy
        );

        return Product::create($companyData);
    }
}
