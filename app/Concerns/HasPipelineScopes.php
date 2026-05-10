<?php

namespace App\Concerns;

use App\Models\Pipeline;
use Illuminate\Database\Eloquent\Builder;

trait HasPipelineScopes
{
    /**
     * Scope a query to only include real pipelines.
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
     * Scope a query to only include active pipelines.
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
     * Scope a query to only include inactive pipelines.
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
     * Scope a query to only include default pipelines.
     *
     * @param Builder $query
     *
     * @return Builder
     */
    public function scopeDefault(Builder $query): Builder
    {
        return $query->where('is_default', true);
    }

    /**
     * Scope a query to filter by entity type.
     *
     * @param Builder $query
     * @param string $entityType
     *
     * @return Builder
     */
    public function scopeForEntityType(
        Builder $query,
        string $entityType
    ): Builder {
        return $query->where('entity_type', $entityType);
    }

    /**
     * Scope a query to order by position.
     *
     * @param Builder $query
     * @param string $direction
     *
     * @return Builder
     */
    public function scopeOrderByPosition(
        Builder $query,
        string $direction = 'asc'
    ): Builder {
        return $query->orderBy('position', $direction);
    }

    /**
     * Scope a query to only include deal entities.
     *
     * @param  Builder $query
     *
     * @return Builder
     */
    public function scopeDeals(Builder $query): Builder
    {
        return $query->where('type', Pipeline::DEAL_ENTITY);
    }

    /**
     * Scope a query to only include order entities.
     *
     * @param  Builder $query
     *
     * @return Builder
     */
    public function scopeOrderss(Builder $query): Builder
    {
        return $query->where('type', Pipeline::ORDER_ENTITY);
    }

    /**
     * Scope a query to only include task entities.
     *
     * @param  Builder $query
     *
     * @return Builder
     */
    public function scopeTasks(Builder $query): Builder
    {
        return $query->where('type', Pipeline::TASK_ENTITY);
    }

    /**
     * Scope a query to only include project entities.
     *
     * @param  Builder $query
     *
     * @return Builder
     */
    public function scopeProjects(Builder $query): Builder
    {
        return $query->where('type', Pipeline::PROJECT_ENTITY);
    }

    /**
     * Scope a query to only include candidate entities.
     *
     * @param  Builder $query
     *
     * @return Builder
     */
    public function scopeCandidates(Builder $query): Builder
    {
        return $query->where('type', Pipeline::CANDIDATE_ENTITY);
    }

    /**
     * Scope a query to only include quote entities.
     *
     * @param  Builder $query
     *
     * @return Builder
     */
    public function scopeQuotes(Builder $query): Builder
    {
        return $query->where('type', Pipeline::QUOTE_ENTITY);
    }
}
