<?php

namespace App\Concerns\Companies;

/**
 * International phone number formatting helpers.
 */
trait FormatsInternationalPhoneNumbers
{
    /**
     * Format an international phone number.
     *
     * @param  string $number
     *
     * @return string
     */
    protected function formatInternationalNumber(
        string $number
    ): string {
        $digits = ltrim($number, '+');

        if ($this->isShortInternationalNumber($digits)) {
            return '+' . $digits;
        }

        return $this->buildInternationalNumber($digits);
    }

    /**
     * Determine whether the number is too short
     * for advanced formatting.
     *
     * @param  string $digits
     *
     * @return bool
     */
    protected function isShortInternationalNumber(
        string $digits
    ): bool {
        return strlen($digits) <= 10;
    }

    /**
     * Build a formatted international number.
     *
     * @param  string $digits
     *
     * @return string
     */
    protected function buildInternationalNumber(
        string $digits
    ): string {
        $countryCode = $this->extractCountryCode($digits);

        $remaining = substr(
            $digits,
            strlen($countryCode)
        );

        return '+' . $countryCode . ' '
            . $this->formatInternationalGroups($remaining);
    }

    /**
     * Extract the country code.
     *
     * @param  string $digits
     *
     * @return string
     */
    protected function extractCountryCode(
        string $digits
    ): string {
        return substr(
            $digits,
            0,
            min(3, strlen($digits) - 10)
        );
    }

    /**
     * Format international digit groups.
     *
     * @param  string $digits
     *
     * @return string
     */
    protected function formatInternationalGroups(
        string $digits
    ): string {
        return implode(
            ' ',
            str_split($digits, 3)
        );
    }
}
