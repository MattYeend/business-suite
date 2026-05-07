<?php

namespace App\Models;

use Database\Factories\CompanyContactFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;

#[Fillable([
    'company_id',
    'first_name',
    'last_name',
    'email',
    'phone',
    'mobile',
    'job_title',
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

class CompanyContact extends Model
{
    /**
     * @use HasFactory<CompanyContactFactory>
     * @use SoftDeletes<SoftDeletes>
     */
    use HasFactory,
        SoftDeletes;

    /**
     * Get the company for contact.
     *
     * @return BelongsTo
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id');
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
     * Scope to filter primary contacts.
     *
     * @param Builder $query
     *
     * @return Builder
     */
    public function scopePrimary(Builder $query): Builder
    {
        return $query->where('is_primary', true);
    }

    /**
     * Scope to filter real contacts.
     *
     * @param Builder $query
     *
     * @return Builder
     */
    public function scopeReal(Builder $query): Builder
    {
        return $query->where('is_real', true);
    }

    /**
     * Scope to filter contacts by company.
     *
     * @param Builder $query
     * @param int $companyId
     *
     * @return Builder
     */
    public function scopeForCompany(Builder $query, int $companyId): Builder
    {
        return $query->where('company_id', $companyId);
    }

    /**
     * Scope to filter contacts with email.
     *
     * @param Builder $query
     *
     * @return Builder
     */
    public function scopeWithEmail(Builder $query): Builder
    {
        return $query->whereNotNull('email');
    }

    /**
     * Scope to filter contacts with phone.
     *
     * @param Builder $query
     *
     * @return Builder
     */
    public function scopeWithPhone(Builder $query): Builder
    {
        return $query->where(function ($q) {
            $q->whereNotNull('phone')
                ->orWhereNotNull('mobile');
        });
    }

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
        $firstInitial = $this->first_name ? substr($this->first_name, 0, 1) : '';
        $lastInitial = $this->last_name ? substr($this->last_name, 0, 1) : '';
        
        return strtoupper($firstInitial . $lastInitial);
    }

    /**
     * Get the primary contact method.
     *
     * @return string|null
     */
    public function getPrimaryContactMethodAttribute(): ?string
    {
        if ($this->email) {
            return $this->email;
        }
        
        if ($this->mobile) {
            return $this->mobile;
        }
        
        return $this->phone;
    }

    /**
     * Check if contact has complete information.
     *
     * @return bool
     */
    public function isComplete(): bool
    {
        return isset($this->first_name) 
            && isset($this->last_name) 
            && isset($this->email);
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
            'restored_at' => 'datetime',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'deleted_at' => 'datetime',
        ];
    }
}
