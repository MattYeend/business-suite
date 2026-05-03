<?php

namespace App\Services\Users;

use Illuminate\Database\Eloquent\Builder;

class UserSortingService
{
    /**
     * Apply sorting to the query.
     *
     * @param  Builder $query
     * @param  string|null $sortBy
     * @param  string $sortDirection
     *
     * @return Builder
     */
    public function applySorting(
        Builder $query,
        ?string $sortBy = 'created_at',
        string $sortDirection = 'desc'
    ): Builder {
        $sortBy = $sortBy ?? 'created_at';
        $sortDirection = strtolower($sortDirection) === 'asc' ? 'asc' : 'desc';

        return match ($sortBy) {
            'name' => $query->orderBy('name', $sortDirection),
            'email' => $query->orderBy('email', $sortDirection),
            'team' => $query->orderBy('team_id', $sortDirection),
            'role' => $this->sortByRole($query, $sortDirection),
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
            'email' => 'Email',
            'team' => 'Team',
            'role' => 'Role',
            'created_at' => 'Created Date',
            'updated_at' => 'Updated Date',
        ];
    }

    /**
     * Sort by role (priority: super_admin > admin > user).
     *
     * @param  Builder $query
     * @param  string $sortDirection
     *
     * @return Builder
     */
    protected function sortByRole(
        Builder $query,
        string $sortDirection
    ): Builder {
        if ($sortDirection === 'asc') {
            return $query->orderByRaw('
                CASE
                    WHEN is_super_admin = 1 THEN 3
                    WHEN is_admin = 1 THEN 2
                    WHEN is_user = 1 THEN 1
                    ELSE 0
                END ASC
            ');
        }

        return $query->orderByRaw('
            CASE
                WHEN is_super_admin = 1 THEN 3
                WHEN is_admin = 1 THEN 2
                WHEN is_user = 1 THEN 1
                ELSE 0
            END DESC
        ');
    }
}
