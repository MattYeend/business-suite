<?php

namespace App\Concerns\Parts;

/**
 * Part lead time helpers.
 */
trait HasPartLeadTimes
{
    /**
     * Check if part has lead time.
     *
     * @return bool
     */
    public function hasLeadTime(): bool
    {
        return $this->lead_time_days !== null;
    }

    /**
     * Get lead time in weeks.
     *
     * @return float|null
     */
    public function getLeadTimeWeeksAttribute(): ?float
    {
        if (! $this->hasLeadTime()) {
            return null;
        }

        return round($this->lead_time_days / 7, 2);
    }
}
