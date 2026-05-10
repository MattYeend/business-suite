<?php

namespace App\Concerns\Companies;

use App\Models\CompanyPhone;

/**
 * Company phone helper methods.
 *
 * @property bool $is_real
 * @property bool $is_primary
 * @property string $type
 * @property string $number
 * @property int $company_id
 * @property int $id
 *
 * @mixin CompanyPhone
 */
trait HasCompanyPhoneHelpers
{
    use DetectsUKPhoneNumbers;
    use DetectsUSPhoneNumbers;
    use FormatsGenericPhoneNumbers;
    use FormatsInternationalPhoneNumbers;
    use FormatsUKPhoneNumbers;
    use FormatsUSPhoneNumbers;
    use HasCompanyPhoneStateHelpers;
    use HasCompanyPhoneTypeHelpers;

    /**
     * Get a formatted phone number.
     *
     * @param  string|null $format
     *
     * @return string
     */
    public function getFormattedNumber(
        ?string $format = null
    ): string {
        if (! isset($this->number)) {
            return '';
        }

        $cleaned = $this->cleanPhoneNumber(
            $this->number
        );

        if (! isset($cleaned)) {
            return $this->number;
        }

        return $this->formatCleanedNumber(
            $cleaned,
            $format
        );
    }

    /**
     * Format a cleaned phone number.
     *
     * @param  string $cleaned
     * @param  string|null $format
     *
     * @return string
     */
    protected function formatCleanedNumber(
        string $cleaned,
        ?string $format
    ): string {
        $isInternational = str_starts_with($cleaned, '+');

        $digits = ltrim($cleaned, '+');

        return match (true) {
            $this->isUKNumber($digits) => $this->formatUKNumber(
                $digits,
                $format
            ),

            $this->isUSNumber($digits) => $this->formatUSNumber(
                $digits,
                $format,
                $isInternational
            ),

            $isInternational => $this->formatInternationalNumber(
                $cleaned
            ),

            default => $this->formatGenericNumber($digits),
        };
    }
}
