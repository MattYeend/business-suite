<?php

namespace App\Concerns\Companies;

/**
 * Generic phone number formatting helpers.
 *
 * @property string $number
 */
trait FormatsGenericPhoneNumbers
{
    /**
     * Clean a phone number.
     *
     * Removes all non-numeric characters except
     * a leading plus sign.
     *
     * @param string $number
     *
     * @return string
     */
    protected function cleanPhoneNumber(
        string $number
    ): string {
        return preg_replace(
            '/[^0-9+]/',
            '',
            $number
        ) ?? '';
    }

    /**
     * Format a generic phone number.
     *
     * @param string $digits
     *
     * @return string
     */
    protected function formatGenericNumber(
        string $digits
    ): string {
        $length = strlen($digits);

        if ($length <= 6) {
            return $digits;
        }

        if ($length === 7) {
            return substr($digits, 0, 3)
                . '-'
                . substr($digits, 3);
        }

        return implode(
            ' ',
            str_split(
                $digits,
                min(4, ceil($length / 3))
            )
        );
    }
}
