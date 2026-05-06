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
 */
trait HasCompanyHelpers
{
    /**
     * Get the full address as a formatted string.
     *
     * @return null|string
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

        return empty($parts) ? null : implode(', ', $parts);
    }

    // /**
    //  * Get primary email (fallback to model email).
    //  *
    //  * @return null|string
    //  */
    // public function getPrimaryEmailAttribute(): ?string
    // {
    //     return $this->primaryContact('email')?->value ?? $this->email;
    // }

    // /**
    //  * Get primary phone (fallback to model phone).
    //  *
    //  * @return null|string
    //  */
    // public function getPrimaryPhoneAttribute(): ?string
    // {
    //     return $this->primaryContact('phone')?->value ?? $this->phone;
    // }

    // /**
    //  * Get primary website (fallback to model website).
    //  *
    //  * @return null|string
    //  */
    // public function getPrimaryWebsiteAttribute(): ?string
    // {
    //     $contact = $this->primaryContact('website');

    //     if ($contact) {
    //         return $contact->formatted_value;
    //     }

    //     return $this->formatWebsiteUrl($this->website);
    // }

    /**
     * Get employee count category.
     *
     * @return null|string
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
     * @return null|string
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
     * @param  null|string $website
     *
     * @return null|string
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
