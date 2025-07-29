<?php

namespace App\Models;

use App\Traits\Alertable;
use App\Traits\BelongsToCompany;
use App\Traits\BelongsToTenant;
use App\Traits\Userstamps;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    use HasFactory, Alertable, Userstamps, BelongsToTenant, BelongsToCompany;

    protected $fillable = [
        'order_number', 'supplier_id', 'user_id', 'warehouse_id',
        'subtotal', 'tax_amount', 'discount_amount', 'total_amount',
        'status', 'order_date', 'expected_delivery_date', 'received_date',
        'notes'
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'order_date' => 'datetime',
        'expected_delivery_date' => 'datetime',
        'received_date' => 'datetime'
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function items()
    {
        return $this->hasMany(PurchaseOrderItem::class);
    }

    public function stockMovements()
    {
        return $this->morphMany(StockMovement::class, 'reference');
    }
}
