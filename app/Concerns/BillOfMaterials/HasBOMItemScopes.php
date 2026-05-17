<?php

namespace App\Concerns\BillOfMaterials;

use Illuminate\Database\Eloquent\Builder;

trait HasBOMItemScopes
{
    /**
     * Scope a query to only include real BOM Item.
     *
     * @param  Builder $query
     *
     * @return Builder
     */
    public function scopeReal(Builder $query): Builder
    {
        return $query->where('is_real', true);
    }

    /**
     * Scope: Required items only (not optional).
     *
     * @param  Builder $query
     *
     * @return Builder
     */
    public function scopeRequired(Builder $query): Builder
    {
        return $query->where('is_optional', false);
    }

    /**
     * Scope: Optional items only.
     *
     * @param  Builder $query
     *
     * @return Builder
     */
    public function scopeOptional(Builder $query): Builder
    {
        return $query->where('is_optional', true);
    }

    /**
     * Scope: Order by sequence.
     *
     * @param  Builder $query
     *
     * @return Builder
     */
    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sequence');
    }
}
