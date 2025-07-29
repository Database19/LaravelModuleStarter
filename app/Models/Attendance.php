<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'date',
        'check_in',
        'check_out',
        'break_start',
        'break_end',
        'total_hours',
        'overtime_hours',
        'status',
        'notes',
        'company_id',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'date' => 'date',
        'check_in' => 'datetime',
        'check_out' => 'datetime',
        'break_start' => 'datetime',
        'break_end' => 'datetime',
        'total_hours' => 'decimal:2',
        'overtime_hours' => 'decimal:2'
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // Scope untuk company
    public function scopeForCompany($query, $companyId)
    {
        return $query->where('company_id', $companyId);
    }

    // Accessor untuk status label
    public function getStatusLabelAttribute()
    {
        return match($this->status) {
            'present' => 'Present',
            'late' => 'Late',
            'absent' => 'Absent',
            'half_day' => 'Half Day',
            'overtime' => 'Overtime',
            default => 'Unknown'
        };
    }
}
