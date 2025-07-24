<?php

namespace App\Models;

use App\Traits\Alertable;
use App\Traits\BelongsToTenant;
use App\Traits\Userstamps;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory, Alertable, Userstamps, BelongsToTenant;

    protected $fillable = [
        'code', 'project_id', 'title', 'description', 'assignee_id',
        'task_status_id', 'start_date', 'due_date', 'completed_date',
        'priority', 'progress', 'estimated_hours', 'actual_hours',
        'parent_id'
    ];

    protected $casts = [
        'start_date' => 'date',
        'due_date' => 'date',
        'completed_date' => 'date',
        'estimated_hours' => 'decimal:2',
        'actual_hours' => 'decimal:2'
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function assignee()
    {
        return $this->belongsTo(User::class, 'assignee_id');
    }

    public function taskStatus()
    {
        return $this->belongsTo(TaskStatus::class);
    }

    public function parent()
    {
        return $this->belongsTo(Task::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Task::class, 'parent_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function taskComments()
    {
        return $this->hasMany(TaskComment::class);
    }

    public function attachments()
    {
        return $this->morphMany(Attachment::class, 'attachable');
    }
}
