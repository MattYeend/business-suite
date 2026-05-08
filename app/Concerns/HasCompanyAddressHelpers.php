<?php

namespace App\Concerns;

use App\Models\CompanyAddress;

/**
 * @property bool $is_real
 * @property bool $is_primary
 * @property string $type
 * @property string $address_line_1
 * @property string|null $address_line_2
 * @property string $city
 * @property string|null $county
 * @property string $country
 * @property string $post_code
 * @property int $company_id
 * @property int $id
 *
 * @mixin CompanyAddress
 */
trait HasCompanyAddressHelpers
{
    /**
     * Check if the address is real.
     *
     * @return bool
     */
    public function isReal(): bool
    {
        return (bool) $this->is_real;
    }

    /**
     * Check if the address is a test/demo address.
     *
     * @return bool
     */
    public function isTest(): bool
    {
        return ! $this->is_real;
    }

    /**
     * Check if the address is the primary address.
     *
     * @return bool
     */
    public function isPrimary(): bool
    {
        return (bool) $this->is_primary;
    }

    /**
     * Check if the address is of a specific type.
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
     * Check if this is a billing address.
     *
     * @return bool
     */
    public function isBilling(): bool
    {
        return $this->type === CompanyAddress::TYPE_BILLING;
    }

    /**
     * Check if this is a shipping address.
     *
     * @return bool
     */
    public function isShipping(): bool
    {
        return $this->type === CompanyAddress::TYPE_SHIPPING;
    }

    /**
     * Check if this is an office address.
     *
     * @return bool
     */
    public function isOffice(): bool
    {
        return $this->type === CompanyAddress::TYPE_OFFICE;
    }

    /**
     * Check if this is a warehouse address.
     *
     * @return bool
     */
    public function isWarehouse(): bool
    {
        return $this->type === CompanyAddress::TYPE_WAREHOUSE;
    }

    /**
     * Get the full address as a single string.
     *
     * @param string $separator
     *
     * @return string
     */
    public function getFullAddress(string $separator = ', '): string
    {
        return collect([
            $this->address_line_1,
            $this->address_line_2,
            $this->city,
            $this->county,
            $this->country,
            $this->post_code,
        ])->filter()->join($separator);
    }

    /**
     * Get the full address as an array of lines.
     *
     * @return array
     */
    public function getAddressLines(): array
    {
        return collect([
            $this->address_line_1,
            $this->address_line_2,
            $this->city,
            $this->county,
            $this->post_code,
            $this->country,
        ])->filter()->values()->toArray();
    }

    /**
     * Get all available address types.
     *
     * @return array<int, string>
     */
    public static function getTypes(): array
    {
        return [
            CompanyAddress::TYPE_BILLING,
            CompanyAddress::TYPE_SHIPPING,
            CompanyAddress::TYPE_OFFICE,
            CompanyAddress::TYPE_WAREHOUSE,
        ];
    }

    /**
     * Get a formatted type label.
     *
     * @return string
     */
    public function getTypeLabel(): string
    {
        return match ($this->type) {
            CompanyAddress::TYPE_BILLING => 'Billing',
            CompanyAddress::TYPE_SHIPPING => 'Shipping',
            CompanyAddress::TYPE_OFFICE => 'Office',
            CompanyAddress::TYPE_WAREHOUSE => 'Warehouse',
            default => ucfirst($this->type),
        };
    }
}
