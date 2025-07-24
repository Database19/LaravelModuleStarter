<?php

namespace App\Models;

use App\Traits\Alertable;
use App\Traits\BelongsToTenant;
use App\Traits\Userstamps;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use HasFactory, Alertable, Userstamps, BelongsToTenant;

    protected $fillable = [
        'name', 'logo_url', 'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
