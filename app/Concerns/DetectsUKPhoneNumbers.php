<?php

namespace App\Concerns;

/**
 * Detect UK phone numbers.
 */
trait DetectsUKPhoneNumbers
{
    /**
     * Determine whether the number is a UK number.
     *
     * @param  string $digits
     *
     * @return bool
     */
    protected function isUKNumber(string $digits): bool
    {
        if (str_starts_with($digits, '44')) {
            return strlen($digits) === 12;
        }

        if (str_starts_with($digits, '0')) {
            return strlen($digits) === 11;
        }

        return strlen($digits) === 10 && ! str_starts_with($digits, '1');
    }
}
