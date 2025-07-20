<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskStatus extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'color', 'order', 'is_active', 'created_by', 'updated_by'
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
