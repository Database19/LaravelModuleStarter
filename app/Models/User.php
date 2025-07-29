<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use App\Models\Company;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name', 'email', 'password', 'employee_id', 'department_id',
        'position_id', 'phone_number', 'hire_date', 'birth_date',
        'gender', 'address', 'employment_status', 'salary', 'manager_id',
        'company_id', 'is_super_admin' // Tambah field untuk super admin
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'hire_date' => 'date',
        'birth_date' => 'date',
        'salary' => 'decimal:2',
        'is_super_admin' => 'boolean'
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function position()
    {
        return $this->belongsTo(Position::class);
    }

    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function subordinates()
    {
        return $this->hasMany(User::class, 'manager_id');
    }

    public function managedWarehouses()
    {
        return $this->hasMany(Warehouse::class, 'manager_id');
    }

    public function managedDepartments()
    {
        return $this->hasMany(Department::class, 'manager_id');
    }

    public function managedProjects()
    {
        return $this->hasMany(Project::class, 'manager_id');
    }

    public function projectMembers()
    {
        return $this->hasMany(ProjectMember::class);
    }

    public function assignedTasks()
    {
        return $this->hasMany(Task::class, 'assignee_id');
    }

    public function taskComments()
    {
        return $this->hasMany(TaskComment::class);
    }

    public function salesOrders()
    {
        return $this->hasMany(SalesOrder::class);
    }

    public function purchaseOrders()
    {
        return $this->hasMany(PurchaseOrder::class);
    }

    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class);
    }

    public function journalEntries()
    {
        return $this->hasMany(JournalEntry::class);
    }

    public function attachments()
    {
        return $this->hasMany(Attachment::class, 'uploaded_by');
    }

    // Scope untuk filter berdasarkan status karyawan
    public function scopeActive($query)
    {
        return $query->where('employment_status', 'active');
    }

    public function scopeInactive($query)
    {
        return $query->where('employment_status', 'inactive');
    }

    public function scopeTerminated($query)
    {
        return $query->where('employment_status', 'terminated');
    }

    public function employee()
    {
        return $this->hasOne(Employee::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Check if user is super admin (can access all companies)
     */
    public function isSuperAdmin(): bool
    {
        return $this->is_super_admin ?? false;
    }

    /**
     * Check if user can access specific company
     */
    public function canAccessCompany($companyId): bool
    {
        // Super admin bisa akses semua company
        if ($this->isSuperAdmin()) {
            return true;
        }

        // Regular user hanya bisa akses company-nya sendiri
        return $this->company_id == $companyId;
    }

    /**
     * Get all companies that user can access
     */
    public function accessibleCompanies()
    {
        if ($this->isSuperAdmin()) {
            return Company::all();
        }

        return collect([$this->company]);
    }

    /**
     * Scope untuk super admin
     */
    public function scopeSuperAdmin($query)
    {
        return $query->where('is_super_admin', true);
    }
}
