<?php

namespace App\Services\Products;

use App\Models\Product;

class ProductFormatterService
{
    /**
     * Format a single product with all data.
     *
     * @param  Product $product
     *
     * @return array
     */
    public function format(Product $product): array
    {
        return array_merge(
            $this->getBaseData($product),
            $this->getIdentificationInformation($product),
            $this->getPricingInformation($product),
            $this->getInventoryInformation($product),
            $this->getMetaInformation($product),
            $this->getAuditData($product),
        );
    }

    /**
     * Get the product base data.
     *
     * @param  Product $product
     *
     * @return array
     */
    private function getBaseData(Product $product): array
    {
        return [
            'id' => $product->id,
            'name' => $product->name,
            'description' => $product->description,
            'status' => $product->status,
        ];
    }

    /**
     * Get the product identification information.
     *
     * @param  Product $product
     *
     * @return array
     */
    private function getIdentificationInformation(Product $product): array
    {
        return [
            'sku' => $product->sku,
        ];
    }

    /**
     * Get the product pricing information.
     *
     * @param  Product $product
     *
     * @return array
     */
    private function getPricingInformation(Product $product): array
    {
        return [
            'price' => $product->price,
            'currency' => $product->currency,
        ];
    }

    /**
     * Get the inventory information.
     *
     * @param  Product $product
     *
     * @return array
     */
    private function getInventoryInformation(Product $product): array
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
     * Get the product meta information.
     *
     * @param  Product $product
     *
     * @return array
     */
    private function getMetaInformation(Product $product): array
    {
        return [
            'is_real' => $product->is_real,
            'meta' => $product->meta,
        ];
    }

    /**
     * Get the product audit data.
     *
     * @param  Product $product
     *
     * @return array
     */
    private function getAuditData(Product $product): array
    {
        return [
            'created_at' => $product->created_at,
            'updated_at' => $product->updated_at,
            'deleted_at' => $product->deleted_at,
            'restored_at' => $product->restored_at,
            'created_by' => $product->created_by,
            'updated_by' => $product->updated_by,
            'deleted_by' => $product->deleted_by,
            'restored_by' => $product->restored_by,
        ];
    }
}
