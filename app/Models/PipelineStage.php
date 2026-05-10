<?php

namespace App\Models;

use App\Concerns\Pipelines\HasPipelineStageHelpers;
use App\Concerns\Pipelines\HasPipelineStageScopes;
use Database\Factories\PipelineStageFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable([
    'pipeline_id',
    'name',
    'colour',
    'position',
    'is_terminal',
    'terminal_type',
    'probability',
    'sla_hours',
    'requires_approval',
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
 * Pipeline stage model representing individual stages within a pipeline.
 *
 * @property int $id
 * @property int $pipeline_id
 * @property string $name
 * @property string|null $colour
 * @property int $position
 * @property bool $is_terminal
 * @property string|null $terminal_type
 * @property int|null $probability
 * @property int|null $sla_hours
 * @property bool $requires_approval
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
 * @property-read Pipeline $pipeline
 * @property-read User|null $creator
 * @property-read User|null $updater
 * @property-read User|null $deleter
 * @property-read User|null $restorer
 */
class PipelineStage extends Model
{
    /**
     * @use HasFactory<PipelineStageFactory>
     * @use SoftDeletes<SoftDeletes>
     * @use HasPipelineStageHelpers<HasPipelineStageHelpers>
     * @use HasPipelineStageScopes<HasPipelineStageScopes>
     */
    use HasFactory,
        SoftDeletes,
        HasPipelineStageHelpers,
        HasPipelineStageScopes;

    public const WON_TERMINAL = 'won_terminal';
    public const LOST_TERMINAL = 'lost_terminal';
    public const COMPLETED_TERMINAL = 'completed_terminal';
    public const CANCELLED_TERMINAL = 'cancelled_terminal';
    public const REJECTED_TERMINAL = 'rejected_terminal';

    /**
     * Get the pipeline that owns the stage.
     *
     * @return BelongsTo
     */
    public function pipeline(): BelongsTo
    {
        return $this->belongsTo(Pipeline::class);
    }

    /**
     * Get the user who created the stage.
     *
     * @return BelongsTo
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who last updated the stage.
     *
     * @return BelongsTo
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Get the user who deleted the stage.
     *
     * @return BelongsTo
     */
    public function deleter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }

    /**
     * Get the user who restored the stage.
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
            'is_terminal' => 'boolean',
            'is_active' => 'boolean',
            'is_real' => 'boolean',
            'requires_approval' => 'boolean',
            'position' => 'integer',
            'probability' => 'integer',
            'sla_hours' => 'integer',
            'meta' => 'array',
            'restored_at' => 'datetime',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'deleted_at' => 'datetime',
        ];
    }
}
