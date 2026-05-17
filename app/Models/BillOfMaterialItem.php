<?php

namespace App\Models;

use Database\Factories\BillOfMaterialItemFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;

#[Fillable([
    'bill_of_material_id',
    'product_id',
    'part_id',
    'quantity',
    'sequence',
    'notes',
    'is_active',
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
     */
    use HasFactory,
        SoftDeletes;

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

    // ==================== SCOPES ====================
    /**
     * Scope a query to only include real BOM Item.
     *
     * @param  Builder $query
     *
     * @return Builder
     */
    public function scopeReal(Builder $query): Builder
    {
        return $query->where('is_real', true);
    }

    /**
     * Scope: Required items only (not optional).
     *
     * @param  Builder $query
     *
     * @return Builder
     */
    public function scopeRequired(Builder $query): Builder
    {
        return $query->where('is_optional', false);
    }

    /**
     * Scope: Optional items only.
     *
     * @param  Builder $query
     *
     * @return Builder
     */
    public function scopeOptional(Builder $query): Builder
    {
        return $query->where('is_optional', true);
    }

    /**
     * Scope: Order by sequence.
     *
     * @param  Builder $query
     *
     * @return Builder
     */
    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sequence');
    }

    // ==================== ACCESSORS ====================

    /**
     * Calculate the total cost for this BOM item.
     *
     * @return float
     */
    public function getTotalCostAttribute(): float
    {
        $partCost = $this->part->cost_price ?? 0;
        $quantityWithScrap = $this->getQuantityWithScrap();
        
        return $quantityWithScrap * $partCost;
    }

    /**
     * Get formatted total cost.
     *
     * @return string
     */
    public function getFormattedTotalCostAttribute(): string
    {
        $currency = $this->product->currency ?? 'GBP';
        $symbols = [
            'GBP' => '£',
            'USD' => '$',
            'EUR' => '€',
        ];
        
        $symbol = $symbols[$currency] ?? $currency . ' ';
        
        return $symbol . number_format($this->total_cost, 2);
    }

    /**
     * Get quantity including scrap allowance.
     *
     * @return float
     */
    public function getQuantityWithScrapAttribute(): float
    {
        return $this->getQuantityWithScrap();
    }

    /**
     * Check if part is available in sufficient quantity.
     *
     * @return bool
     */
    public function getIsAvailableAttribute(): bool
    {
        return $this->part->quantity >= $this->quantity;
    }

    /**
     * Get shortage quantity if part is not available.
     *
     * @return float
     */
    public function getShortageAttribute(): float
    {
        $shortage = $this->quantity - $this->part->quantity;
        return max(0, $shortage);
    }

    // ==================== HELPER METHODS ====================

    /**
     * Calculate quantity including scrap percentage.
     *
     * @return float
     */
    public function getQuantityWithScrap(): float
    {
        if ($this->scrap_percentage === null || $this->scrap_percentage == 0) {
            return $this->quantity;
        }

        return $this->quantity * (1 + ($this->scrap_percentage / 100));
    }

    /**
     * Calculate quantity needed for multiple products.
     *
     * @param  int $productQuantity
     *
     * @return float
     */
    public function calculateQuantityForProducts(int $productQuantity): float
    {
        return $this->getQuantityWithScrap() * $productQuantity;
    }

    /**
     * Check if sufficient stock exists for manufacturing.
     *
     * @param  int $productQuantity
     *
     * @return array
     */
    public function checkStockAvailability(int $productQuantity = 1): array
    {
        $requiredQuantity = $this->calculateQuantityForProducts($productQuantity);
        $availableQuantity = $this->part->quantity;
        $sufficient = $availableQuantity >= $requiredQuantity;

        return [
            'part_id' => $this->part_id,
            'part_name' => $this->part->name,
            'part_sku' => $this->part->sku,
            'required_per_product' => $this->quantity,
            'scrap_percentage' => $this->scrap_percentage,
            'quantity_with_scrap' => $this->getQuantityWithScrap(),
            'total_required' => $requiredQuantity,
            'available' => $availableQuantity,
            'sufficient' => $sufficient,
            'shortage' => max(0, $requiredQuantity - $availableQuantity),
        ];
    }

    /**
     * Get extended cost (quantity × unit cost).
     *
     * @return float
     */
    public function getExtendedCost(): float
    {
        return $this->total_cost;
    }

    /**
     * Get formatted extended cost.
     *
     * @return string
     */
    public function getFormattedExtendedCost(): string
    {
        return $this->formatted_total_cost;
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
