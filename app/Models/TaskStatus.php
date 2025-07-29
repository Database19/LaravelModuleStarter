<?php

namespace App\Models;

use App\Traits\Alertable;
use App\Traits\BelongsToCompany;
use App\Traits\BelongsToTenant;
use App\Traits\Userstamps;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskStatus extends Model
{
    use HasFactory, Alertable, Userstamps, BelongsToTenant, BelongsToCompany;

    protected $fillable = [
        'name', 'color', 'order', 'is_active'
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

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
}
