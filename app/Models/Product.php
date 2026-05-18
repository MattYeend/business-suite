<?php

namespace App\Models;

use App\Concerns\Products\HasProductAccessors;
use App\Concerns\Products\HasProductHelpers;
use App\Concerns\Products\HasProductScopes;
use Database\Factories\ProductFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
// use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

#[Fillable([
    'sku',
    'name',
    'description',
    'price',
    'currency',
    'status',
    'quantity',
    'min_stock_level',
    'max_stock_level',
    'reorder_point',
    'reorder_quantity',
    'lead_time_days',
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

/**
 * @property int $id
 * @property string|null $sku
 * @property string $name
 * @property string|null $description
 * @property float $price
 * @property string $currency
 * @property string $status
 * @property int $quantity
 * @property int $min_stock_level
 * @property int|null $max_stock_level
 * @property int|null $reorder_point
 * @property int|null $reorder_quantity
 * @property int|null $lead_time_days
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 *
 * @property-read Collection|\App\Models\Image $images
 * @property-read Collection|\App\Models\Category $categories
 * @property-read BillOfMaterial|null $billOfMaterial
 * @property-read Collection|BillOfMaterialItem $billOfMaterialItems
 * @property-read Collection|Part $parts
 */
class Product extends Model
{
    /**
     * @use HasFactory<ProductFactory>
     * @use SoftDeletes<SoftDeletes>
     * @use HasProductAccessors<HasProductAccessors>
     * @use HasProductHelpers<HasProductHelpers>
     * @use HasProductScopes<HasProductScopes>
     */
    use HasFactory,
        SoftDeletes,
        HasProductAccessors,
        HasProductHelpers,
        HasProductScopes;

    public const STATUS_ACTIVE = 'active';
    public const STATUS_DISCONTINUED = 'discontinued';
    public const STATUS_PENDING = 'pending';
    public const STATUS_OUT_OF_STOCK = 'out_of_stock';

    /**
     * Get the user who created the product.
     *
     * @return BelongsTo
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who last updated the product.
     *
     * @return BelongsTo
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Get the user who deleted the product.
     *
     * @return BelongsTo
     */
    public function deleter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }

    /**
     * Get the user who restored the product.
     *
     * @return BelongsTo
     */
    public function restorer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'restored_by');
    }

    /**
     * Get all images for this product (polymorphic).
     *
     * @return MorphToMany
     */
    public function images(): MorphToMany
    {
        return $this->morphToMany(Image::class, 'imageable')
            ->withPivot('sort_order', 'is_primary', 'usage_context')
            ->withTimestamps()
            ->orderBy('sort_order');
    }

    // /**
    //  * Get all categories for this product (polymorphic many-to-many).
    //  *
    //  * @return MorphToMany
    //  */
    // public function categories(): MorphToMany
    // {
    //     return $this->morphToMany(Category::class, 'categorizable')
    //         ->withTimestamps()
    //         ->withPivot('sort_order')
    //         ->orderByPivot('sort_order');
    // }

    /**
     * Get the Bill of Material for this product.
     *
     * @return HasOne
     */
    public function billOfMaterial(): HasOne
    {
        return $this->hasOne(BillOfMaterial::class);
    }

    /**
     * Get all BOM items for this product.
     *
     * @return HasMany
     */
    public function billOfMaterialItems(): HasMany
    {
        return $this->hasMany(BillOfMaterialItem::class);
    }

    /**
     * Get all parts used in this product through BOM items.
     */
    public function parts()
    {
        return $this->hasManyThrough(
            Part::class,
            BillOfMaterialItem::class,
            'product_id',   // Foreign key on BillOfMaterialItem table
            'id',           // Foreign key on Part table
            'id',           // Local key on Product table
            'part_id'       // Local key on BillOfMaterialItem table
        )->distinct();
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string,string>
     */
    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'quantity' => 'integer',
            'min_stock_level' => 'integer',
            'max_stock_level' => 'integer',
            'reorder_point' => 'integer',
            'reorder_quantity' => 'integer',
            'lead_time_days' => 'integer',
            'meta' => 'array',
            'restored_at' => 'datetime',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'deleted_at' => 'datetime',
        ];
    }
}
