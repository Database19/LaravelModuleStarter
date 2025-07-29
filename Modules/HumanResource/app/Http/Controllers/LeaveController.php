<?php

namespace Modules\HumanResource\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;

class LeaveController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $leaves = collect([
            (object)[
                'id' => 1,
                'employee_name' => 'John Doe',
                'leave_type' => 'Annual Leave',
                'start_date' => '2025-08-01',
                'end_date' => '2025-08-05',
                'days' => 5,
                'status' => 'Approved'
            ],
            (object)[
                'id' => 2,
                'employee_name' => 'Jane Smith',
                'leave_type' => 'Sick Leave',
                'start_date' => '2025-07-28',
                'end_date' => '2025-07-28',
                'days' => 1,
                'status' => 'Pending'
            ]
        ]);

        return view('humanresource::leave.index', compact('leaves'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('humanresource::leave.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'leave_type' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string|max:1000'
        ]);

        return redirect()->route('humanresource.leave.index')
            ->with('success', 'Leave request created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $leave = (object)[
            'id' => $id,
            'employee_name' => 'John Doe',
            'leave_type' => 'Annual Leave',
            'start_date' => '2025-08-01',
            'end_date' => '2025-08-05',
            'days' => 5,
            'status' => 'Approved',
            'reason' => 'Family vacation',
            'applied_date' => '2025-07-20',
            'approved_by' => 'HR Manager'
        ];

        return view('humanresource::leave.show', compact('leave'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $leave = (object)[
            'id' => $id,
            'employee_id' => 1,
            'leave_type' => 'Annual Leave',
            'start_date' => '2025-08-01',
            'end_date' => '2025-08-05',
            'reason' => 'Family vacation'
        ];

        return view('humanresource::leave.edit', compact('leave'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id): RedirectResponse
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'leave_type' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string|max:1000'
        ]);

        return redirect()->route('humanresource.leave.index')
            ->with('success', 'Leave request updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        return redirect()->route('humanresource.leave.index')
            ->with('success', 'Leave request deleted successfully.');
    }

    /**
     * Approve a leave request.
     */
    public function approve($id)
    {
        return redirect()->route('humanresource.leave.index')
            ->with('success', 'Leave request approved successfully.');
    }

    /**
     * Reject a leave request.
     */
    public function reject($id)
    {
        return redirect()->route('humanresource.leave.index')
            ->with('success', 'Leave request rejected successfully.');
    }
}
