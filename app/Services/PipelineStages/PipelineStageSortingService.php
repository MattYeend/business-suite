<?php

namespace App\Services\PipelineStages;

use Illuminate\Database\Eloquent\Builder;

class PipelineStageSortingService
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
            'position' => $query->orderBy('position', $sortDirection),
            'colour' => $query->orderBy('colour', $sortDirection),
            'is_terminal' => $query->orderBy('is_terminal', $sortDirection),
            'terminal_type' => $query->orderBy('terminal_type', $sortDirection),
            'probability' => $query->orderBy('probability', $sortDirection),
            'sla_hours' => $query->orderBy('sla_hours', $sortDirection),
            'requires_approval' => $query->orderBy(
                'requires_approval',
                $sortDirection
            ),
            'is_real' => $query->orderBy('is_real', $sortDirection),
            'created_at' => $query->orderBy('created_at', $sortDirection),
            'updated_at' => $query->orderBy('updated_at', $sortDirection),
            default => $query->orderBy('position', $sortDirection),
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
            'position' => 'Position',
            'colour' => 'Colour',
            'is_terminal' => 'Terminal',
            'terminal_type' => 'Terminal Type',
            'probability' => 'Probability',
            'sla_hours' => 'SLA Hours',
            'requires_approval' => 'Requires Approval',
            'is_real' => 'Real',
            'created_at' => 'Created Date',
            'updated_at' => 'Updated Date',
        ];
    }
}
