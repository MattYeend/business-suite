<?php

namespace App\Concerns;

use App\Models\PipelineStage;
use Illuminate\Database\Eloquent\Builder;

trait HasPipelineStageScopes
{
    /**
     * Scope a query to only include real stages.
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
     * Scope a query to only include active stages.
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
     * Scope a query to only include inactive stages.
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
     * Scope a query to only include terminal stages.
     *
     * @param Builder $query
     *
     * @return Builder
     */
    public function scopeTerminal(Builder $query): Builder
    {
        return $query->where('is_terminal', true);
    }

    /**
     * Scope a query to only include non-terminal stages.
     *
     * @param Builder $query
     *
     * @return Builder
     */
    public function scopeNonTerminal(Builder $query): Builder
    {
        return $query->where('is_terminal', false);
    }

    /**
     * Scope a query to filter by terminal type.
     *
     * @param Builder $query
     * @param string $terminalType
     *
     * @return Builder
     */
    public function scopeTerminalType(
        Builder $query,
        string $terminalType
    ): Builder {
        return $query->where('terminal_type', $terminalType);
    }

    /**
     * Scope a query to only include stages requiring approval.
     *
     * @param Builder $query
     *
     * @return Builder
     */
    public function scopeRequiresApproval(Builder $query): Builder
    {
        return $query->where('requires_approval', true);
    }

    /**
     * Scope a query to only include won terminal stages.
     *
     * @param Builder $query
     *
     * @return Builder
     */
    public function scopeWon(Builder $query): Builder
    {
        return $query->where(
            'terminal_type',
            PipelineStage::WON_TERMINAL
        );
    }

    /**
     * Scope a query to only include lost terminal stages.
     *
     * @param Builder $query
     *
     * @return Builder
     */
    public function scopeLost(Builder $query): Builder
    {
        return $query->where(
            'terminal_type',
            PipelineStage::LOST_TERMINAL
        );
    }

    /**
     * Scope a query to only include completed terminal stages.
     *
     * @param Builder $query
     *
     * @return Builder
     */
    public function scopeCompleted(Builder $query): Builder
    {
        return $query->where(
            'terminal_type',
            PipelineStage::COMPLETED_TERMINAL
        );
    }

    /**
     * Scope a query to only include cancelled terminal stages.
     *
     * @param Builder $query
     *
     * @return Builder
     */
    public function scopeCancelled(Builder $query): Builder
    {
        return $query->where(
            'terminal_type',
            PipelineStage::CANCELLED_TERMINAL
        );
    }

    /**
     * Scope a query to only include rejected terminal stages.
     *
     * @param Builder $query
     *
     * @return Builder
     */
    public function scopeRejected(Builder $query): Builder
    {
        return $query->where(
            'terminal_type',
            PipelineStage::REJECTED_TERMINAL
        );
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
}
