<?php

namespace Modules\Inventory\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

class Stock extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'product_id',
        'warehouse_id',
        'quantity',
        'unit_price',
        'total_value',
        'notes',
        'company_id',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
        'unit_price' => 'decimal:2',
        'total_value' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Product::class);
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Warehouse::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by');
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'updated_by');
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Company::class);
    }

    // Calculate total value automatically
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($stock) {
            $stock->total_value = $stock->quantity * $stock->unit_price;
            if (Auth::check()) {
                $stock->company_id = Auth::user()->company_id ?? 1;
                $stock->created_by = Auth::user()->id;
                $stock->updated_by = Auth::user()->id;
            }
        });

        static::updating(function ($stock) {
            $stock->total_value = $stock->quantity * $stock->unit_price;
            if (Auth::check()) {
                $stock->updated_by = Auth::user()->id;
            }
        });
    }
}
