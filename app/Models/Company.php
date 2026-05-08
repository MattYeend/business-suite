<?php

namespace App\Models;

use App\Concerns\HasCompanyHelpers;
use App\Concerns\HasCompanyScopes;
use Database\Factories\CompanyFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
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
     * @use HasCompanyHelpers<HasCompanyHelpers>
     * @use HasCompanyScopes<HasCompanyScopes>
     */
    use HasFactory,
        SoftDeletes,
        HasCompanyHelpers,
        HasCompanyScopes;

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

    /**
     * Get all contacts for the company.
     *
     * @return HasMany
     */
    public function companyContacts(): HasMany
    {
        return $this->hasMany(CompanyContact::class)->ordered();
    }

    /**
     * Get all phone related information for the company.
     *
     * @return HasMany
     */
    public function companyPhones(): HasMany
    {
        return $this->hasMany(CompanyPhone::class)->ordered();
    }

    /**
     * Get all address related information for the company.
     *
     * @return HasMany
     */
    public function companyAddresses(): HasMany
    {
        return $this->hasMany(CompanyAddress::class)->ordered();
    }

    /**
     * Get contacts of a specific type.
     *
     * @param  string $type
     *
     * @return HasMany
     */
    public function contactsOfType(string $type): HasMany
    {
        return $this->hasMany(CompanyContact::class)
            ->where('type', $type)
            ->ordered();
    }

    /**
     * Get phones of a specific type.
     *
     * @param  string $type
     *
     * @return HasMany
     */
    public function phonesOfType(string $type): HasMany
    {
        return $this->hasMany(CompanyPhone::class)
            ->where('type', $type)
            ->ordered();
    }

    /**
     * Get addresses of a specific type.
     *
     * @param  string $type
     *
     * @return HasMany
     */
    public function addressesesOfType(string $type): HasMany
    {
        return $this->hasMany(CompanyAddress::class)
            ->where('type', $type)
            ->ordered();
    }

    /**
     * Get primary contact of a specific type.
     *
     * @param  string $type
     *
     * @return CompanyContact|null
     */
    public function primaryContact(string $type): ?CompanyContact
    {
        return $this->contactsOfType($type)
            ->where('is_primary', true)
            ->first();
    }

    /**
     * Get primary phone of a specific type.
     *
     * @param  string $type
     *
     * @return CompanyPhone|null
     */
    public function primaryPhones(string $type): ?CompanyPhone
    {
        return $this->phonesOfType($type)
            ->where('is_primary', true)
            ->first();
    }

    /**
     * Get primary address of a specific type.
     *
     * @param  string $type
     *
     * @return CompanyAddress|null
     */
    public function primaryAddresses(string $type): ?CompanyAddress
    {
        return $this->addressesOfType($type)
            ->where('is_primary', true)
            ->first();
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
