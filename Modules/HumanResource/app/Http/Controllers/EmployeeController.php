<?php

namespace Modules\HumanResource\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Department;
use App\Models\Employee;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class EmployeeController extends Controller
{
    public function index()
    {
        // Ambil data karyawan beserta data user dan departemennya
        $employees = Employee::with(['user', 'department'])->latest()->paginate(15);
        return view('humanresource::employees.index', compact('employees'));
    }

    public function create()
    {
        $departments = Department::all();
        return view('humanresource::employees.create', compact('departments'));
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            // Validasi untuk tabel users
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',

            // Validasi untuk tabel employees
            'job_title' => 'required|string|max:255',
            'hire_date' => 'required|date',
            'basic_salary' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            // 1. Buat data user terlebih dahulu
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);
            // TODO: Berikan role yang sesuai untuk user ini

            // 2. Buat data employee yang terhubung dengan user baru
            $joinDate = \Carbon\Carbon::parse($request->join_date);
            $yearMonth = $joinDate->format('Ym'); // Contoh: 202507

            // Cari nomor urut terakhir untuk bulan dan tahun yang sama
            $latestEmployee = \App\Models\Employee::where('employee_id_number', 'LIKE', $yearMonth . '%')
                                                ->orderBy('employee_id_number', 'desc')
                                                ->first();

            $nextSequence = 1;
            if ($latestEmployee) {
                // Ambil 3 digit terakhir, ubah ke integer, lalu tambah 1
                $lastSequence = (int) substr($latestEmployee->employee_id_number, -3);
                $nextSequence = $lastSequence + 1;
            }

            // Format NIK: YYYYMM + 3 digit nomor urut (e.g., 202507001)
            $employeeIdNumber = $yearMonth . str_pad($nextSequence, 3, '0', STR_PAD_LEFT);

            // --- AKHIR LOGIKA GENERATE NIK ---

            // 2. Buat data employee dengan NIK yang sudah di-generate
            $user->employee()->create([
                'job_title' => $request->job_title,
                'department_id' => $request->department_id,
                'join_date' => $request->join_date, // Pastikan menggunakan join_date
                'basic_salary' => $request->basic_salary,
                'employee_id_number' => $employeeIdNumber, // Gunakan variabel NIK yang baru
                'employment_status' => 'Full-time', // Sesuaikan jika perlu
                'created_by' => auth()->id(),
                'updated_by' => auth()->id(),
            ]);

            DB::commit();
            return redirect()->route('humanresource.employees.index')->with('success', 'Data karyawan berhasil ditambahkan.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function show(Employee $employee)
    {
        $employee->load(['user', 'department']);
        return view('humanresource::employees.show', compact('employee'));
    }

    public function edit(Employee $employee)
    {
        $departments = Department::all();
        // Load relasi user agar bisa diakses di view
        $employee->load('user');

        return view('humanresource::employees.edit', compact('employee', 'departments'));
    }

    /**
     * Memperbarui data karyawan di database.
     */
    public function update(Request $request, Employee $employee)
    {
        $user = $employee->user;

        $request->validate([
            'name' => 'required|string|max:255',
            // Pastikan email unik, tapi abaikan email user saat ini
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            // Password bersifat opsional saat update
            'password' => 'nullable|string|min:8|confirmed',
            'job_title' => 'required|string|max:255',
            'hire_date' => 'required|date',
            'basic_salary' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            // 1. Update data user
            $user->name = $request->name;
            $user->email = $request->email;
            if ($request->filled('password')) {
                $user->password = Hash::make($request->password);
            }
            $user->save();

            // 2. Update data employee
            $employee->update([
                'job_title' => $request->job_title,
                'department_id' => $request->department_id,
                'hire_date' => $request->hire_date,
                'basic_salary' => $request->basic_salary,
                // Isi field lain dari request
                'updated_by' => auth()->id(),
            ]);

            DB::commit();
            return redirect()->route('humanresource.employees.show', $employee)->with('success', 'Data karyawan berhasil diperbarui.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Menghapus data karyawan.
     */
    public function destroy(Employee $employee)
    {
        // Bungkus dalam transaksi untuk memastikan keduanya terhapus
        DB::transaction(function () use ($employee) {
            // Hapus data user, ini akan otomatis menghapus data employee karena onDelete('cascade')
            $employee->user()->delete();
        });

        return redirect()->route('humanresource.employees.index')->with('success', 'Data karyawan berhasil dihapus.');
    }

}
