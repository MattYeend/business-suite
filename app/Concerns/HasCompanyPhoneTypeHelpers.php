<?php

namespace App\Concerns;

use App\Models\CompanyPhone;

/**
 * Company phone type helper methods.
 *
 * @property string $type
 */
trait HasCompanyPhoneTypeHelpers
{
    /**
     * Determine whether the phone is of a given type.
     *
     * @param  string $type
     *
     * @return bool
     */
    public function isType(string $type): bool
    {
        return $this->type === $type;
    }

    /**
     * Determine whether the phone is a main phone.
     *
     * @return bool
     */
    public function isMain(): bool
    {
        return $this->type === CompanyPhone::TYPE_MAIN;
    }

    /**
     * Determine whether the phone is a fax number.
     *
     * @return bool
     */
    public function isFax(): bool
    {
        return $this->type === CompanyPhone::TYPE_FAX;
    }

    /**
     * Determine whether the phone is a toll-free number.
     *
     * @return bool
     */
    public function isTollFree(): bool
    {
        return $this->type === CompanyPhone::TYPE_TOLL_FREE;
    }

    /**
     * Determine whether the phone is a mobile number.
     *
     * @return bool
     */
    public function isMobile(): bool
    {
        return $this->type === CompanyPhone::TYPE_MOBILE;
    }

    /**
     * Get all available phone types.
     *
     * @return array<int,string>
     */
    public static function getTypes(): array
    {
        return [
            CompanyPhone::TYPE_MAIN,
            CompanyPhone::TYPE_FAX,
            CompanyPhone::TYPE_TOLL_FREE,
            CompanyPhone::TYPE_MOBILE,
        ];
    }

    /**
     * Get a human-readable type label.
     *
     * @return string
     */
    public function getTypeLabel(): string
    {
        return match ($this->type) {
            CompanyPhone::TYPE_MAIN => 'Main',
            CompanyPhone::TYPE_FAX => 'Fax',
            CompanyPhone::TYPE_TOLL_FREE => 'Toll Free',
            CompanyPhone::TYPE_MOBILE => 'Mobile',
            default => $this->type ? ucfirst(str_replace('_', ' ', $this->type)) : null,
        };
    }
}
