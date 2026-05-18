<?php

namespace App\Models;

use App\Concerns\BillOfMaterials\HasBOMAccessors;
use App\Concerns\BillOfMaterials\HasBOMHelpers;
use App\Concerns\BillOfMaterials\HasBOMScopes;
use Database\Factories\BillOfMaterialFactory;
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
    'product_id',
    'bom_number',
    'version',
    'description',
    'is_active',
    'effective_from',
    'effective_to',
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
 * @property int $product_id
 * @property string $bom_number
 * @property string|null $version
 * @property string|null $description
 * @property bool $is_active
 * @property Carbon|null $effective_from
 * @property Carbon|null $effective_to
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 *
 * @property-read Product $product
 * @property-read Collection|\App\Models\BillOfMaterialItem $items
 */
class BillOfMaterial extends Model
{
    /**
     * @use HasFactory<BillOfMaterialFactory>
     * @use SoftDeletes<SoftDeletes>
     * @use HasBOMAccessors<HasBOMAccessors>
     * @use HasBOMHelpers<HasBOMHelpers>
     * @use HasBOMScopes<HasBOMScopes>
     */
    use HasFactory,
        SoftDeletes,
        HasBOMAccessors,
        HasBOMHelpers,
        HasBOMScopes;

    /**
     * Get the user who created the BOM.
     *
     * @return BelongsTo
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who last updated the BOM.
     *
     * @return BelongsTo
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Get the user who deleted the BOM.
     *
     * @return BelongsTo
     */
    public function deleter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }

    /**
     * Get the user who restored the BOM.
     *
     * @return BelongsTo
     */
    public function restorer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'restored_by');
    }

    /**
     * Get the product this BOM belongs to.
     *
     * @return BelongsTo
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get all items in this Bill of Material.
     *
     * @return HasMany
     */
    public function items(): HasMany
    {
        return $this->hasMany(
            BillOfMaterialItem::class
        )->orderBy('sequence');
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string,string>
     */
    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'effective_from' => 'datetime',
            'effective_to' => 'datetime',
            'meta' => 'array',
            'restored_at' => 'datetime',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'deleted_at' => 'datetime',
        ];
    }
}
