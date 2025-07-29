<?php

namespace Modules\HumanResource\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;

class RecruitmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $recruitments = collect([
            (object)[
                'id' => 1,
                'job_title' => 'Software Developer',
                'department' => 'IT',
                'status' => 'Open',
                'applications' => 15,
                'posted_date' => '2025-07-01'
            ],
            (object)[
                'id' => 2,
                'job_title' => 'Marketing Manager',
                'department' => 'Marketing',
                'status' => 'Closed',
                'applications' => 8,
                'posted_date' => '2025-06-15'
            ]
        ]);

        return view('humanresource::recruitment.index', compact('recruitments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('humanresource::recruitment.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'job_title' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'description' => 'required|string',
            'requirements' => 'required|string',
            'salary_range' => 'nullable|string',
            'location' => 'required|string',
            'employment_type' => 'required|in:Full Time,Part Time,Contract,Internship'
        ]);

        return redirect()->route('humanresource.recruitment.index')
            ->with('success', 'Job posting created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $recruitment = (object)[
            'id' => $id,
            'job_title' => 'Software Developer',
            'department' => 'IT',
            'status' => 'Open',
            'applications' => 15,
            'posted_date' => '2025-07-01',
            'description' => 'We are looking for a skilled software developer...',
            'requirements' => 'Bachelor degree in Computer Science, 3+ years experience...',
            'salary_range' => 'Rp 8,000,000 - Rp 12,000,000',
            'location' => 'Jakarta',
            'employment_type' => 'Full Time'
        ];

        return view('humanresource::recruitment.show', compact('recruitment'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $recruitment = (object)[
            'id' => $id,
            'job_title' => 'Software Developer',
            'department' => 'IT',
            'description' => 'We are looking for a skilled software developer...',
            'requirements' => 'Bachelor degree in Computer Science, 3+ years experience...',
            'salary_range' => 'Rp 8,000,000 - Rp 12,000,000',
            'location' => 'Jakarta',
            'employment_type' => 'Full Time'
        ];

        return view('humanresource::recruitment.edit', compact('recruitment'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id): RedirectResponse
    {
        $request->validate([
            'job_title' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'description' => 'required|string',
            'requirements' => 'required|string',
            'salary_range' => 'nullable|string',
            'location' => 'required|string',
            'employment_type' => 'required|in:Full Time,Part Time,Contract,Internship'
        ]);

        return redirect()->route('humanresource.recruitment.index')
            ->with('success', 'Job posting updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        return redirect()->route('humanresource.recruitment.index')
            ->with('success', 'Job posting deleted successfully.');
    }

    /**
     * Close a job posting.
     */
    public function close($id)
    {
        return redirect()->route('humanresource.recruitment.index')
            ->with('success', 'Job posting closed successfully.');
    }

    /**
     * View applications for a job posting.
     */
    public function applications($id)
    {
        $applications = collect([
            (object)[
                'id' => 1,
                'candidate_name' => 'John Applicant',
                'email' => 'john@example.com',
                'phone' => '+62123456789',
                'status' => 'Under Review',
                'applied_date' => '2025-07-10'
            ],
            (object)[
                'id' => 2,
                'candidate_name' => 'Jane Candidate',
                'email' => 'jane@example.com',
                'phone' => '+62987654321',
                'status' => 'Interview Scheduled',
                'applied_date' => '2025-07-08'
            ]
        ]);

        return view('humanresource::recruitment.applications', compact('applications', 'id'));
    }
}
