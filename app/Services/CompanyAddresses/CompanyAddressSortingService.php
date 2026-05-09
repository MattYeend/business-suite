<?php

namespace App\Services\CompanyAddresses;

use Illuminate\Database\Eloquent\Builder;

class CompanyAddressSortingService
{
    /**
     * Apply sorting to query.
     *
     * @param  Builder $query
     * @param  string|null $sortBy
     * @param  string|null $sortDirection
     *
     * @return Builder
     */
    public function applySorting(
        Builder $query,
        ?string $sortBy = 'created_at',
        ?string $sortDirection = 'desc'
    ): Builder {
        $sortDirection = strtolower($sortDirection) === 'asc' ? 'asc' : 'desc';

        return match ($sortBy) {
            'address_line_1' => $query->orderBy(
                'address_line_1',
                $sortDirection
            ),
            'address_line_2' => $query->orderBy(
                'address_line_2',
                $sortDirection
            ),
            'type' => $query->orderBy('type', $sortDirection),
            'city' => $query->orderBy('city', $sortDirection),
            'county' => $query->orderBy('county', $sortDirection),
            'postal_code' => $query->orderBy('postal_code', $sortDirection),
            'country' => $query->orderBy('country', $sortDirection),
            'created_at' => $query->orderBy('created_at', $sortDirection),
            'updated_at' => $query->orderBy('updated_at', $sortDirection),
            default => $query->orderBy('created_at', $sortDirection),
        };
    }

    /**
     * Get available sort fields.
     *
     * @return array
     */
    public function getAvailableSortFields(): array
    {
        return [
            'address_line_1' => 'Address Line 1',
            'address_line_2' => 'Address Line 2',
            'type' => 'Type',
            'city' => 'City',
            'county' => 'County',
            'postal_code' => 'Postal Code',
            'country' => 'Country',
            'created_at' => 'Created Date',
            'updated_at' => 'Updated Date',
        ];
    }
}
