@extends('layouts.app')

@section('title', 'Ticket Management')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Ticket Management</h3>
                    <a href="{{ route('helpdesk.tickets.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Create New Ticket
                    </a>
                </div>

                <div class="card-body">
                    <!-- Filter Form -->
                    <form method="GET" action="{{ route('helpdesk.tickets.index') }}" class="mb-4">
                        <div class="row">
                            <div class="col-md-3">
                                <input type="text" name="search" class="form-control" placeholder="Search tickets..."
                                       value="{{ request('search') }}">
                            </div>
                            <div class="col-md-2">
                                <select name="status" class="form-control">
                                    <option value="">All Status</option>
                                    <option value="open" {{ request('status') == 'open' ? 'selected' : '' }}>Open</option>
                                    <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                    <option value="resolved" {{ request('status') == 'resolved' ? 'selected' : '' }}>Resolved</option>
                                    <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Closed</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select name="priority" class="form-control">
                                    <option value="">All Priority</option>
                                    <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>Low</option>
                                    <option value="medium" {{ request('priority') == 'medium' ? 'selected' : '' }}>Medium</option>
                                    <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>High</option>
                                    <option value="urgent" {{ request('priority') == 'urgent' ? 'selected' : '' }}>Urgent</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select name="assigned_to" class="form-control">
                                    <option value="">All Agents</option>
                                    @foreach($agents as $agent)
                                        <option value="{{ $agent->id }}" {{ request('assigned_to') == $agent->id ? 'selected' : '' }}>
                                            {{ $agent->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-info">Filter</button>
                                <a href="{{ route('helpdesk.tickets.index') }}" class="btn btn-secondary">Reset</a>
                            </div>
                        </div>
                    </form>

                    <!-- Tickets Table -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Ticket Number</th>
                                    <th>Title</th>
                                    <th>Customer</th>
                                    <th>Priority</th>
                                    <th>Status</th>
                                    <th>Assigned To</th>
                                    <th>Created Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($tickets as $ticket)
                                    <tr>
                                        <td>{{ $loop->iteration + ($tickets->currentPage() - 1) * $tickets->perPage() }}</td>
                                        <td>
                                            <a href="{{ route('helpdesk.tickets.show', $ticket) }}" class="text-primary">
                                                {{ $ticket->ticket_number }}
                                            </a>
                                        </td>
                                        <td>{{ Str::limit($ticket->title, 50) }}</td>
                                        <td>
                                            @if($ticket->customer)
                                                {{ $ticket->customer->name }}
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge badge-{{ $ticket->priority == 'urgent' ? 'danger' : ($ticket->priority == 'high' ? 'warning' : ($ticket->priority == 'medium' ? 'info' : 'secondary')) }}">
                                                {{ ucfirst($ticket->priority) }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge badge-{{ $ticket->status == 'open' ? 'primary' : ($ticket->status == 'in_progress' ? 'warning' : ($ticket->status == 'resolved' ? 'success' : 'secondary')) }}">
                                                {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($ticket->assignedAgent)
                                                {{ $ticket->assignedAgent->name }}
                                            @else
                                                <span class="text-muted">Unassigned</span>
                                            @endif
                                        </td>
                                        <td>{{ $ticket->created_at->format('d/m/Y H:i') }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('helpdesk.tickets.show', $ticket) }}"
                                                   class="btn btn-info btn-sm" title="View">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('helpdesk.tickets.edit', $ticket) }}"
                                                   class="btn btn-warning btn-sm" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('helpdesk.tickets.destroy', $ticket) }}"
                                                      method="POST" style="display:inline;"
                                                      onsubmit="return confirm('Are you sure you want to delete this ticket?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm" title="Delete">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center">No tickets found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($tickets->hasPages())
                        <div class="d-flex justify-content-center">
                            {{ $tickets->appends(request()->query())->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .badge {
        font-size: 0.875rem;
    }
    .btn-group .btn {
        margin-right: 2px;
    }
</style>
@endpush
