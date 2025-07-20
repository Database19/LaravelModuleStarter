<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockMovement extends Model
{
    use HasFactory;

    protected $fillable = [
        'reference_number', 'product_id', 'warehouse_id', 'type',
        'quantity', 'quantity_before', 'quantity_after', 'reason',
        'user_id', 'reference_type', 'reference_id', 'movement_date',
        'created_by', 'updated_by'
    ];

    protected $casts = [
        'movement_date' => 'datetime'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function reference()
    {
        return $this->morphTo();
    }
}
