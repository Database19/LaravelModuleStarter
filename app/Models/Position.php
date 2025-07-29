<?php

namespace App\Models;

use App\Traits\Alertable;
use App\Traits\BelongsToCompany;
use App\Traits\BelongsToTenant;
use App\Traits\Userstamps;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
    use HasFactory, Alertable, Userstamps, BelongsToTenant, BelongsToCompany;

    protected $fillable = [
        'name', 'code', 'description', 'department_id', 'base_salary',
        'is_active'
    ];

    protected $casts = [
        'base_salary' => 'decimal:2',
        'is_active' => 'boolean'
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
