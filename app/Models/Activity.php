<?php
// app/Models/Activity.php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    use HasFactory, BelongsToTenant;

    protected $fillable = [
        'type',
        'subject',
        'description',
        'scheduled_at',
        'completed_at',
        'status',
        'customer_id',
        'deal_id',
        'user_id'
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'completed_at' => 'datetime'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function deal()
    {
        return $this->belongsTo(Deal::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeOverdue($query)
    {
        return $query->where('status', 'pending')
                    ->where('scheduled_at', '<', now());
    }
}
