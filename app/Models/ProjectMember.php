<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectMember extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id', 'user_id', 'role', 'joined_date', 'left_date',
        'is_active', 'created_by', 'updated_by'
    ];

    protected $casts = [
        'joined_date' => 'date',
        'left_date' => 'date',
        'is_active' => 'boolean'
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
