<?php

namespace App\Models;

use Database\Factories\ImageFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

#[Fillable([
    'file_name',
    'file_path',
    'disk',
    'mime_type',
    'file_size',
    'width',
    'height',
    'alt_text',
    'title',
    'description',
    'uploaded_by',
    'is_real',
    'meta',
    'created_by',
    'created_at',
    'updated_by',
    'updated_at',
    'deleted_by',
    'deleted_at',
    'restored_by',
    'restored_at',
])]

class Image extends Model
{
    /**
     * @use HasFactory<ImageFactory>
     * @use SoftDeletes<SoftDeletes>
     */
    use HasFactory,
        SoftDeletes;

    /**
     * Get the user who uploaded this image.
     *
     * @return BelongsTo
     */
    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    /**
     * Get the user who created this record.
     *
     * @return BelongsTo
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who last updated the image.
     *
     * @return BelongsTo
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Get the user who deleted the image.
     *
     * @return BelongsTo
     */
    public function deleter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }

    /**
     * Get the user who restored the image.
     *
     * @return BelongsTo
     */
    public function restorer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'restored_by');
    }

    /**
     * Get all parts that use this image.
     *
     * @return MorphToMany
     */
    public function parts(): MorphToMany
    {
        return $this->morphedByMany(Part::class, 'imageable')
            ->withPivot('sort_order', 'is_primary', 'usage_context')
            ->withTimestamps();
    }

    /**
     * Get all products that use this image.
     *
     * @return MorphToMany
     */
    public function products(): MorphToMany
    {
        return $this->morphedByMany(Product::class, 'imageable')
            ->withPivot('sort_order', 'is_primary', 'usage_context')
            ->withTimestamps();
    }

    /**
     * Get the full URL to the image.
     *
     * @return string
     *
     * @suppress PhanUndeclaredMethod
     */
    public function getUrlAttribute(): string
    {
        if ($this->disk === 'public') {
            return asset('storage/' . $this->file_path);
        }

        /** @var \Illuminate\Contracts\Filesystem\Cloud $disk */
        $disk = Storage::disk($this->disk);
        return $disk->url($this->file_path);
    }

    /**
     * Get human-readable file size.
     *
     * @return string
     */
    public function getFileSizeHumanAttribute(): string
    {
        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB'];
        $unitCount = count($units) - 1;

        for ($i = 0; $bytes > 1024 && $i < $unitCount; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, 2) . ' ' . $units[$i];
    }

    /**
     * Check if this is an image file (not PDF/other).
     *
     * @return bool
     */
    public function isImage(): bool
    {
        return str_starts_with($this->mime_type ?? '', 'image/');
    }

    /**
     * Check if this is a PDF file.
     *
     * @return bool
     */
    public function isPdf(): bool
    {
        return $this->mime_type === 'application/pdf';
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string,string>
     */
    protected function casts(): array
    {
        return [
            'file_size' => 'integer',
            'width' => 'integer',
            'height' => 'integer',
            'is_real' => 'boolean',
            'meta' => 'array',
            'restored_at' => 'datetime',
        ];
    }
}
