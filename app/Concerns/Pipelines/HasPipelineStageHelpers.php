<?php

namespace App\Concerns\Pipelines;

use App\Models\PipelineStage;

/**
 * Pipeline stage type helper methods.
 *
 * @property bool $is_terminal
 * @property bool $is_active
 * @property string|null $terminal_type
 * @property bool $requires_approval
 * @property bool $is_real
 * @property int|null $sla_hours
 */

trait HasPipelineStageHelpers
{
    /**
     * Check if the stage is terminal.
     *
     * @return bool
     */
    public function isTerminal(): bool
    {
        return $this->is_terminal;
    }

    /**
     * Check if the stage is non-terminal.
     *
     * @return bool
     */
    public function isNonTerminal(): bool
    {
        return ! $this->is_terminal;
    }

    /**
     * Check if the stage is active.
     *
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->is_active;
    }

    /**
     * Check if the stage is inactive.
     *
     * @return bool
     */
    public function isInactive(): bool
    {
        return ! $this->is_active;
    }

    /**
     * Check if the stage requires approval.
     *
     * @return bool
     */
    public function requiresApproval(): bool
    {
        return $this->requires_approval;
    }

    /**
     * Check if the stage is real.
     *
     * @return bool
     */
    public function isReal(): bool
    {
        return $this->is_real;
    }

    /**
     * Determine whether the stage is of a given terminal type.
     *
     * @param  string $terminalType
     *
     * @return bool
     */
    public function isTerminalType(string $terminalType): bool
    {
        return $this->terminal_type === $terminalType;
    }

    /**
     * Check if the stage is a won terminal stage.
     *
     * @return bool
     */
    public function isWon(): bool
    {
        return $this->terminal_type === PipelineStage::WON_TERMINAL;
    }

    /**
     * Check if the stage is a lost terminal stage.
     *
     * @return bool
     */
    public function isLost(): bool
    {
        return $this->terminal_type === PipelineStage::LOST_TERMINAL;
    }

    /**
     * Check if the stage is a completed terminal stage.
     *
     * @return bool
     */
    public function isCompleted(): bool
    {
        return $this->terminal_type === PipelineStage::COMPLETED_TERMINAL;
    }

    /**
     * Check if the stage is a cancelled terminal stage.
     *
     * @return bool
     */
    public function isCancelled(): bool
    {
        return $this->terminal_type === PipelineStage::CANCELLED_TERMINAL;
    }

    /**
     * Check if the stage is a rejected terminal stage.
     *
     * @return bool
     */
    public function isRejected(): bool
    {
        return $this->terminal_type === PipelineStage::REJECTED_TERMINAL;
    }

    /**
     * Check if the stage has an SLA.
     *
     * @return bool
     */
    public function hasSla(): bool
    {
        return $this->sla_hours !== null;
    }

    /**
     * Get all available terminal types.
     *
     * @return array<int,string>
     */
    public static function getTerminalTypes(): array
    {
        return [
            PipelineStage::WON_TERMINAL,
            PipelineStage::LOST_TERMINAL,
            PipelineStage::COMPLETED_TERMINAL,
            PipelineStage::CANCELLED_TERMINAL,
            PipelineStage::REJECTED_TERMINAL,
        ];
    }

    /**
     * Get a human-readable terminal type label.
     *
     * @return string|null
     */
    public function getTerminalTypeLabel(): ?string
    {
        return match ($this->terminal_type) {
            PipelineStage::WON_TERMINAL => 'Won',
            PipelineStage::LOST_TERMINAL => 'Lost',
            PipelineStage::COMPLETED_TERMINAL => 'Completed',
            PipelineStage::CANCELLED_TERMINAL => 'Cancelled',
            PipelineStage::REJECTED_TERMINAL => 'Rejected',
            default => $this->terminal_type ? ucfirst(
                str_replace('_', ' ', $this->terminal_type)
            ) : null,
        };
    }

    /**
     * Get the SLA in days.
     *
     * @return float|null
     */
    public function getSlaDaysAttribute(): ?float
    {
        if ($this->sla_hours === null) {
            return null;
        }

        return round($this->sla_hours / 24, 2);
    }
}
