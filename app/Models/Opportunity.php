<?php

namespace App\Models;

use App\Traits\Alertable;
use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class Opportunity extends Model
{
    use Alertable, BelongsToTenant;

    protected $fillable = [
        'name', 'lead_id', 'customer_id', 'expected_value',
        'expected_closing_date', 'stage', 'owner_id'
    ];

    protected $casts = ['expected_closing_date' => 'date'];

    public function lead() { return $this->belongsTo(Lead::class); }
    public function customer() { return $this->belongsTo(Customer::class); }
    public function owner() { return $this->belongsTo(User::class, 'owner_id'); }
}

