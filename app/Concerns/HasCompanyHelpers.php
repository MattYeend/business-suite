<?php

namespace App\Concerns;

/**
 * @property string|null $address
 * @property string|null $city
 * @property string|null $region
 * @property string|null $postal_code
 * @property string|null $country
 * @property string|null $email
 * @property string|null $phone
 * @property string|null $website
 * @property int|null $employee_count
 * @property string|null $annual_revenue
 *
 * @property-read string|null $full_address
 * @property-read string|null $primary_email
 * @property-read string|null $primary_phone
 * @property-read string|null $primary_website
 * @property-read string|null $primary_phone_main
 * @property-read string|null $primary_phone_fax
 * @property-read string|null $primary_phone_mobile
 * @property-read string|null $primary_phone_toll_free
 * @property-read string|null $employee_size
 * @property-read string|null $formatted_revenue
 *
 * @method mixed primaryContact(string $type)
 * @method mixed primaryPhone(string $type)
 */
trait HasCompanyHelpers
{
    /**
     * Get the full address as a formatted string.
     *
     * @return string|null
     */
    public function getFullAddressAttribute(): ?string
    {
        $parts = array_filter([
            $this->address,
            $this->city,
            $this->region,
            $this->postal_code,
            $this->country,
        ]);

        if ($parts === []) {
            return null;
        }

        return implode(', ', $parts);
    }

    /**
     * Get primary email (fallback to model email).
     *
     * @return string|null
     */
    public function getPrimaryEmailAttribute(): ?string
    {
        return $this->primaryContact('email')?->value ?? $this->email;
    }

    /**
     * Get primary phone (fallback to model phone).
     *
     * @return string|null
     */
    public function getPrimaryPhoneAttribute(): ?string
    {
        return $this->primaryPhone('main')?->number
            ?? $this->primaryContact('phone')?->value
            ?? $this->phone;
    }

    /**
     * Get primary main phone number.
     *
     * @return string|null
     */
    public function getPrimaryPhoneMainAttribute(): ?string
    {
        return $this->primaryPhone('main')?->number;
    }

    /**
     * Get primary fax number.
     *
     * @return string|null
     */
    public function getPrimaryPhoneFaxAttribute(): ?string
    {
        return $this->primaryPhone('fax')?->number;
    }

    /**
     * Get primary mobile number.
     *
     * @return string|null
     */
    public function getPrimaryPhoneMobileAttribute(): ?string
    {
        return $this->primaryPhone('mobile')?->number;
    }

    /**
     * Get primary toll-free number.
     *
     * @return string|null
     */
    public function getPrimaryPhoneTollFreeAttribute(): ?string
    {
        return $this->primaryPhone('toll_free')?->number;
    }

    /**
     * Get primary website (fallback to model website).
     *
     * @return string|null
     */
    public function getPrimaryWebsiteAttribute(): ?string
    {
        $contact = $this->primaryContact('website');

        if ($contact) {
            return $contact->formatted_value;
        }

        return $this->formatWebsiteUrl($this->website);
    }

    /**
     * Get employee count category.
     *
     * @return string|null
     */
    public function getEmployeeSizeAttribute(): ?string
    {
        return $this->employee_count
            ? $this->categorizeEmployeeSize($this->employee_count)
            : null;
    }

    /**
     * Get formatted annual revenue.
     *
     * @return string|null
     */
    public function getFormattedRevenueAttribute(): ?string
    {
        return $this->annual_revenue
            ? '£' . number_format($this->annual_revenue, 2)
            : null;
    }

    /**
     * Format website URL with https:// prefix if needed.
     *
     * @param  string|null $website
     *
     * @return string|null
     */
    private function formatWebsiteUrl(?string $website): ?string
    {
        return match (true) {
            ! $website => null,
            preg_match('~^https?://~i', $website) === 1 => $website,
            default => 'https://' . $website,
        };
    }

    /**
     * Categorize employee count into size bands.
     *
     * @param  int $count
     *
     * @return string
     */
    private function categorizeEmployeeSize(int $count): string
    {
        return match (true) {
            $count < 10 => 'Micro (1-9)',
            $count < 50 => 'Small (10-49)',
            $count < 250 => 'Medium (50-249)',
            $count < 1000 => 'Large (250-999)',
            default => 'Enterprise (1000+)',
        };
    }
}
