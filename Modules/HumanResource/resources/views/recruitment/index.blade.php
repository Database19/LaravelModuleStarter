@extends('layouts.app')

@section('title', 'Recruitment Management')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0">
                        <i class="fas fa-users mr-2"></i>Recruitment Management
                    </h3>
                    <div>
                        <a href="{{ route('humanresource.recruitment.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Post Job
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
                            <label>Department:</label>
                            <select class="form-control" name="department">
                                <option value="">All Departments</option>
                                <option value="IT">IT</option>
                                <option value="HR">HR</option>
                                <option value="Finance">Finance</option>
                                <option value="Marketing">Marketing</option>
                                <option value="Sales">Sales</option>
                                <option value="Operations">Operations</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label>Status:</label>
                            <select class="form-control" name="status">
                                <option value="">All Status</option>
                                <option value="Draft">Draft</option>
                                <option value="Open">Open</option>
                                <option value="Closed">Closed</option>
                                <option value="On Hold">On Hold</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label>Job Title:</label>
                            <input type="text" class="form-control" name="search" placeholder="Search job title...">
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
                                    <th>Job Title</th>
                                    <th>Department</th>
                                    <th>Posted Date</th>
                                    <th>Applications</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($recruitments as $recruitment)
                                <tr>
                                    <td>{{ $recruitment->id }}</td>
                                    <td>
                                        <div class="fw-bold">{{ $recruitment->job_title }}</div>
                                        <small class="text-muted">{{ Str::limit($recruitment->description ?? 'No description available', 50) }}</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary">{{ $recruitment->department }}</span>
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($recruitment->posted_date)->format('d M Y') }}</td>
                                    <td>
                                        <a href="{{ route('humanresource.recruitment.applications', $recruitment->id) }}" class="text-decoration-none">
                                            <span class="badge bg-info">{{ $recruitment->applications }} applications</span>
                                        </a>
                                    </td>
                                    <td>
                                        @if($recruitment->status == 'Open')
                                            <span class="badge bg-success">{{ $recruitment->status }}</span>
                                        @elseif($recruitment->status == 'Closed')
                                            <span class="badge bg-danger">{{ $recruitment->status }}</span>
                                        @elseif($recruitment->status == 'Draft')
                                            <span class="badge bg-secondary">{{ $recruitment->status }}</span>
                                        @else
                                            <span class="badge bg-warning">{{ $recruitment->status }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('humanresource.recruitment.show', $recruitment->id) }}"
                                               class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('humanresource.recruitment.edit', $recruitment->id) }}"
                                               class="btn btn-sm btn-warning">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="{{ route('humanresource.recruitment.applications', $recruitment->id) }}"
                                               class="btn btn-sm btn-primary" title="View Applications">
                                                <i class="fas fa-file-alt"></i>
                                            </a>
                                            @if($recruitment->status == 'Open')
                                                <form method="POST"
                                                      action="{{ route('humanresource.recruitment.close', $recruitment->id) }}"
                                                      style="display: inline;">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="btn btn-sm btn-secondary" title="Close Job">
                                                        <i class="fas fa-ban"></i>
                                                    </button>
                                                </form>
                                            @endif
                                            <form method="POST"
                                                  action="{{ route('humanresource.recruitment.destroy', $recruitment->id) }}"
                                                  style="display: inline;"
                                                  onsubmit="return confirm('Are you sure you want to delete this job posting?')">
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
                                        <i class="fas fa-users fa-3x mb-3"></i><br>
                                        No job postings found.
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
