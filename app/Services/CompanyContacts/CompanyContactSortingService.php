<?php

namespace App\Services\CompanyContacts;

use Illuminate\Database\Eloquent\Builder;

class CompanyContactSortingService
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
        $sortBy = $sortBy ?? 'created_at';
        $sortDirection = strtolower($sortDirection) === 'asc' ? 'asc' :
            'desc';

        return match ($sortBy) {
            'first_name' => $query->orderBy('first_name', $sortDirection),
            'last_name' => $query->orderBy('last_name', $sortDirection),
            'phone' => $query->orderBy('phone', $sortDirection),
            'mobile' => $query->orderBy('mobile', $sortDirection),
            'email' => $query->orderBy('email', $sortDirection),
            'job_title' => $query->orderBy('job_title', $sortDirection),
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
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'phone' => 'Phone',
            'mobile' => 'Mobile',
            'email' => 'Email',
            'job_title' => 'Job Title',
            'created_at' => 'Created Date',
            'updated_at' => 'Updated Date',
        ];
    }
}
