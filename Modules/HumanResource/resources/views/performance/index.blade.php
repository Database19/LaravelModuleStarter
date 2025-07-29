@extends('layouts.app')

@section('title', 'Performance Management')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0">
                        <i class="fas fa-chart-line mr-2"></i>Performance Management
                    </h3>
                    <div>
                        <a href="{{ route('humanresource.performance.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> New Review
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
                            <label>Review Period:</label>
                            <select class="form-control" name="review_period">
                                <option value="">All Periods</option>
                                <option value="Q1 2025">Q1 2025</option>
                                <option value="Q2 2025">Q2 2025</option>
                                <option value="Q3 2025">Q3 2025</option>
                                <option value="Q4 2025">Q4 2025</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label>Status:</label>
                            <select class="form-control" name="status">
                                <option value="">All Status</option>
                                <option value="Draft">Draft</option>
                                <option value="In Progress">In Progress</option>
                                <option value="Completed">Completed</option>
                                <option value="Approved">Approved</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label>Score Range:</label>
                            <select class="form-control" name="score_range">
                                <option value="">All Scores</option>
                                <option value="5">Excellent (5.0)</option>
                                <option value="4">Good (4.0-4.9)</option>
                                <option value="3">Average (3.0-3.9)</option>
                                <option value="2">Below Average (2.0-2.9)</option>
                                <option value="1">Poor (1.0-1.9)</option>
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
                                    <th>Review Period</th>
                                    <th>Overall Score</th>
                                    <th>Status</th>
                                    <th>Reviewer</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($performances as $performance)
                                <tr>
                                    <td>{{ $performance->id }}</td>
                                    <td>{{ $performance->employee_name }}</td>
                                    <td>
                                        <span class="badge bg-secondary">{{ $performance->review_period }}</span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <span class="badge
                                                @if($performance->overall_score >= 4.5) bg-success
                                                @elseif($performance->overall_score >= 4.0) bg-info
                                                @elseif($performance->overall_score >= 3.0) bg-warning
                                                @else bg-danger
                                                @endif
                                                me-2">
                                                {{ number_format($performance->overall_score, 1) }}
                                            </span>
                                            <div class="progress" style="width: 60px; height: 8px;">
                                                <div class="progress-bar
                                                    @if($performance->overall_score >= 4.5) bg-success
                                                    @elseif($performance->overall_score >= 4.0) bg-info
                                                    @elseif($performance->overall_score >= 3.0) bg-warning
                                                    @else bg-danger
                                                    @endif"
                                                    style="width: {{ ($performance->overall_score / 5) * 100 }}%">
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if($performance->status == 'Completed')
                                            <span class="badge bg-success">{{ $performance->status }}</span>
                                        @elseif($performance->status == 'In Progress')
                                            <span class="badge bg-warning">{{ $performance->status }}</span>
                                        @elseif($performance->status == 'Draft')
                                            <span class="badge bg-secondary">{{ $performance->status }}</span>
                                        @else
                                            <span class="badge bg-info">{{ $performance->status }}</span>
                                        @endif
                                    </td>
                                    <td>{{ $performance->reviewer }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('humanresource.performance.show', $performance->id) }}"
                                               class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('humanresource.performance.edit', $performance->id) }}"
                                               class="btn btn-sm btn-warning">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form method="POST"
                                                  action="{{ route('humanresource.performance.destroy', $performance->id) }}"
                                                  style="display: inline;"
                                                  onsubmit="return confirm('Are you sure you want to delete this performance review?')">
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
                                    <td colspan="7" class="text-center text-muted py-4">
                                        <i class="fas fa-chart-line fa-3x mb-3"></i><br>
                                        No performance reviews found.
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
