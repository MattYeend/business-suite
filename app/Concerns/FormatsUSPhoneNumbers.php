<?php

namespace App\Concerns;

/**
 * US phone number formatting helpers.
 */
trait FormatsUSPhoneNumbers
{
    /**
     * Format a US phone number.
     *
     * @param  string $digits
     * @param  string|null $format
     * @param  bool $isInternational
     *
     * @return string
     */
    protected function formatUSNumber(
        string $digits,
        ?string $format,
        bool $isInternational
    ): string {
        $digits = $this->normaliseUSDigits($digits);

        if ($this->shouldFormatUSE164($format)) {
            return '+1' . $digits;
        }

        return $this->formatUSDisplay(
            $digits,
            $format,
            $isInternational
        );
    }

    /**
     * Normalise US digits.
     *
     * @param  string $digits
     *
     * @return string
     */
    protected function normaliseUSDigits(
        string $digits
    ): string {
        if (str_starts_with($digits, '1')) {
            return substr($digits, 1);
        }

        return $digits;
    }

    /**
     * Determine whether E164 formatting should be used.
     *
     * @param  string|null $format
     *
     * @return bool
     */
    protected function shouldFormatUSE164(
        ?string $format
    ): bool {
        return $format === 'e164';
    }

    /**
     * Format a US number for display.
     *
     * @param  string $digits
     * @param  string|null $format
     * @param  bool $isInternational
     *
     * @return string
     */
    protected function formatUSDisplay(
        string $digits,
        ?string $format,
        bool $isInternational
    ): string {
        [$areaCode, $exchange, $number] =
            $this->splitUSNumber($digits);

        if ($this->shouldFormatUSInternational(
            $format,
            $isInternational
        )) {
            return "+1 ({$areaCode}) ${exchange}-${number}";
        }

        return "({$areaCode}) {$exchange}-{$number}";
    }

    /**
     * Determine whether international formatting
     * should be used.
     *
     * @param  string|null $format
     * @param  bool $isInternational
     *
     * @return bool
     */
    protected function shouldFormatUSInternational(
        ?string $format,
        bool $isInternational
    ): bool {
        return $format === 'international'
            || $isInternational;
    }

    /**
     * Split a US phone number into parts.
     *
     * @param  string $digits
     *
     * @return array<int,string>
     */
    protected function splitUSNumber(
        string $digits
    ): array {
        return [
            substr($digits, 0, 3),
            substr($digits, 3, 3),
            substr($digits, 6, 4),
        ];
    }
}
