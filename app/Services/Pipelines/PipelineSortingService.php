<?php

namespace App\Services\Pipelines;

use Illuminate\Database\Eloquent\Builder;

class PipelineSortingService
{
    /**
     * Apply sorting to query.
     *
     * @param  Builder $query
     * @param  string|null $sortBy
     * @param  string|null $sortDirection
     *
     * @return Builder
     */
    public function applySorting(
        Builder $query,
        ?string $sortBy = 'created_at',
        ?string $sortDirection = 'desc'
    ): Builder {
        $sortDirection = strtolower($sortDirection) === 'asc' ? 'asc' : 'desc';

        return match ($sortBy) {
            'name' => $query->orderBy('name', $sortDirection),
            'entity_type' => $query->orderBy('entity_type', $sortDirection),
            'position' => $query->orderBy('position', $sortDirection),
            'is_default' => $query->orderBy('is_default', $sortDirection),
            'is_active' => $query->orderBy('is_active', $sortDirection),
            'is_real' => $query->orderBy('is_real', $sortDirection),
            'created_at' => $query->orderBy('created_at', $sortDirection),
            'updated_at' => $query->orderBy('updated_at', $sortDirection),
            default => $query->orderBy('created_at', $sortDirection),
        };
    }

    /**
     * Get available sort fields.
     *
     * @return array
     */
    public function getAvailableSortFields(): array
    {
        return [
            'name' => 'Name',
            'entity_type' => 'Entity Type',
            'position' => 'Position',
            'is_default' => 'Default',
            'is_active' => 'Active',
            'is_real' => 'Real',
            'created_at' => 'Created Date',
            'updated_at' => 'Updated Date',
        ];
    }
}
