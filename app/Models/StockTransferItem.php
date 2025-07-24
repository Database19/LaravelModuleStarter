<?php

namespace App\Models;

use App\Traits\Alertable;
use Illuminate\Database\Eloquent\Model;

class StockTransferItem extends Model
{
    use Alertable;

    protected $fillable = ['stock_transfer_id', 'product_id', 'quantity'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
