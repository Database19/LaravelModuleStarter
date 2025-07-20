<?php

namespace App\Models;

use App\Models\Employee;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Payroll extends Model
{
    protected $fillable = [
        'employee_id',
        'pay_period_start_date',
        'pay_period_end_date',
        'payment_date',
        'basic_salary',
        'allowances',
        'deductions',
        'tax_amount',
        'net_salary',
        'status',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'pay_period_start_date' => 'date',
        'pay_period_end_date' => 'date',
        'payment_date' => 'date',
        'basic_salary' => 'decimal:2',
        'allowances' => 'decimal:2',
        'deductions' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'net_salary' => 'decimal:2',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
