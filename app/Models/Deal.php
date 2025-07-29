<?php
// app/Models/Deal.php

namespace App\Models;

use App\Traits\BelongsToCompany;
use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Deal extends Model
{
    use HasFactory, BelongsToTenant, BelongsToCompany;

    protected $fillable = [
        'title',
        'description',
        'value',
        'stage',
        'probability',
        'expected_close_date',
        'customer_id',
        'user_id'
    ];

    protected $casts = [
        'value' => 'decimal:2',
        'expected_close_date' => 'date'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function activities()
    {
        return $this->hasMany(Activity::class);
    }

    public function scopeWon($query)
    {
        return $query->where('stage', 'closed_won');
    }

    public function scopeLost($query)
    {
        return $query->where('stage', 'closed_lost');
    }

    public function scopeOpen($query)
    {
        return $query->whereNotIn('stage', ['closed_won', 'closed_lost']);
    }

    public function getProbabilityColorAttribute()
    {
        if ($this->probability >= 80) return 'text-green-600';
        if ($this->probability >= 50) return 'text-yellow-600';
        return 'text-red-600';
    }
}
