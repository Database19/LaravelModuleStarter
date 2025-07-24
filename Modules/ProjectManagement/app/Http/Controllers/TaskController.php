<?php

namespace Modules\ProjectManagement\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\TaskStatus;
use App\Models\User;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * Menyimpan task baru.
     * Biasanya dipanggil dari halaman detail proyek.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'title' => 'required|string|max:255',
            'assignee_id' => 'nullable|exists:users,id',
            'task_status_id' => 'required|exists:task_statuses,id',
            'due_date' => 'nullable|date',
            'priority' => 'required|in:low,medium,high,urgent',
        ]);

        $validated['code'] = 'TASK-' . time(); // TODO: Buat generator kode yang lebih baik
        $validated['created_by'] = auth()->id();
        $validated['updated_by'] = auth()->id();

        Task::create($validated);
        return back();
    }

    /**
     * Menampilkan form untuk mengedit task (misalnya via modal/halaman terpisah).
     */
    public function edit(Task $task)
    {
        $users = User::all();
        $taskStatuses = TaskStatus::all();
        return view('projectmanagement::tasks.edit', compact('task', 'users', 'taskStatuses'));
    }

    /**
     * Memperbarui data task.
     */
    public function update(Request $request, Task $task)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'assignee_id' => 'nullable|exists:users,id',
            'task_status_id' => 'required|exists:task_statuses,id',
            'start_date' => 'nullable|date',
            'due_date' => 'nullable|date',
            'priority' => 'required|in:low,medium,high,urgent',
            'progress' => 'required|integer|min:0|max:100',
            'estimated_hours' => 'nullable|numeric|min:0',
            'description' => 'nullable|string',
        ]);

        $validated['updated_by'] = auth()->id();

        if ($validated['progress'] == 100) {
            $validated['completed_date'] = now();
        } else {
            $validated['completed_date'] = null;
        }

        $task->update($validated);
        return back();
    }

    /**
     * Menghapus task.
     */
    public function destroy(Task $task)
    {
        $task->delete();
        return back();
    }
}
