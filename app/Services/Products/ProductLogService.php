<?php

namespace App\Services\Products;

use App\Models\Log;
use App\Models\Product;
use App\Models\User;

class ProductLogService
{
    /**
     * Log product creation.
     *
     * @param  Product $product
     * @param  User $actor
     * @param  int $actorId
     *
     * @return array
     */
    public function logCreation(
        Product $product,
        User $actor,
        int $actorId
    ): array {
        $data = $this->basePartData($product) + [
            'created_at' => now(),
            'created_by' => $actor?->name,
        ];

        Log::log(
            Log::ACTION_CREATE_PRODUCT,
            $data,
            $actorId,
        );

        return $data;
    }

    /**
     * Log a product show event.
     *
     * @param  Product $product
     * @param  User $actor
     * @param  int $actorId
     *
     * @return array
     */
    public function logShow(
        Product $product,
        User $actor,
        int $actorId
    ): array {
        $data = $this->basePartData($product) + [
            'shown_at' => now(),
            'shown_by' => $actor?->name,
        ];

        Log::log(
            Log::ACTION_SHOW_PRODUCT,
            $data,
            $actorId,
        );

        return $data;
    }

    /**
     * Log a product update event.
     *
     * @param  Product $product
     * @param  User $actor
     * @param  int $actorId
     *
     * @return array
     */
    public function logUpdate(
        Product $product,
        User $actor,
        int $actorId
    ): array {
        $data = $this->basePartData($product) + [
            'updated_at' => now(),
            'updated_by' => $actor?->name,
        ];

        Log::log(
            Log::ACTION_UPDATE_PRODUCT,
            $data,
            $actorId,
        );

        return $data;
    }

    /**
     * Log a product deletion event.
     *
     * @param  Product $product
     * @param  User $actor
     * @param  int $actorId
     *
     * @return array
     */
    public function logDeletion(
        Product $product,
        User $actor,
        int $actorId
    ): array {
        $data = $this->basePartData($product) + [
            'deleted_at' => now(),
            'deleted_by' => $actor?->name,
        ];

        Log::log(
            Log::ACTION_DELETE_PRODUCT,
            $data,
            $actorId,
        );

        return $data;
    }

    /**
     * Log product force deletion.
     *
     * @param  Product $product
     * @param  User $actor
     * @param  int $actorId
     *
     * @return array
     */
    public function logForceDeletion(
        Product $product,
        User $actor,
        int $actorId
    ): array {
        $data = $this->basePartData($product) + [
            'force_deleted_at' => now(),
            'force_deleted_by' => $actor?->name,
        ];

        Log::log(
            Log::ACTION_FORCE_DELETE_PRODUCT,
            $data,
            $actorId,
        );

        return $data;
    }

    /**
     * Log a product restoration event.
     *
     * @param  Product $product
     * @param  User $actor
     * @param  int $actorId
     *
     * @return array
     */
    public function logRestoration(
        Product $product,
        User $actor,
        int $actorId
    ): array {
        $data = $this->basePartData($product) + [
            'restored_at' => now(),
            'restored_by' => $actor?->name,
        ];

        Log::log(
            Log::ACTION_RESTORE_PRODUCT,
            $data,
            $actorId,
        );

        return $data;
    }

    /**
     * Log a product import event.
     *
     * @param  array $importData
     * @param  User $actor
     * @param  int $actorId
     *
     * @return array
     */
    public function logImport(
        array $importData,
        User $actor,
        int $actorId
    ): array {
        $data = [
            'imported_at' => now(),
            'imported_by' => $actor?->name,
            'imported_count' => count($importData),
            'imported_data_sample' => array_slice($importData, 0, 5),
        ];

        Log::log(
            Log::ACTION_IMPORT_PRODUCT,
            $data,
            $actorId,
        );

        return $data;
    }

    /**
     * Log a product export event.
     *
     * @param  array $exportData
     * @param  User $actor
     * @param  int $actorId
     *
     * @return array
     */
    public function logExport(
        array $exportData,
        User $actor,
        int $actorId
    ): array {
        $data = [
            'exported_at' => now(),
            'exported_by' => $actor?->name,
            'exported_count' => count($exportData),
            'exported_data_sample' => array_slice($exportData, 0, 5),
        ];

        Log::log(
            Log::ACTION_EXPORT_PRODUCT,
            $data,
            $actorId,
        );

        return $data;
    }

    /**
     * Log a product update event performed by cron.
     *
     * @param  Product $product
     *
     * @return array
     */
    public function logUpdateByCron(
        Product $product,
    ): array {
        $data = $this->basePartData($product) + [
            'updated_at' => now(),
            'updated_by' => 'System (Cron)',
        ];

        Log::log(
            Log::ACTION_PRODUCT_UPDATED_BY_CRON,
            $data,
            null,
        );

        return $data;
    }

    /**
     * Get base product data for logging.
     *
     * @param  Product|null $product
     *
     * @return array
     */
    protected function basePartData(?Product $product): array
    {
        if (! $product) {
            return $this->getNullData();
        }

        return $this->getProductData($product);
    }

    /**
     * Get null product data.
     *
     * @return array
     */
    private function getNullData(): array
    {
        return array_merge(
            $this->getNullBaseData(),
            $this->getNullPriceData(),
            $this->getNullQuantityData(),
            $this->getNullMetaData(),
        );
    }

    /**
     * Get complete product data.
     *
     * @param  Product $product
     *
     * @return array
     */
    private function getProductData(Product $product): array
    {
        return array_merge(
            $this->getBaseData($product),
            $this->getPriceData($product),
            $this->getQuantityData($product),
            $this->getMetaData($product),
        );
    }

    /**
     * Get null base product data.
     *
     * @return array
     */
    private function getNullBaseData(): array
    {
        return [
            'id' => null,
            'sku' => null,
            'name' => null,
            'description' => null,
            'status' => null,
        ];
    }

    /**
     * Get base product data.
     *
     * @param  Product $product
     *
     * @return array
     */
    private function getBaseData(Product $product): array
    {
        return [
            'id' => $product->id,
            'sku' => $product->sku,
            'name' => $product->name,
            'description' => $product->description,
            'status' => $product->status,
        ];
    }

    /**
     * Get null price product data.
     *
     * @return array
     */
    private function getNullPriceData(): array
    {
        return [
            'price' => null,
            'currency' => null,
        ];
    }

    /**
     * Get price product data.
     *
     * @param  Product $product
     *
     * @return array
     */
    private function getPriceData(Product $product): array
    {
        return [
            'price' => $product->price,
            'currency' => $product->currency,
        ];
    }

    /**
     * Get null quantity product data.
     *
     * @return array
     */
    private function getNullQuantityData(): array
    {
        return [
            'quantity' => null,
            'min_stock_level' => null,
            'max_stock_level' => null,
            'reorder_point' => null,
            'reorder_quantity' => null,
            'lead_time_days' => null,
        ];
    }

    /**
     * Get quantity product data.
     *
     * @param  Product $product
     *
     * @return array
     */
    private function getQuantityData(Product $product): array
    {
        return [
            'quantity' => $product->quantity,
            'min_stock_level' => $product->min_stock_level,
            'max_stock_level' => $product->max_stock_level,
            'reorder_point' => $product->reorder_point,
            'reorder_quantity' => $product->reorder_quantity,
            'lead_time_days' => $product->lead_time_days,
        ];
    }

    /**
     * Get null metadata product data.
     *
     * @return array
     */
    private function getNullMetaData(): array
    {
        return [
            'is_real' => null,
            'meta' => null,
        ];
    }

    /**
     * Get metadata product data.
     *
     * @param  Product $product
     *
     * @return array
     */
    private function getMetaData(Product $product): array
    {
        return [
            'is_real' => $product->is_real,
            'meta' => $product->meta,
        ];
    }
}
