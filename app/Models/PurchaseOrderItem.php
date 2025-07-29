<?php

namespace App\Models;

use App\Traits\Alertable;
use App\Traits\BelongsToCompany;
use App\Traits\BelongsToTenant;
use App\Traits\Userstamps;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrderItem extends Model
{
    use HasFactory, Userstamps, Alertable, BelongsToTenant, BelongsToCompany;

    protected $fillable = [
        'purchase_order_id', 'product_id', 'quantity', 'received_quantity',
        'unit_cost', 'discount_amount', 'total_cost'
    ];

    protected $casts = [
        'unit_cost' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'total_cost' => 'decimal:2'
    ];

    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
