<?php

namespace App\Concerns\Companies;

/**
 * @property string $first_name
 * @property string $last_name
 * @property string|null $mobile
 * @property string|null $phone
 * @property string|null $email
 */
trait HasCompanyContactHelpers
{
    /**
     * Get the contact's full name.
     *
     * @return string
     */
    public function getFullNameAttribute(): string
    {
        return trim("{$this->first_name} {$this->last_name}");
    }

    /**
     * Get the contact's initials.
     *
     * @return string
     */
    public function getInitialsAttribute(): string
    {
        $firstInitial = $this->extractInitial($this->first_name);
        $lastInitial = $this->extractInitial($this->last_name);

        return strtoupper($firstInitial . $lastInitial);
    }

    /**
     * Get the primary contact method.
     *
     * @return string|null
     */
    public function getPrimaryContactMethodAttribute(): ?string
    {
        return $this->email
            ?? $this->mobile
            ?? $this->phone;
    }

    /**
     * Check if contact has complete information.
     *
     * @return bool
     */
    public function isComplete(): bool
    {
        return $this->hasRequiredFields();
    }

    /**
     * Extract the first character from a string.
     *
     * @param  string|null $value
     *
     * @return string
     */
    private function extractInitial(?string $value): string
    {
        return $value ? substr($value, 0, 1) : '';
    }

    /**
     * Check if all required contact fields are present.
     *
     * @return bool
     */
    private function hasRequiredFields(): bool
    {
        return isset($this->first_name)
            && isset($this->last_name)
            && isset($this->email);
    }
}
