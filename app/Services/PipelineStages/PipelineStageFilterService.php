<?php

namespace App\Services\PipelineStages;

use Illuminate\Database\Eloquent\Builder;

class PipelineStageFilterService
{
    /**
     * Apply search filter to query.
     *
     * @param  Builder $query
     * @param  string|null $search
     *
     * @return Builder
     */
    public function applySearch(Builder $query, ?string $search): Builder
    {
        if ($search === null) {
            return $query;
        }

        return $query->where(function (Builder $q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
                ->orWhere('colour', 'like', "%{$search}%")
                ->orWhere('position', 'like', "{$search}")
                ->orWhere('terminal_type', 'like', "{$search}")
                ->orWhere('sla_hours', 'like', "{$search}")
                ->orWhere('requires_approval', 'like', "{$search}");
        });
    }

    /**
     * Apply status filter to query.
     *
     * @param  Builder $query
     * @param  string|null $status
     *
     * @return Builder
     */
    public function applyStatus(Builder $query, ?string $status): Builder
    {
        if ($status === null) {
            return $query;
        }

        return match ($status) {
            'real' => $query->where('is_real', true),
            'test' => $query->where('is_real', false),
            default => $query,
        };
    }

    /**
     * Apply all filters to query.
     *
     * @param  Builder $query
     * @param  array $filters
     *
     * @return Builder
     */
    public function applyAll(Builder $query, array $filters): Builder
    {
        $query = $this->applySearch($query, $filters['search'] ?? null);

        return $this->applyStatus($query, $filters['status'] ?? null);
    }
}
