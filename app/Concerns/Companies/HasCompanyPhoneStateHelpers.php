<?php

namespace App\Concerns\Companies;

/**
 * Company phone state helper methods.
 *
 * @property bool $is_real
 * @property bool $is_primary
 */
trait HasCompanyPhoneStateHelpers
{
    /**
     * Determine whether the phone is real.
     *
     * @return bool
     */
    public function isReal(): bool
    {
        return (bool) $this->is_real;
    }

    /**
     * Determine whether the phone is the primary phone.
     *
     * @return bool
     */
    public function isPrimary(): bool
    {
        return (bool) $this->is_primary;
    }
}
