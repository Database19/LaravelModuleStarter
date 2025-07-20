<?php

namespace Modules\MasterData\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class EmployeesController extends Controller
{
    public function index()
    {
        $employees = Employee::with('user.roles')->latest()->paginate(10);
        return view('masterdata::employees.index', compact('employees'));
    }

    public function create()
    {
        $roles = Role::pluck('name', 'name');
        return view('masterdata::employees.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|exists:roles,name',
            'employee_id_number' => 'required|string|unique:employees,employee_id_number',
            'job_title' => 'required|string|max:255',
            'join_date' => 'required|date',
            'phone_number' => 'nullable|string|max:20',
        ]);

        DB::transaction(function () use ($validated) {
            // 1. Buat User terlebih dahulu
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
            ]);
            $user->assignRole($validated['role']);

            // 2. Buat profil Employee yang terhubung ke User
            $user->employee()->create([
                'employee_id_number' => $validated['employee_id_number'],
                'job_title' => $validated['job_title'],
                'join_date' => $validated['join_date'],
                'phone_number' => $validated['phone_number'],
            ]);
        });

        alert()->success('Berhasil!', 'Karyawan baru telah ditambahkan.');
        return redirect()->route('master-data.employees.index');
    }

    public function edit(Employee $employee)
    {
        $employee->load('user'); // Pastikan data user ter-load
        $roles = Role::pluck('name', 'name');
        return view('masterdata::employees.edit', compact('employee', 'roles'));
    }

    public function update(Request $request, Employee $employee)
    {
        $user = $employee->user;
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|exists:roles,name',
            'employee_id_number' => 'required|string|unique:employees,employee_id_number,' . $employee->id,
            'job_title' => 'required|string|max:255',
            'join_date' => 'required|date',
            'phone_number' => 'nullable|string|max:20',
        ]);

        DB::transaction(function () use ($validated, $employee, $user) {
            // 1. Update data User
            $user->update([
                'name' => $validated['name'],
                'email' => $validated['email'],
            ]);

            if (!empty($validated['password'])) {
                $user->update(['password' => Hash::make($validated['password'])]);
            }
            $user->syncRoles([$validated['role']]);

            // 2. Update profil Employee
            $employee->update([
                'employee_id_number' => $validated['employee_id_number'],
                'job_title' => $validated['job_title'],
                'join_date' => $validated['join_date'],
                'phone_number' => $validated['phone_number'],
            ]);
        });

        alert()->success('Berhasil!', 'Data karyawan telah diperbarui.');
        return redirect()->route('master-data.employees.index');
    }

    public function destroy(Employee $employee)
    {
        // Hapus user, dan profil employee akan terhapus otomatis karena relasi
        $employee->user()->delete();
        alert()->success('Berhasil!', 'Karyawan telah dihapus.');
        return redirect()->route('master-data.employees.index');
    }
}
