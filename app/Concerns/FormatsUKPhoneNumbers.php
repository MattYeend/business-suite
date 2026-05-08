<?php

namespace App\Concerns;

/**
 * UK phone number formatting helpers.
 */
trait FormatsUKPhoneNumbers
{
    use SplitsUKPhoneNumbers;

    /**
     * Format a UK phone number.
     *
     * @param  string $digits
     * @param  string|null $format
     *
     * @return string
     */
    protected function formatUKNumber(
        string $digits,
        ?string $format
    ): string {
        $digits = $this->normaliseUKDigits($digits);

        if ($this->shouldFormatUKE164($format)) {
            return $this->formatUKE164($digits);
        }

        return $this->formatUKNational($digits);
    }

    /**
     * Format UK E164 number.
     *
     * @param  string $digits
     *
     * @return string
     */
    protected function formatUKE164(
        string $digits
    ): string {
        return '+44 ' . ltrim($digits, '0');
    }

    /**
     * Format national UK number.
     *
     * @param  string $digits
     *
     * @return string
     */
    protected function formatUKNational(
        string $digits
    ): string {
        [$areaCode, $firstPart, $secondPart] =
            $this->splitUKNumber($digits);

        return trim(
            "{$areaCode} {$firstPart} {$secondPart}"
        );
    }

    /**
     * Determine whether E164 formatting should be used.
     *
     * @param  string|null $format
     *
     * @return bool
     */
    protected function shouldFormatUKE164(
        ?string $format
    ): bool {
        return in_array(
            $format,
            ['e164', 'international'],
            true
        );
    }

    /**
     * Normalise UK digits.
     *
     * @param  string $digits
     *
     * @return string
     */
    protected function normaliseUKDigits(
        string $digits
    ): string {
        if (str_starts_with($digits, '44')) {
            return '0' . substr($digits, 2);
        }

        if (str_starts_with($digits, '0')) {
            return $digits;
        }

        return '0' . $digits;
    }
}
