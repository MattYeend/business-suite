<?php

namespace App\Models;

use App\Concerns\Companies\HasCompanyAddressHelpers;
use App\Concerns\Companies\HasCompanyAddressScopes;
use Database\Factories\CompanyAddressFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable([
    'company_id',
    'type',
    'address_line_1',
    'address_line_2',
    'city',
    'county',
    'country',
    'postal_code',
    'is_primary',
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
 * @property int $company_id
 * @property string $type
 * @property string $address_line_1
 * @property string|null $address_line_2
 * @property string $city
 * @property string|null $county
 * @property string $country
 * @property string|null $postal_code
 * @property bool $is_primary
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
 * @property-read Company $company
 * @property-read User|null $creator
 * @property-read User|null $updater
 * @property-read User|null $deleter
 * @property-read User|null $restorer
 */
class CompanyAddress extends Model
{
    /**
     * @use HasFactory<CompanyAddressFactory>
     * @use SoftDeletes<SoftDeletes>
     * @use HasCompanyAddressHelpers<HasCompanyAddressHelpers>
     * @use HasCompanyAddressScopes<HasCompanyAddressScopes>
     */
    use HasFactory,
        SoftDeletes,
        HasCompanyAddressHelpers,
        HasCompanyAddressScopes;

    public const TYPE_BILLING = 'billing';
    public const TYPE_BRANCH = 'branch';
    public const TYPE_FACTORY = 'factory';
    public const TYPE_SHIPPING = 'shipping';
    public const TYPE_SHOWROOM = 'showroom';
    public const TYPE_RETAIL = 'retail';
    public const TYPE_OFFICE = 'office';
    public const TYPE_WAREHOUSE = 'warehouse';

    /**
     * Get the user who created the address.
     *
     * @return BelongsTo
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who last updated the address.
     *
     * @return BelongsTo
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Get the user who deleted the address.
     *
     * @return BelongsTo
     */
    public function deleter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }

    /**
     * Get the user who restored the address.
     *
     * @return BelongsTo
     */
    public function restorer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'restored_by');
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string,string>
     */
    protected function casts(): array
    {
        return [
            'is_primary' => 'boolean',
            'is_real' => 'boolean',
            'meta' => 'array',
            'restored_at' => 'datetime',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'deleted_at' => 'datetime',
        ];
    }
}
