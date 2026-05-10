<?php

namespace App\Concerns\Companies;

/**
 * Detect US/Canada phone numbers.
 */
trait DetectsUSPhoneNumbers
{
    /**
     * Determine whether the number is a US number.
     *
     * @param  string $digits
     *
     * @return bool
     */
    protected function isUSNumber(string $digits): bool
    {
        return $this->isUSInternational($digits)
            || $this->isUSLocal($digits);
    }

    /**
     * Determine whether the number is an
     * international US number.
     *
     * @param  string $digits
     *
     * @return bool
     */
    protected function isUSInternational(string $digits): bool
    {
        return str_starts_with($digits, '1')
            && strlen($digits) === 11;
    }

    /**
     * Determine whether the number is a
     * local US number.
     *
     * @param  string $digits
     *
     * @return bool
     */
    protected function isUSLocal(string $digits): bool
    {
        return strlen($digits) === 10;
    }
}
