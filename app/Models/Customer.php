<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name', 'email', 'phone', 'address', 'company_name',
        'type', 'tax_id', 'is_active', 'created_by', 'updated_by'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'type' => 'string'
    ];

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function salesOrders()
    {
        return $this->hasMany(SalesOrder::class);
    }

    public function projects()
    {
        return $this->hasMany(Project::class);
    }
}
