<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'employee_id_number', 'job_title', 'department_id',
        'join_date', // <-- PERBAIKI DI SINI
        'termination_date', 'basic_salary', 'bank_name',
        'bank_account_number', 'bank_account_holder', 'place_of_birth',
        'date_of_birth', 'gender', 'marital_status', 'address_ktp',
        'address_domicile', 'created_by', 'updated_by'
    ];

    protected $casts = [
        'join_date' => 'date', // <-- PERBAIKI DI SINI
        'termination_date' => 'date',
        'date_of_birth' => 'date',
        'basic_salary' => 'decimal:2',
    ];

    /**
     * Mendapatkan akun user yang terhubung dengan profil karyawan ini.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Mendapatkan departemen tempat karyawan bekerja.
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * Satu Karyawan memiliki banyak riwayat gaji.
     */
    public function payrolls(): HasMany
    {
        return $this->hasMany(Payroll::class);
    }
}
