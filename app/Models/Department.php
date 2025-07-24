<?php

namespace App\Models;

use App\Traits\Alertable;
use App\Traits\BelongsToTenant;
use App\Traits\Userstamps;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory, Alertable, Userstamps, BelongsToTenant;

    protected $fillable = [
        'name', 'code', 'description', 'manager_id', 'parent_id',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function parent()
    {
        return $this->belongsTo(Department::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Department::class, 'parent_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function positions()
    {
        return $this->hasMany(Position::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
