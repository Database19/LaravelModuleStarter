@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4 md:p-6">
    {{-- Header Proyek --}}
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-800">{{ $project->name }}</h1>
        <p class="text-lg text-gray-600">Pelanggan: {{ $project->customer->name ?? 'Proyek Internal' }}</p>
    </div>

    {{-- Form Tambah Tugas --}}
    <div class="mb-8">
        <form action="{{ route('projectmanagement.tasks.store') }}" method="POST" class="bg-white p-4 rounded-lg shadow-md">
            @csrf
            <input type="hidden" name="project_id" value="{{ $project->id }}">
            <h3 class="font-bold text-lg mb-2">Tambah Tugas Baru</h3>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                <div class="md:col-span-2">
                    <label for="title" class="text-sm font-medium text-gray-700">Judul Tugas*</label>
                    <input type="text" name="title" id="title" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                </div>
                <div>
                    <label for="assignee_id" class="text-sm font-medium text-gray-700">Ditugaskan Kepada</label>
                    <select name="assignee_id" id="assignee_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        <option value="">Pilih Karyawan</option>
                        @foreach($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <button type="submit" class="w-full px-4 py-2 bg-blue-600 text-white font-semibold rounded-md hover:bg-blue-700">Tambah</button>
                </div>
                {{-- Hidden fields for default values --}}
                <input type="hidden" name="task_status_id" value="{{ $taskStatuses->where('name', 'Todo')->first()->id ?? 1 }}">
                <input type="hidden" name="priority" value="medium">
            </div>
        </form>
    </div>

    {{-- Papan Kanban Tugas --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($taskStatuses as $status)
            <div class="bg-gray-100 rounded-lg">
                <h2 class="font-bold text-lg p-4 border-b">{{ $status->name }} ({{ $project->tasks->where('task_status_id', $status->id)->count() }})</h2>
                <div class="p-4 space-y-4">
                    @foreach($project->tasks->where('task_status_id', $status->id) as $task)
                        <div class="bg-white p-4 rounded-md shadow-sm border">
                            <div class="flex justify-between items-start">
                                <p class="font-semibold text-gray-800">{{ $task->title }}</p>
                                <a href="{{ route('projectmanagement.tasks.edit', $task) }}" class="text-gray-400 hover:text-blue-600">
                                    <i class="fas fa-pen"></i>
                                </a>
                            </div>
                            <div class="mt-2 flex justify-between items-center text-sm text-gray-500">
                                <span>
                                    @if($task->assignee)
                                        <img src="https://ui-avatars.com/api/?name={{ urlencode($task->assignee->name) }}&size=24&background=random" class="w-6 h-6 rounded-full inline-block mr-1" alt="{{ $task->assignee->name }}">
                                        {{ $task->assignee->name }}
                                    @else
                                        Belum Ditugaskan
                                    @endif
                                </span>
                                @if($task->due_date)
                                <span><i class="far fa-calendar-alt mr-1"></i> {{ $task->due_date->format('d M') }}</span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
