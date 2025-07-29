@extends('layouts.app')

@section('title', 'Attendance Management')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0">
                        <i class="fas fa-clock mr-2"></i>Attendance Management
                    </h3>
                    <div>
                        <a href="{{ route('humanresource.attendance.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Add Attendance
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle"></i> {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert">
                                <span>&times;</span>
                            </button>
                        </div>
                    @endif

                    <!-- Filter Section -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <label>Date Range:</label>
                            <input type="date" class="form-control" name="start_date" value="{{ date('Y-m-d') }}">
                        </div>
                        <div class="col-md-3">
                            <label>&nbsp;</label>
                            <input type="date" class="form-control" name="end_date" value="{{ date('Y-m-d') }}">
                        </div>
                        <div class="col-md-3">
                            <label>Status:</label>
                            <select class="form-control" name="status">
                                <option value="">All Status</option>
                                <option value="Present">Present</option>
                                <option value="Absent">Absent</option>
                                <option value="Late">Late</option>
                                <option value="Half Day">Half Day</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label>&nbsp;</label>
                            <button type="button" class="btn btn-info btn-block">
                                <i class="fas fa-search"></i> Filter
                            </button>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>Employee</th>
                                    <th>Date</th>
                                    <th>Check In</th>
                                    <th>Check Out</th>
                                    <th>Working Hours</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($attendances as $attendance)
                                <tr>
                                    <td>{{ $attendance->id }}</td>
                                    <td>{{ $attendance->employee_name }}</td>
                                    <td>{{ $attendance->date }}</td>
                                    <td>
                                        <span class="badge bg-info">{{ $attendance->check_in }}</span>
                                    </td>
                                    <td>
                                        @if($attendance->check_out)
                                            <span class="badge bg-success">{{ $attendance->check_out }}</span>
                                        @else
                                            <span class="badge bg-warning">Not checked out</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($attendance->check_out)
                                            {{ \Carbon\Carbon::parse($attendance->check_in)->diffForHumans(\Carbon\Carbon::parse($attendance->check_out), true) }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        @if($attendance->status == 'Present')
                                            <span class="badge bg-success">{{ $attendance->status }}</span>
                                        @elseif($attendance->status == 'Late')
                                            <span class="badge bg-warning">{{ $attendance->status }}</span>
                                        @elseif($attendance->status == 'Absent')
                                            <span class="badge bg-danger">{{ $attendance->status }}</span>
                                        @else
                                            <span class="badge bg-info">{{ $attendance->status }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('humanresource.attendance.show', $attendance->id) }}"
                                               class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('humanresource.attendance.edit', $attendance->id) }}"
                                               class="btn btn-sm btn-warning">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form method="POST"
                                                  action="{{ route('humanresource.attendance.destroy', $attendance->id) }}"
                                                  style="display: inline;"
                                                  onsubmit="return confirm('Are you sure you want to delete this attendance record?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted py-4">
                                        <i class="fas fa-clock fa-3x mb-3"></i><br>
                                        No attendance records found.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Auto-hide alerts after 5 seconds
    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 5000);
</script>
@endpush
