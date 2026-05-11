<?php

namespace App\Concerns\Parts;

use App\Models\Part;
use Illuminate\Database\Eloquent\Builder;

trait HasPartScopes
{
    /**
     * Scope a query to only include real parts.
     *
     * @param Builder $query
     *
     * @return Builder
     */
    public function scopeReal(Builder $query): Builder
    {
        return $query->where('is_real', true);
    }

    /**
     * Scope a query to only include active parts.
     *
     * @param Builder $query
     *
     * @return Builder
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to only include inactive parts.
     *
     * @param Builder $query
     *
     * @return Builder
     */
    public function scopeInactive(Builder $query): Builder
    {
        return $query->where('is_active', false);
    }

    /**
     * Scope a query to only include purchasable parts.
     *
     * @param Builder $query
     *
     * @return Builder
     */
    public function scopePurchasable(Builder $query): Builder
    {
        return $query->where('is_purchasable', true);
    }

    /**
     * Scope a query to only include sellable parts.
     *
     * @param Builder $query
     *
     * @return Builder
     */
    public function scopeSellable(Builder $query): Builder
    {
        return $query->where('is_sellable', true);
    }

    /**
     * Scope a query to only include manufactured parts.
     *
     * @param Builder $query
     *
     * @return Builder
     */
    public function scopeManufactured(Builder $query): Builder
    {
        return $query->where('is_manufactured', true);
    }

    /**
     * Scope a query to only include serialised parts.
     *
     * @param Builder $query
     *
     * @return Builder
     */
    public function scopeSerialised(Builder $query): Builder
    {
        return $query->where('is_serialised', true);
    }

    /**
     * Scope a query to only include batch tracked parts.
     *
     * @param Builder $query
     *
     * @return Builder
     */
    public function scopeBatchTracked(Builder $query): Builder
    {
        return $query->where('is_batch_tracked', true);
    }

    /**
     * Scope a query to filter by status.
     *
     * @param Builder $query
     * @param string $status
     *
     * @return Builder
     */
    public function scopeStatus(Builder $query, string $status): Builder
    {
        return $query->where('status', $status);
    }

    /**
     * Scope a query to filter by type.
     *
     * @param Builder $query
     * @param string $type
     *
     * @return Builder
     */
    public function scopeType(Builder $query, string $type): Builder
    {
        return $query->where('type', $type);
    }

    /**
     * Scope a query to only include raw material parts.
     *
     * @param Builder $query
     *
     * @return Builder
     */
    public function scopeRawMaterials(Builder $query): Builder
    {
        return $query->where('type', Part::TYPE_RAW_MATERIAL);
    }

    /**
     * Scope a query to only include finished good parts.
     *
     * @param Builder $query
     *
     * @return Builder
     */
    public function scopeFinishedGoods(Builder $query): Builder
    {
        return $query->where('type', Part::TYPE_FINISHED_GOOD);
    }

    /**
     * Scope a query to only include consumable parts.
     *
     * @param Builder $query
     *
     * @return Builder
     */
    public function scopeConsumables(Builder $query): Builder
    {
        return $query->where('type', Part::TYPE_CONSUMABLE);
    }

    /**
     * Scope a query to only include spare parts.
     *
     * @param Builder $query
     *
     * @return Builder
     */
    public function scopeSpareParts(Builder $query): Builder
    {
        return $query->where('type', Part::TYPE_SPARE_PART);
    }

    /**
     * Scope a query to only include sub-assembly parts.
     *
     * @param Builder $query
     *
     * @return Builder
     */
    public function scopeSubAssemblies(Builder $query): Builder
    {
        return $query->where('type', Part::TYPE_SUB_ASSEMBLY);
    }

    /**
     * Scope a query to only include discontinued parts.
     *
     * @param Builder $query
     *
     * @return Builder
     */
    public function scopeDiscontinued(Builder $query): Builder
    {
        return $query->where('status', Part::STATUS_DISCONTINUED);
    }

    /**
     * Scope a query to only include pending parts.
     *
     * @param Builder $query
     *
     * @return Builder
     */
    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', Part::STATUS_PENDING);
    }

    /**
     * Scope a query to only include out of stock parts.
     *
     * @param Builder $query
     *
     * @return Builder
     */
    public function scopeOutOfStock(Builder $query): Builder
    {
        return $query->where('status', Part::STATUS_OUT_OF_STOCK);
    }

    /**
     * Scope a query to only include parts with low stock.
     *
     * @param Builder $query
     *
     * @return Builder
     */
    public function scopeLowStock(Builder $query): Builder
    {
        return $query->whereColumn('quantity', '<=', 'min_stock_level');
    }

    /**
     * Scope a query to only include parts that need reordering.
     *
     * @param Builder $query
     *
     * @return Builder
     */
    public function scopeNeedsReorder(Builder $query): Builder
    {
        return $query->whereNotNull('reorder_point')
            ->whereColumn('quantity', '<=', 'reorder_point');
    }

    /**
     * Scope a query to filter by warehouse location.
     *
     * @param Builder $query
     * @param string $location
     *
     * @return Builder
     */
    public function scopeAtWarehouse(Builder $query, string $location): Builder
    {
        return $query->where('warehouse_location', $location);
    }

    /**
     * Scope a query to filter by manufacturer.
     *
     * @param Builder $query
     * @param string $manufacturer
     *
     * @return Builder
     */
    public function scopeByManufacturer(
        Builder $query,
        string $manufacturer
    ): Builder {
        return $query->where('manufacturer', $manufacturer);
    }

    /**
     * Scope a query to filter by brand.
     *
     * @param Builder $query
     * @param string $brand
     *
     * @return Builder
     */
    public function scopeByBrand(Builder $query, string $brand): Builder
    {
        return $query->where('brand', $brand);
    }

    /**
     * Scope a query to order by price.
     *
     * @param Builder $query
     * @param string $direction
     *
     * @return Builder
     */
    public function scopeOrderByPrice(
        Builder $query,
        string $direction = 'asc'
    ): Builder {
        return $query->orderBy('price', $direction);
    }

    /**
     * Scope a query to order by stock quantity.
     *
     * @param Builder $query
     * @param string $direction
     *
     * @return Builder
     */
    public function scopeOrderByQuantity(
        Builder $query,
        string $direction = 'asc'
    ): Builder {
        return $query->orderBy('quantity', $direction);
    }

    /**
     * Scope a query to order by name.
     *
     * @param Builder $query
     * @param string $direction
     *
     * @return Builder
     */
    public function scopeOrderByName(
        Builder $query,
        string $direction = 'asc'
    ): Builder {
        return $query->orderBy('name', $direction);
    }
}
