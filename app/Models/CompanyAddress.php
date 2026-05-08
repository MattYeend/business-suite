<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyAddress extends Model
{
    /** @use HasFactory<\Database\Factories\CompanyAddressFactory> */
    use HasFactory;

    public const TYPE_BILLING = 'billing';
    public const TYPE_SHIPPING = 'shipping';
    public const TYPE_OFFICE = 'office';
    public const TYPE_WAREHOUSE = 'warehouse';
}
