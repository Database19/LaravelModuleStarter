<?php

namespace Modules\HumanResource\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class AttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $attendances = Attendance::with(['employee'])
                ->forCompany(Auth::user()->company_id ?? 1)
                ->latest();

            return DataTables::of($attendances)
                ->addColumn('employee_name', function ($attendance) {
                    return $attendance->employee->full_name ?? 'N/A';
                })
                ->addColumn('status_badge', function ($attendance) {
                    $class = match($attendance->status) {
                        'present' => 'success',
                        'late' => 'warning',
                        'absent' => 'danger',
                        'half_day' => 'info',
                        'overtime' => 'primary',
                        default => 'secondary'
                    };
                    return '<span class="badge bg-' . $class . '">' . $attendance->status_label . '</span>';
                })
                ->addColumn('action', function ($attendance) {
                    return '
                        <div class="btn-group" role="group">
                            <a href="' . route('humanresource.attendance.show', $attendance->id) . '" class="btn btn-sm btn-info">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="' . route('humanresource.attendance.edit', $attendance->id) . '" class="btn btn-sm btn-warning">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="' . route('humanresource.attendance.destroy', $attendance->id) . '" method="POST" style="display: inline;">
                                ' . csrf_field() . method_field('DELETE') . '
                                <button type="submit" class="btn btn-sm btn-danger confirm-delete-button">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    ';
                })
                ->rawColumns(['status_badge', 'action'])
                ->make(true);
        }

        return view('humanresource::attendance.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $employees = Employee::where('company_id', Auth::user()->company_id ?? 1)
            ->where('is_active', true)
            ->orderBy('first_name')
            ->get();

        return view('humanresource::attendance.create', compact('employees'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'date' => 'required|date',
            'check_in' => 'required',
            'check_out' => 'nullable',
            'break_start' => 'nullable',
            'break_end' => 'nullable',
            'status' => 'required|in:present,absent,late,half_day,overtime',
            'notes' => 'nullable|string|max:500'
        ]);

        Attendance::create([
            'employee_id' => $request->employee_id,
            'date' => $request->date,
            'check_in' => $request->check_in,
            'check_out' => $request->check_out,
            'break_start' => $request->break_start,
            'break_end' => $request->break_end,
            'status' => $request->status,
            'notes' => $request->notes,
            'company_id' => Auth::user()->company_id ?? 1,
            'created_by' => Auth::id(),
            'updated_by' => Auth::id()
        ]);

        alert()->success('Success', 'Attendance record created successfully.');
        return redirect()->route('humanresource.attendance.index');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $attendance = Attendance::with(['employee', 'createdBy'])
            ->forCompany(Auth::user()->company_id ?? 1)
            ->findOrFail($id);

        return view('humanresource::attendance.show', compact('attendance'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $attendance = Attendance::forCompany(Auth::user()->company_id ?? 1)
            ->findOrFail($id);

        $employees = Employee::where('company_id', Auth::user()->company_id ?? 1)
            ->where('is_active', true)
            ->orderBy('first_name')
            ->get();

        return view('humanresource::attendance.edit', compact('attendance', 'employees'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id): RedirectResponse
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'date' => 'required|date',
            'check_in' => 'required',
            'check_out' => 'nullable',
            'break_start' => 'nullable',
            'break_end' => 'nullable',
            'status' => 'required|in:present,absent,late,half_day,overtime',
            'notes' => 'nullable|string|max:500'
        ]);

        $attendance = Attendance::forCompany(Auth::user()->company_id ?? 1)
            ->findOrFail($id);

        $attendance->update([
            'employee_id' => $request->employee_id,
            'date' => $request->date,
            'check_in' => $request->check_in,
            'check_out' => $request->check_out,
            'break_start' => $request->break_start,
            'break_end' => $request->break_end,
            'status' => $request->status,
            'notes' => $request->notes,
            'updated_by' => Auth::id()
        ]);

        alert()->success('Success', 'Attendance record updated successfully.');
        return redirect()->route('humanresource.attendance.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $attendance = Attendance::forCompany(Auth::user()->company_id ?? 1)
            ->findOrFail($id);

        $attendance->delete();

        alert()->success('Success', 'Attendance record deleted successfully.');
        return redirect()->route('humanresource.attendance.index');
    }
}
