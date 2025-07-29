<?php

namespace App\Models;

use App\Traits\Alertable;
use App\Traits\BelongsToCompany;
use App\Traits\BelongsToTenant;
use App\Traits\Userstamps;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Budget extends Model
{
    use HasFactory, SoftDeletes, Alertable, Userstamps, BelongsToTenant, BelongsToCompany;

    protected $fillable = [
        'name',
        'period',
        'start_date',
        'end_date',
        'total_amount',
        'used_amount',
        'remaining_amount',
        'description',
        'status',
        'company_id',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'total_amount' => 'decimal:2',
        'used_amount' => 'decimal:2',
        'remaining_amount' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    // Calculate remaining amount automatically
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($budget) {
            $budget->remaining_amount = $budget->total_amount - $budget->used_amount;
        });
    }
}
