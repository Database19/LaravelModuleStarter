<?php
namespace App\Models;

use App\Traits\Alertable;
use Illuminate\Database\Eloquent\Model;

class StockCountItem extends Model
{
    use Alertable;

    protected $fillable = ['stock_count_id', 'product_id', 'system_quantity', 'counted_quantity'];

    public function product() { return $this->belongsTo(Product::class); }
}
