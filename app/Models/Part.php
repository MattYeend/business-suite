<?php

namespace App\Models;

use App\Concerns\Parts\HasPartHelpers;
use App\Concerns\Parts\HasPartScopes;
use Database\Factories\PartFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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
    public const TYPE_CONUMABLE = 'consumable';
    public const TYPE_SPARE_PART = 'spare_part';
    public const TYPE_SUB_ASSEMBLY = 'sub_assebly';

    public const STATUS_ACTIVE = 'active';
    public const STATUS_DISCONTINUED = 'discontinued';
    public const STATUS_PENDING = 'pending';
    public const STATUS_OUT_OF_STOCK = 'out_of_stock';
}
