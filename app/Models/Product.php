<?php

namespace App\Models;

use App\Traits\Alertable;
use App\Traits\BelongsToCompany;
use App\Traits\BelongsToTenant;
use App\Traits\Userstamps;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes, Alertable, Userstamps, BelongsToTenant, BelongsToCompany;

    protected $fillable = [
        'name',
        'sku',
        'barcode',
        'description',
        'product_category_id',
        'brand_id',
        'unit_id',
        'type',
        'price',
        'cost',
        'quantity',
        'min_stock',
        'track_stock',
        'is_active'
    ];

    public function category()
    {
        return $this->belongsTo(ProductCategory::class, 'product_category_id');
    }

    public function warehouses()
    {
        return $this->belongsToMany(Warehouse::class, 'warehouse_stocks')->withPivot('quantity', 'rack_location')->withTimestamps();
    }

    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class);
    }

    public function bom()
    {
        return $this->hasOne(Bom::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }
}
