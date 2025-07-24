@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4 md:p-6">
    <div class="max-w-2xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">Edit Tugas</h1>
        <p class="text-gray-600 mb-6">Proyek: <a href="{{ route('projectmanagement.projects.show', $task->project_id) }}" class="text-blue-600 hover:underline">{{ $task->project->name }}</a></p>

        <form action="{{ route('projectmanagement.tasks.update', $task) }}" method="POST" class="bg-white p-6 rounded-lg shadow-md">
            @csrf
            @method('PUT')

            <div class="space-y-6">
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700">Judul Tugas*</label>
                    <input type="text" name="title" id="title" value="{{ old('title', $task->title) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="assignee_id" class="block text-sm font-medium text-gray-700">Ditugaskan Kepada</label>
                        <select name="assignee_id" id="assignee_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            <option value="">Pilih Karyawan</option>
                            @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ (old('assignee_id', $task->assignee_id)) == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="task_status_id" class="block text-sm font-medium text-gray-700">Status*</label>
                        <select name="task_status_id" id="task_status_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                            @foreach($taskStatuses as $status)
                            <option value="{{ $status->id }}" {{ (old('task_status_id', $task->task_status_id)) == $status->id ? 'selected' : '' }}>{{ $status->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="start_date" class="block text-sm font-medium text-gray-700">Tanggal Mulai</label>
                        <input type="date" name="start_date" id="start_date" value="{{ old('start_date', $task->start_date ? $task->start_date->format('Y-m-d') : '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    </div>
                    <div>
                        <label for="due_date" class="block text-sm font-medium text-gray-700">Tenggat Waktu</label>
                        <input type="date" name="due_date" id="due_date" value="{{ old('due_date', $task->due_date ? $task->due_date->format('Y-m-d') : '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    </div>
                </div>
                 <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="priority" class="block text-sm font-medium text-gray-700">Prioritas*</label>
                        <select name="priority" id="priority" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                            <option value="low" {{ (old('priority', $task->priority)) == 'low' ? 'selected' : '' }}>Rendah</option>
                            <option value="medium" {{ (old('priority', $task->priority)) == 'medium' ? 'selected' : '' }}>Sedang</option>
                            <option value="high" {{ (old('priority', $task->priority)) == 'high' ? 'selected' : '' }}>Tinggi</option>
                            <option value="urgent" {{ (old('priority', $task->priority)) == 'urgent' ? 'selected' : '' }}>Mendesak</option>
                        </select>
                    </div>
                    <div>
                        <label for="progress" class="block text-sm font-medium text-gray-700">Progres (%)*</label>
                        <input type="number" name="progress" id="progress" value="{{ old('progress', $task->progress) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required min="0" max="100">
                    </div>
                </div>
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700">Deskripsi</label>
                    <textarea name="description" id="description" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">{{ old('description', $task->description) }}</textarea>
                </div>
            </div>
            <div class="mt-6 flex justify-between items-center">
                <x-delete-button :action="route('project.management.tasks.destroy', $task)" />
                <div class="flex justify-end">
                    <a href="{{ route('projectmanagement.projects.show', $task->project_id) }}" class="px-6 py-2 text-sm font-medium text-gray-700 rounded-md mr-2">Batal</a>
                    <button type="submit" class="px-6 py-2 bg-blue-600 text-white font-semibold rounded-md hover:bg-blue-700">Update Tugas</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
