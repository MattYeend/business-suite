<?php

namespace App\Concerns\Companies;

/**
 * Split UK phone numbers.
 */
trait SplitsUKPhoneNumbers
{
    /**
     * UK five-digit area codes.
     */
    protected const UK_FIVE_DIGIT_AREA_CODES = [
        '01204',
        '01274',
        '01277',
        '01297',
        '01298',
    ];

    /**
     * UK three-digit area codes.
     */
    protected const UK_THREE_DIGIT_AREA_CODES = [
        '020',
        '023',
        '024',
        '028',
        '029',
    ];

    /**
     * Split a UK number into formatted parts.
     *
     * @param  string $digits
     *
     * @return array<int,string>
     */
    protected function splitUKNumber(string $digits): array
    {
        $fiveDigit = substr($digits, 0, 5);

        if ($this->hasFiveDigitAreaCode($fiveDigit)) {
            return $this->splitFiveDigitAreaCode(
                $digits,
                $fiveDigit
            );
        }

        return $this->splitRemainingUKNumber($digits);
    }

    /**
     * Split remaining UK number formats.
     *
     * @param  string $digits
     *
     * @return array<int,string>
     */
    protected function splitRemainingUKNumber(
        string $digits
    ): array {
        $threeDigit = substr($digits, 0, 3);

        if ($this->hasThreeDigitAreaCode($threeDigit)) {
            return $this->splitThreeDigitAreaCode(
                $digits,
                $threeDigit
            );
        }

        return $this->splitStandardUKNumber($digits);
    }

    /**
     * Check if the given code is a valid five-digit UK area code.
     *
     * @param  string $code
     *
     * @return bool
     */
    protected function hasFiveDigitAreaCode(
        string $code
    ): bool {
        return in_array(
            $code,
            self::UK_FIVE_DIGIT_AREA_CODES,
            true
        );
    }

    /**
     * Check if the given code is a valid three-digit UK area code.
     *
     * @param  string $code
     *
     * @return bool
     */
    protected function hasThreeDigitAreaCode(
        string $code
    ): bool {
        return in_array(
            $code,
            self::UK_THREE_DIGIT_AREA_CODES,
            true
        );
    }

    /**
     * Split a UK number with a five-digit area code.
     *
     * @param  string $digits
     * @param  string $areaCode
     *
     * @return array<int,string>
     */
    protected function splitFiveDigitAreaCode(
        string $digits,
        string $areaCode
    ): array {
        return [
            $areaCode,
            substr($digits, 5, 3),
            substr($digits, 8, 3),
        ];
    }

    /**
     * Split a UK number with a three-digit area code.
     *
     * @param  string $digits
     * @param  string $areaCode
     *
     * @return array<int,string>
     */
    protected function splitThreeDigitAreaCode(
        string $digits,
        string $areaCode
    ): array {
        return [
            $areaCode,
            substr($digits, 3, 4),
            substr($digits, 7, 4),
        ];
    }

    /**
     * Split a standard four-digit area code UK number.
     *
     * @param  string $digits
     *
     * @return array<int,string>
     */
    protected function splitStandardUKNumber(
        string $digits
    ): array {
        return [
            substr($digits, 0, 4),
            substr($digits, 4, 3),
            substr($digits, 7, 4),
        ];
    }
}
