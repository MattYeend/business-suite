<?php

namespace App\Services\Users;

use Illuminate\Database\Eloquent\Builder;

/**
 * Applies various filters to user queries.
 */
class UserFilterService
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
                ->orWhere('email', 'like', "%{$search}%")
                ->orWhere('phone', 'like', "%{$search}%");
        });
    }

    /**
     * Apply team filter to query.
     *
     * @param  Builder $query
     * @param  int|null $teamId
     *
     * @return Builder
     */
    public function applyTeam(Builder $query, ?int $teamId): Builder
    {
        if ($teamId === null) {
            return $query;
        }

        return $query->where('team_id', $teamId);
    }

    /**
     * Apply role filter to query.
     *
     * @param  Builder $query
     * @param  string|null $role
     *
     * @return Builder
     */
    public function applyRole(Builder $query, ?string $role): Builder
    {
        if ($role === null) {
            return $query;
        }

        return $this->filterByRole($query, $role);
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
        $query = $this->applyTeam($query, $filters['team_id'] ?? null);
        $query = $this->applyRole($query, $filters['role'] ?? null);

        return $this->applyStatus($query, $filters['status'] ?? null);
    }

    /**
     * Filter query by role type.
     *
     * @param  Builder $query
     * @param  string $role
     *
     * @return Builder
     */
    private function filterByRole(Builder $query, string $role): Builder
    {
        return match ($role) {
            'super_admin' => $query->where('is_super_admin', true),
            'admin' => $query->where('is_admin', true),
            'user' => $query->where('is_user', true),
            default => $query->whereHas(
                'roles',
                fn ($q) => $q->where('name', $role)
            ),
        };
    }
}
