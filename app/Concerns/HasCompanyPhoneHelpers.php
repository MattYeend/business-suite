<?php

namespace App\Concerns;

/**
 * @property string|null $is_real
 * @property string|null $is_primary
 * @property string|null $type
 * @property string|null $number
 */
trait HasCompanyPhoneHelpers
{
    /**
     * Check if the phone is real.
     *
     * @return bool
     */
    public function isReal(): bool
    {
        return (bool) $this->is_real;
    }

    /**
     * Check if the phone is a test/demo phone.
     *
     * @return bool
     */
    public function isTest(): bool
    {
        return ! $this->is_real;
    }

    /**
     * Check if the phone is the primary phone.
     *
     * @return bool
     */
    public function isPrimary(): bool
    {
        return (bool) $this->is_primary;
    }

    /**
     * Check if the phone is of a specific type.
     *
     * @param string $type
     *
     * @return bool
     */
    public function isType(string $type): bool
    {
        return $this->type === $type;
    }

    /**
     * Check if the phone is a main phone.
     *
     * @return bool
     */
    public function isMain(): bool
    {
        return $this->type === 'main';
    }

    /**
     * Check if the phone is a fax number.
     *
     * @return bool
     */
    public function isFax(): bool
    {
        return $this->type === 'fax';
    }

    /**
     * Check if the phone is a toll-free number.
     *
     * @return bool
     */
    public function isTollFree(): bool
    {
        return $this->type === 'toll_free';
    }

    /**
     * Check if the phone is a mobile number.
     *
     * @return bool
     */
    public function isMobile(): bool
    {
        return $this->type === 'mobile';
    }

    /**
     * Get a formatted phone number.
     *
     * @return string
     */
    public function getFormattedNumberAttribute(): string
    {
        return $this->number;
    }

    /**
     * Get the phone type label.
     *
     * @return string
     */
    public function getTypeLabelAttribute(): string
    {
        return match ($this->type) {
            'main' => 'Main',
            'fax' => 'Fax',
            'toll_free' => 'Toll Free',
            'mobile' => 'Mobile',
            default => ucfirst($this->type),
        };
    }
}
