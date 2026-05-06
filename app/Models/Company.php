<?php

namespace App\Models;

use Database\Factories\CompanyFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

#[Fillable([
    'name',
    'industry_id',
    'email',
    'website',
    'phone',
    'address',
    'city',
    'region',
    'postal_code',
    'country',
    'employee_count',
    'annual_revenue',
    'is_real',
    'meta',
    'created_at',
    'created_by',
    'updated_at',
    'updated_by',
    'deleted_at',
    'deleted_by',
    'restored_at',
    'restored_by',
])]

/**
 * @property int $id
 * @property string $name
 * @property int|null $industry_id
 * @property string|null $email
 * @property string|null $website
 * @property string|null $phone
 * @property string|null $address
 * @property string|null $city
 * @property string|null $region
 * @property string|null $postal_code
 * @property string|null $country
 * @property int|null $employee_count
 * @property float|null $annual_revenue
 * @property bool $is_real
 * @property array|null $meta
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $deleted_by
 * @property int|null $restored_by
 * @property Carbon|null $restored_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * 
 * @property-read CompanyIndustry|null $industry
 * @property-read User|null $creator
 * @property-read User|null $updater
 * @property-read User|null $deleter
 * @property-read User|null $restorer
 * @property-read Collection<int,CompanyContact> $companyContacts
 */

class Company extends Model
{
    /**
     * @use HasFactory<CompanyFactory>
     * @use SoftDeletes<SoftDeletes>
     * */
    use HasFactory, SoftDeletes;

    /**
     * Get the industry for company.
     *
     * @return BelongsTo
     */
    public function industry(): BelongsTo
    {
        return $this->belongsTo(CompanyIndustry::class, 'industry_id');
    }

    /**
     * Get the user who created the company.
     *
     * @return BelongsTo
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who last updated the company.
     *
     * @return BelongsTo
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Get the user who deleted the company.
     *
     * @return BelongsTo
     */
    public function deleter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }

    /**
     * Get the user who restored the company.
     *
     * @return BelongsTo
     */
    public function restorer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'restored_by');
    }

    // /**
    //  * Get all contacts for the company.
    //  *
    //  * @return HasMany
    //  */
    // public function companyContacts(): HasMany
    // {
    //     return $this->hasMany(CompanyContact::class)->ordered();
    // }

    // /**
    //  * Get contacts of a specific type.
    //  *
    //  * @param  string $type
    //  *
    //  * @return HasMany
    //  */
    // public function contactsOfType(string $type): HasMany
    // {
    //     return $this->hasMany(CompanyContact::class)
    //         ->where('type', $type)
    //         ->ordered();
    // }

    // /**
    //  * Get primary contact of a specific type.
    //  *
    //  * @param  string $type
    //  *
    //  * @return null|CompanyContact
    //  */
    // public function primaryContact(string $type): ?CompanyContact
    // {
    //     return $this->contactsOfType($type)
    //         ->where('is_primary', true)
    //         ->first();
    // }

    /**
     * Scope a query to only include real companies.
     *
     * @param  Builder $query
     *
     * @return Builder
     */
    public function scopeReal(Builder $query): Builder
    {
        return $query->where('is_real', true);
    }

    /**
     * Scope a query to only include test companies.
     *
     * @param  Builder $query
     *
     * @return Builder
     */
    public function scopeTest(Builder $query): Builder
    {
        return $query->where('is_real', false);
    }

    /**
     * Scope a query to filter by industry.
     *
     * @param  Builder $query
     * @param  int $industryId
     *
     * @return Builder
     */
    public function scopeInIndustry(Builder $query, int $industryId): Builder
    {
        return $query->where('industry_id', $industryId);
    }

    /**
     * Scope a query to filter by country.
     *
     * @param  Builder $query
     * @param  string $country
     *
     * @return Builder
     */
    public function scopeInCountry(Builder $query, string $country): Builder
    {
        return $query->where('country', $country);
    }

    /**
     * Scope a query to filter by employee count range.
     *
     * @param  Builder $query
     * @param  int $min
     * @param  null|int $max
     *
     * @return Builder
     */
    public function scopeEmployeeCountBetween(Builder $query, int $min, ?int $max = null): Builder
    {
        $query->where('employee_count', '>=', $min);

        if ($max !== null) {
            $query->where('employee_count', '<=', $max);
        }

        return $query;
    }

    /**
     * Scope a query to filter by annual revenue range.
     *
     * @param  Builder $query
     * @param  float $min
     * @param  null|float $max
     *
     * @return Builder
     */
    public function scopeRevenueBetween(Builder $query, float $min, ?float $max = null): Builder
    {
        $query->where('annual_revenue', '>=', $min);

        if ($max !== null) {
            $query->where('annual_revenue', '<=', $max);
        }

        return $query;
    }

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

        return !empty($parts) ? implode(', ', $parts) : null;
    }

    /**
     * Get primary email (fallback to model email).
     *
     * @return null|string
     */
    public function getPrimaryEmailAttribute(): ?string
    {
        return $this->primaryContact('email')?->value ?? $this->email;
    }

    /**
     * Get primary phone (fallback to model phone).
     *
     * @return null|string
     */
    public function getPrimaryPhoneAttribute(): ?string
    {
        return $this->primaryContact('phone')?->value ?? $this->phone;
    }

    /**
     * Get primary website (fallback to model website).
     *
     * @return null|string
     */
    public function getPrimaryWebsiteAttribute(): ?string
    {
        $contact = $this->primaryContact('website');
        
        if ($contact) {
            return $contact->formatted_value;
        }

        if ($this->website && !preg_match('~^https?://~i', $this->website)) {
            return 'https://' . $this->website;
        }

        return $this->website;
    }

    /**
     * Get employee count category.
     *
     * @return null|string
     */
    public function getEmployeeSizeAttribute(): ?string
    {
        if (!$this->employee_count) {
            return null;
        }

        return match (true) {
            $this->employee_count < 10 => 'Micro (1-9)',
            $this->employee_count < 50 => 'Small (10-49)',
            $this->employee_count < 250 => 'Medium (50-249)',
            $this->employee_count < 1000 => 'Large (250-999)',
            default => 'Enterprise (1000+)',
        };
    }

    /**
     * Get formatted annual revenue.
     *
     * @return null|string
     */
    public function getFormattedRevenueAttribute(): ?string
    {
        if (!$this->annual_revenue) {
            return null;
        }

        return '£' . number_format($this->annual_revenue, 2);
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string,string>
     */
    protected function casts(): array
    {
        return [
            'is_real' => 'boolean',
            'meta' => 'array',
            'employee_count' => 'integer',
            'annual_revenue' => 'decimal:2',
            'restored_at' => 'datetime',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'deleted_at' => 'datetime',
        ];
    }
}
