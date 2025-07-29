@extends('layouts.app')

@section('title', 'Job Applications')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0">
                        <i class="fas fa-file-alt mr-2"></i>Job Applications
                    </h3>
                    <div>
                        <a href="{{ route('humanresource.recruitment.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to Jobs
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

                    <!-- Job Info Section -->
                    <div class="alert alert-info">
                        <h5><i class="fas fa-info-circle"></i> Job Details</h5>
                        <p class="mb-0">Applications for Job ID: <strong>{{ $id }}</strong></p>
                    </div>

                    <!-- Filter Section -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <label>Application Status:</label>
                            <select class="form-control" name="status">
                                <option value="">All Status</option>
                                <option value="New">New</option>
                                <option value="Under Review">Under Review</option>
                                <option value="Interview Scheduled">Interview Scheduled</option>
                                <option value="Interviewed">Interviewed</option>
                                <option value="Hired">Hired</option>
                                <option value="Rejected">Rejected</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label>Date Applied:</label>
                            <input type="date" class="form-control" name="applied_date">
                        </div>
                        <div class="col-md-3">
                            <label>Candidate Name:</label>
                            <input type="text" class="form-control" name="search" placeholder="Search candidate...">
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
                                    <th>Candidate</th>
                                    <th>Contact</th>
                                    <th>Applied Date</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($applications as $application)
                                <tr>
                                    <td>{{ $application->id }}</td>
                                    <td>
                                        <div class="fw-bold">{{ $application->candidate_name }}</div>
                                        <small class="text-muted">Candidate</small>
                                    </td>
                                    <td>
                                        <div>
                                            <i class="fas fa-envelope"></i> {{ $application->email }}
                                        </div>
                                        <div>
                                            <i class="fas fa-phone"></i> {{ $application->phone }}
                                        </div>
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($application->applied_date)->format('d M Y') }}</td>
                                    <td>
                                        @if($application->status == 'New')
                                            <span class="badge bg-primary">{{ $application->status }}</span>
                                        @elseif($application->status == 'Under Review')
                                            <span class="badge bg-warning">{{ $application->status }}</span>
                                        @elseif($application->status == 'Interview Scheduled')
                                            <span class="badge bg-info">{{ $application->status }}</span>
                                        @elseif($application->status == 'Interviewed')
                                            <span class="badge bg-secondary">{{ $application->status }}</span>
                                        @elseif($application->status == 'Hired')
                                            <span class="badge bg-success">{{ $application->status }}</span>
                                        @elseif($application->status == 'Rejected')
                                            <span class="badge bg-danger">{{ $application->status }}</span>
                                        @else
                                            <span class="badge bg-light text-dark">{{ $application->status }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button type="button" class="btn btn-sm btn-info" title="View Application">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-success" title="Download Resume">
                                                <i class="fas fa-download"></i>
                                            </button>
                                            <div class="btn-group" role="group">
                                                <button type="button" class="btn btn-sm btn-warning dropdown-toggle" data-bs-toggle="dropdown">
                                                    <i class="fas fa-cog"></i>
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li><a class="dropdown-item" href="#"><i class="fas fa-eye"></i> Review</a></li>
                                                    <li><a class="dropdown-item" href="#"><i class="fas fa-calendar"></i> Schedule Interview</a></li>
                                                    <li><a class="dropdown-item" href="#"><i class="fas fa-check"></i> Hire</a></li>
                                                    <li><hr class="dropdown-divider"></li>
                                                    <li><a class="dropdown-item text-danger" href="#"><i class="fas fa-times"></i> Reject</a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">
                                        <i class="fas fa-file-alt fa-3x mb-3"></i><br>
                                        No applications found for this job posting.
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
