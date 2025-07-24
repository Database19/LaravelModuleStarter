<?php

namespace Modules\ProjectManagement\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Project;
use App\Models\ProjectStatus;
use App\Models\TaskStatus;
use App\Models\User;
use Illuminate\Http\Request;

class ProjectManagementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $projects = Project::with(['customer', 'manager', 'status'])->latest()->paginate(15);
        return view('projectmanagement::index', compact('projects'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $customers = Customer::all();
        $users = User::all();
        $statuses = ProjectStatus::all();
        return view('projectmanagement::create', compact('customers', 'users', 'statuses'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'customer_id' => 'nullable|exists:customers,id',
            'manager_id' => 'required|exists:users,id',
            'project_status_id' => 'required|exists:project_statuses,id',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'budget' => 'nullable|numeric|min:0',
            'priority' => 'required|in:low,medium,high,urgent',
        ]);

        // Tambahkan data otomatis
        $validated['code'] = 'PROJ-' . time(); // TODO: Buat generator kode yang lebih baik
        $validated['created_by'] = auth()->id();
        $validated['updated_by'] = auth()->id();

        Project::create($validated);
        return redirect()->route('project.management.index');
    }

    /**
     * Show the specified resource.
     */
    public function show(Project $project)
    {
        $project->load(['customer', 'manager', 'status', 'tasks.assignee', 'tasks.status']);

        // Data untuk form "Tambah Tugas" di halaman detail
        $users = User::all();
        $taskStatuses = TaskStatus::all();

        return view('projectmanagement::show', compact('project', 'users', 'taskStatuses'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Project $project)
    {
        $customers = Customer::all();
        $users = User::all();
        $statuses = ProjectStatus::all();
        return view('projectmanagement::edit', compact('project', 'customers', 'users', 'statuses'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Project $project)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'customer_id' => 'nullable|exists:customers,id',
            'manager_id' => 'required|exists:users,id',
            'project_status_id' => 'required|exists:project_statuses,id',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'budget' => 'nullable|numeric|min:0',
            'priority' => 'required|in:low,medium,high,urgent',
        ]);

        $validated['updated_by'] = auth()->id();

        $project->update($validated);
        return redirect()->route('project.management.show', $project)->with('success', 'Data proyek berhasil diperbarui.');
    }

    /**
     * Menghapus proyek dari database.
     */
    public function destroy(Project $project)
    {
        // onDelete('cascade') pada migrasi akan menghapus semua task terkait secara otomatis.
        $project->delete();
        return redirect()->route('project.management.index')->with('success', 'Proyek berhasil dihapus.');
    }
}
