<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

#[Fillable([
    'image_id',
    'imageable_id',
    'imageable_type',
    'sort_order',
    'is_primary',
    'usage_context',
])]

class Imageable extends Pivot
{
    /**
     * Indicates if the model IDs are auto-incrementing.
     */
    public function getIncrementing(): bool
    {
        return true;
    }

    /**
     * Get the image that belongs to this pivot.
     *
     * @return BelongsTo
     */
    public function image(): BelongsTo
    {
        return $this->belongsTo(Image::class);
    }

    /**
     * Get the owning imageable model.
     *
     * @return MorphTo
     */
    public function imageable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Scope a query to only include primary images.
     *
     * @param  Builder $query
     *
     * @return Builder
     */
    public function scopePrimary(Builder $query): Builder
    {
        return $query->where('is_primary', true);
    }

    /**
     * Scope a query to filter by usage context.
     *
     * @param  Builder $query
     * @param  string $context
     *
     * @return Builder
     */
    public function scopeByContext(Builder $query, string $context): Builder
    {
        return $query->where('usage_context', $context);
    }

    /**
     * Scope a query to order by sort order.
     *
     * @param  Builder $query
     *
     * @return Builder
     */
    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order');
    }

    /**
     * Mark this image as the primary image for the parent model.
     *
     * @return bool
     */
    public function markAsPrimary(): bool
    {
        static::where('imageable_id', $this->imageable_id)
            ->where('imageable_type', $this->imageable_type)
            ->where('id', '!=', $this->id)
            ->update(['is_primary' => false]);

        return $this->update(['is_primary' => true]);
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
            'sort_order' => 'integer',
        ];
    }
}
