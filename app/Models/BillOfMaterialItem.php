<?php

namespace App\Models;

use App\Concerns\BillOfMaterials\HasBOMItemAccessors;
use App\Concerns\BillOfMaterials\HasBOMItemHelpers;
use App\Concerns\BillOfMaterials\HasBOMItemScopes;
use Database\Factories\BillOfMaterialItemFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

#[Fillable([
    'bill_of_material_id',
    'product_id',
    'part_id',
    'quantity',
    'sequence',
    'notes',
    'is_optional',
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
 * @property int $bill_of_material_id
 * @property int $product_id
 * @property int $part_id
 * @property float $quantity
 * @property int|null $sequence
 * @property string|null $notes
 * @property bool $is_optional
 * @property string|null $reference_designator
 * @property float|null $scrap_percentage
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property-read BillOfMaterial $billOfMaterial
 * @property-read Product $product
 * @property-read Part $part
 */
class BillOfMaterialItem extends Model
{
    /**
     * @use HasFactory<BillOfMaterialItemFactory>
     * @use SoftDeletes<SoftDeletes>
     * @use HasBOMItemAccessors<HasBOMItemAccessors>
     * @use HasBOMItemHelpers<HasBOMItemHelpers>
     * @use HasBOMItemScopes<HasBOMItemScopes>
     */
    use HasFactory,
        SoftDeletes,
        HasBOMItemAccessors,
        HasBOMItemHelpers,
        HasBOMItemScopes;

    /**
     * Get the user who created the pipeline.
     *
     * @return BelongsTo
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who last updated the pipeline.
     *
     * @return BelongsTo
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Get the user who deleted the pipeline.
     *
     * @return BelongsTo
     */
    public function deleter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }

    /**
     * Get the user who restored the pipeline.
     *
     * @return BelongsTo
     */
    public function restorer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'restored_by');
    }

    /**
     * Get the Bill of Material this item belongs to.
     */
    public function billOfMaterial(): BelongsTo
    {
        return $this->belongsTo(BillOfMaterial::class);
    }

    /**
     * Get the product this BOM item belongs to.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the part used in this BOM item.
     */
    public function part(): BelongsTo
    {
        return $this->belongsTo(Part::class);
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string,string>
     */
    protected function casts(): array
    {
        return [
            'quantity' => 'decimal:4',
            'sequence' => 'integer',
            'is_optional' => 'boolean',
            'scrap_percentage' => 'decimal:2',
            'meta' => 'array',
            'restored_at' => 'datetime',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'deleted_at' => 'datetime',
        ];
    }
}
