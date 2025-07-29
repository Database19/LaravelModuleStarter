@extends('layouts.app')

@section('title', 'Leave Management')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0">
                        <i class="fas fa-calendar-alt mr-2"></i>Leave Management
                    </h3>
                    <div>
                        <a href="{{ route('humanresource.leave.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Apply Leave
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
                            <label>Leave Type:</label>
                            <select class="form-control" name="leave_type">
                                <option value="">All Types</option>
                                <option value="Annual Leave">Annual Leave</option>
                                <option value="Sick Leave">Sick Leave</option>
                                <option value="Emergency Leave">Emergency Leave</option>
                                <option value="Maternity Leave">Maternity Leave</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label>Status:</label>
                            <select class="form-control" name="status">
                                <option value="">All Status</option>
                                <option value="Pending">Pending</option>
                                <option value="Approved">Approved</option>
                                <option value="Rejected">Rejected</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label>Date Range:</label>
                            <input type="date" class="form-control" name="start_date">
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
                                    <th>Leave Type</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Days</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($leaves as $leave)
                                <tr>
                                    <td>{{ $leave->id }}</td>
                                    <td>{{ $leave->employee_name }}</td>
                                    <td>
                                        <span class="badge bg-secondary">{{ $leave->leave_type }}</span>
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($leave->start_date)->format('d M Y') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($leave->end_date)->format('d M Y') }}</td>
                                    <td>
                                        <span class="badge bg-info">{{ $leave->days }} day(s)</span>
                                    </td>
                                    <td>
                                        @if($leave->status == 'Approved')
                                            <span class="badge bg-success">{{ $leave->status }}</span>
                                        @elseif($leave->status == 'Pending')
                                            <span class="badge bg-warning">{{ $leave->status }}</span>
                                        @elseif($leave->status == 'Rejected')
                                            <span class="badge bg-danger">{{ $leave->status }}</span>
                                        @else
                                            <span class="badge bg-secondary">{{ $leave->status }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('humanresource.leave.show', $leave->id) }}"
                                               class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @if($leave->status == 'Pending')
                                                <a href="{{ route('humanresource.leave.edit', $leave->id) }}"
                                                   class="btn btn-sm btn-warning">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form method="POST"
                                                      action="{{ route('humanresource.leave.approve', $leave->id) }}"
                                                      style="display: inline;">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="btn btn-sm btn-success" title="Approve">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                </form>
                                                <form method="POST"
                                                      action="{{ route('humanresource.leave.reject', $leave->id) }}"
                                                      style="display: inline;">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="btn btn-sm btn-danger" title="Reject">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </form>
                                            @endif
                                            <form method="POST"
                                                  action="{{ route('humanresource.leave.destroy', $leave->id) }}"
                                                  style="display: inline;"
                                                  onsubmit="return confirm('Are you sure you want to delete this leave request?')">
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
                                        <i class="fas fa-calendar-alt fa-3x mb-3"></i><br>
                                        No leave requests found.
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
