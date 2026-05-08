<?php

namespace App\Models;

use App\Concerns\HasCompanyPhoneHelpers;
use App\Concerns\HasCompanyPhoneScopes;
use Database\Factories\CompanyPhoneFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable([
    'company_id',
    'type',
    'number',
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
class CompanyPhone extends Model
{
    /**
     * @use HasFactory<CompanyPhoneFactory>
     * @use SoftDeletes<SoftDeletes>
     * @use HasCompanyPhoneHelpers<HasCompanyPhoneHelpers>
     * @use HasCompanyPhoneScopes<HasCompanyPhoneScopes>
     */
    use HasFactory,
        SoftDeletes,
        HasCompanyPhoneHelpers,
        HasCompanyPhoneScopes;

    public const TYPE_MAIN = 'main';
    public const TYPE_FAX = 'fax';
    public const TYPE_TOLL_FREE = 'toll_free';
    public const TYPE_MOBILE = 'mobile';

    /**
     * Get the company that owns the phone.
     *
     * @return BelongsTo
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get the user who created the phone.
     *
     * @return BelongsTo
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who last updated the phone.
     *
     * @return BelongsTo
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Get the user who deleted the phone.
     *
     * @return BelongsTo
     */
    public function deleter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }

    /**
     * Get the user who restored the phone.
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
