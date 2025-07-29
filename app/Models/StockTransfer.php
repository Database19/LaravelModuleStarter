<?php

namespace App\Models;

use App\Traits\Alertable;
use App\Traits\BelongsToCompany;
use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class StockTransfer extends Model
{
    use Alertable, BelongsToTenant, BelongsToCompany;

    protected $fillable = [
        'transfer_number', 'source_warehouse_id', 'destination_warehouse_id',
        'transfer_date', 'status', 'notes', 'created_by'
    ];

    protected $casts = ['transfer_date' => 'date'];

    public function sourceWarehouse()
    {
        return $this->belongsTo(Warehouse::class, 'source_warehouse_id');
    }

    public function destinationWarehouse()
    {
        return $this->belongsTo(Warehouse::class, 'destination_warehouse_id');
    }

    public function items()
    {
        return $this->hasMany(StockTransferItem::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
