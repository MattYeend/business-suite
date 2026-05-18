<?php

namespace App\Models;

use App\Concerns\Parts\HasPartHelpers;
use App\Concerns\Parts\HasPartScopes;
use Database\Factories\PartFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

#[Fillable([
    'sku',
    'part_number',
    'barcode',
    'name',
    'description',
    'brand',
    'manufacturer',
    'type',
    'status',
    'unit_of_measure',
    'height',
    'width',
    'length',
    'weight',
    'volume',
    'colour',
    'material',
    'price',
    'cost_price',
    'currency',
    'tax_rate',
    'tax_code',
    'discount_percentage',
    'quantity',
    'min_stock_level',
    'max_stock_level',
    'reorder_point',
    'reorder_quantity',
    'lead_time_days',
    'warehouse_location',
    'bin_location',
    'is_active',
    'is_purchasable',
    'is_sellable',
    'is_manufactured',
    'is_serialised',
    'is_batch_tracked',
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
 * @property string $sku
 * @property string|null $part_number
 * @property string|null $barcode
 * @property string $name
 * @property string $description
 * @property string|null $brand
 * @property string|null $manufacturer
 * @property string $type
 * @property string $status
 * @property string $unit_of_measure
 * @property float|null $height
 * @property float|null $width
 * @property float|null $length
 * @property float|null $weight
 * @property float|null $volume
 * @property string|null $colour
 * @property string|null $material
 * @property float $price
 * @property float|null $cost_price
 * @property string $currency
 * @property float|null $tax_rate
 * @property string|null $tax_code
 * @property float|null $discount_percentage
 * @property int $quantity
 * @property int $min_stock_level
 * @property int|null $max_stock_level
 * @property int|null $reorder_point
 * @property int|null $reorder_quantity
 * @property int|null $lead_time_days
 * @property string|null $warehouse_location
 * @property string|null $bin_location
 * @property bool $is_active
 * @property bool $is_purchasable
 * @property bool $is_sellable
 * @property bool $is_manufactured
 * @property bool $is_serialised
 * @property bool $is_batch_tracked
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 *
 * @property-read Collection<int,Image> $images
 * @property-read Collection<int,Category> $categories
 * @property-read Collection<int,BillOfMaterialItem> $billOfMaterialItems
 * @property-read Collection<int,Product> $products
 */
class Part extends Model
{
    /**
     * @use HasFactory<PartFactory>
     * @use SoftDeletes<SoftDeletes>
     * @use HasPartHelpers<HasPartHelpers>
     * @use HasPartScopes<HasPartScopes>
     */
    use HasFactory,
        SoftDeletes,
        HasPartHelpers,
        HasPartScopes;

    public const TYPE_RAW_MATERIAL = 'raw_material';
    public const TYPE_FINISHED_GOOD = 'finished_good';
    public const TYPE_CONSUMABLE = 'consumable';
    public const TYPE_SPARE_PART = 'spare_part';
    public const TYPE_SUB_ASSEMBLY = 'sub_assembly';

    public const STATUS_ACTIVE = 'active';
    public const STATUS_DISCONTINUED = 'discontinued';
    public const STATUS_PENDING = 'pending';
    public const STATUS_OUT_OF_STOCK = 'out_of_stock';

    /**
     * Get the user who created the part.
     *
     * @return BelongsTo
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who last updated the part.
     *
     * @return BelongsTo
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Get the user who deleted the part.
     *
     * @return BelongsTo
     */
    public function deleter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }

    /**
     * Get the user who restored the part.
     *
     * @return BelongsTo
     */
    public function restorer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'restored_by');
    }

    /**
     * Get all images for this part.
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

    /**
     * Get the primary image for this part.
     *
     * @return MorphToMany
     */
    public function primaryImage(): MorphToMany
    {
        return $this->images()->wherePivot('is_primary', true);
    }

    /**
     * Get images by usage context.
     *
     * @param string $context
     *
     * @return MorphToMany
     */
    public function imagesByContext(string $context): MorphToMany
    {
        return $this->images()->wherePivot('usage_context', $context);
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string,string>
     */
    protected function casts(): array
    {
        return array_merge(
            $this->decimalCasts(),
            $this->integerCasts(),
            $this->booleanCasts(),
            $this->otherCasts(),
        );
    }

    /**
     * Decimal casts.
     *
     * @return array<string,string>
     */
    protected function decimalCasts(): array
    {
        return [
            'height' => 'decimal:2',
            'width' => 'decimal:2',
            'length' => 'decimal:2',
            'weight' => 'decimal:2',
            'volume' => 'decimal:2',
            'price' => 'decimal:2',
            'cost_price' => 'decimal:2',
            'tax_rate' => 'decimal:2',
            'discount_percentage' => 'decimal:2',
        ];
    }

    /**
     * Integer casts.
     *
     * @return array<string,string>
     */
    protected function integerCasts(): array
    {
        return [
            'quantity' => 'integer',
            'min_stock_level' => 'integer',
            'max_stock_level' => 'integer',
            'reorder_point' => 'integer',
            'reorder_quantity' => 'integer',
            'lead_time_days' => 'integer',
        ];
    }

    /**
     * Boolean casts.
     *
     * @return array<string,string>
     */
    protected function booleanCasts(): array
    {
        return [
            'is_active' => 'boolean',
            'is_purchasable' => 'boolean',
            'is_sellable' => 'boolean',
            'is_manufactured' => 'boolean',
            'is_serialised' => 'boolean',
            'is_batch_tracked' => 'boolean',
            'is_real' => 'boolean',
        ];
    }

    /**
     * Other casts.
     *
     * @return array<string,string>
     */
    protected function otherCasts(): array
    {
        return [
            'meta' => 'array',
            'restored_at' => 'datetime',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'deleted_at' => 'datetime',
        ];
    }
}
