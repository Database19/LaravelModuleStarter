<?php
namespace App\Models;

use App\Models\StockCountItem;
use Illuminate\Database\Eloquent\Model;
use App\Models\Warehouse;
use App\Models\User;
use App\Traits\Alertable;
use App\Traits\BelongsToCompany;
use App\Traits\BelongsToTenant;
use App\Traits\Userstamps;

class StockCount extends Model
{
    use Alertable, Userstamps, BelongsToTenant, BelongsToCompany;

    protected $fillable = [
        'count_number', 'warehouse_id', 'count_date', 'status', 'notes'
    ];
    protected $casts = ['count_date' => 'date'];

    public function warehouse() { return $this->belongsTo(Warehouse::class); }
    public function items() { return $this->hasMany(StockCountItem::class); }
    public function createdBy() { return $this->belongsTo(User::class, 'created_by'); }
}
