<?php

namespace App\Models;

use App\Concerns\HasPipelineHelpers;
use App\Concerns\HasPipelineScopes;
use Database\Factories\PipelineFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable([
    'name',
    'description',
    'entity_type',
    'is_default',
    'is_active',
    'position',
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
class Pipeline extends Model
{
    /**
     * @use HasFactory<PipelineFactory>
     * @use SoftDeletes<SoftDeletes>
     * @use HasPipelineScopes<HasPipelineScopes>
     * @use HasPipelineHelpers<HasPipelineHelpers>
     */
    use HasFactory,
        SoftDeletes,
        HasPipelineScopes,
        HasPipelineHelpers;

    public const DEAL_ENTITY = 'deal_entity';
    public const ORDER_ENTITY = 'order_entity';
    public const TASK_ENTITY = 'task_entity';
    public const PROJECT_ENTITY = 'project_entity';
    public const CANDIDATE_ENTITY = 'candidate_entity';
    public const QUOTE_ENTITY = 'quote_entity';

    /**
     * Get the pipeline stages.
     *
     * @return HasMany
     */
    public function stages(): HasMany
    {
        return $this->hasMany(PipelineStage::class)->orderBy('position');
    }

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
            'is_default' => 'boolean',
            'is_active' => 'boolean',
            'is_real' => 'boolean',
            'position' => 'integer',
            'meta' => 'array',
            'restored_at' => 'datetime',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'deleted_at' => 'datetime',
        ];
    }
}
