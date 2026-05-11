<?php

namespace App\Concerns;

use Illuminate\Database\Eloquent\Builder;

/**
 * Trait for managing team context on User models.
 *
 * @mixin \Spatie\Permission\Traits\HasRoles
 *
 * @property int|null $team_id
 */
trait HasTeam
{
    /**
     * Check if the user belongs to a team.
     *
     * @return bool
     */
    public function hasTeam(): bool
    {
        return $this->team_id !== null;
    }

    /**
     * Get the user's current team ID.
     *
     * @return int|null
     */
    public function getTeamId(): ?int
    {
        return $this->team_id;
    }

    /**
     * Set the user's team context.
     *
     * @param  int|null $teamId
     *
     * @return self
     */
    public function withTeam(?int $teamId): self
    {
        $this->team_id = $teamId;
        return $this;
    }

    /**
     * Switch user's context to a different team.
     *
     * @param  int $teamId
     *
     * @return self
     */
    public function switchTeam(int $teamId): self
    {
        $this->team_id = $teamId;

        /** @var \Spatie\Permission\Traits\HasRoles $this */
        $this->forgetCachedPermissions();

        return $this;
    }

    /**
     * Scope a query to only include users in a specific team.
     *
     * @param  Builder $query
     * @param  int $teamId
     *
     * @return Builder
     */
    public function scopeInTeam($query, int $teamId): Builder
    {
        return $query->where('team_id', $teamId);
    }

    /**
     * Scope a query to only include users without a team.
     *
     * @param  Builder $query
     *
     * @return Builder
     */
    public function scopeWithoutAssignedTeam($query): Builder
    {
        return $query->whereNull('team_id');
    }
}
