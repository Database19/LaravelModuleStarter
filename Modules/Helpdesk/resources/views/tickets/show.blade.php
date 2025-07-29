@extends('layouts.app')

@section('title', 'Ticket Details')

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Ticket Info -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ $ticket->title }}</h3>
                    <div class="card-tools">
                        <a href="{{ route('helpdesk.tickets.edit', $ticket) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <a href="{{ route('helpdesk.tickets.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Back
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Ticket Details -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <table class="table table-sm">
                                <tr>
                                    <th width="150">Ticket Number:</th>
                                    <td>{{ $ticket->ticket_number }}</td>
                                </tr>
                                <tr>
                                    <th>Status:</th>
                                    <td>
                                        <span class="badge badge-{{ $ticket->status == 'open' ? 'primary' : ($ticket->status == 'in_progress' ? 'warning' : ($ticket->status == 'resolved' ? 'success' : 'secondary')) }}">
                                            {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Priority:</th>
                                    <td>
                                        <span class="badge badge-{{ $ticket->priority == 'urgent' ? 'danger' : ($ticket->priority == 'high' ? 'warning' : ($ticket->priority == 'medium' ? 'info' : 'secondary')) }}">
                                            {{ ucfirst($ticket->priority) }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Type:</th>
                                    <td>{{ ucfirst(str_replace('_', ' ', $ticket->type)) }}</td>
                                </tr>
                                <tr>
                                    <th>Created Date:</th>
                                    <td>{{ $ticket->created_at->format('d/m/Y H:i') }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-sm">
                                <tr>
                                    <th width="150">Requester:</th>
                                    <td>{{ $ticket->requester->name ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Customer:</th>
                                    <td>{{ $ticket->customer->name ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Assigned To:</th>
                                    <td>{{ $ticket->assignedAgent->name ?? 'Unassigned' }}</td>
                                </tr>
                                <tr>
                                    <th>Contact Email:</th>
                                    <td>{{ $ticket->contact_email ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Contact Phone:</th>
                                    <td>{{ $ticket->contact_phone ?? 'N/A' }}</td>
                                </tr>
                                @if($ticket->due_date)
                                <tr>
                                    <th>Due Date:</th>
                                    <td>
                                        {{ $ticket->due_date->format('d/m/Y H:i') }}
                                        @if($ticket->due_date->isPast() && $ticket->status != 'closed')
                                            <span class="badge badge-danger ml-2">Overdue</span>
                                        @endif
                                    </td>
                                </tr>
                                @endif
                            </table>
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="mb-4">
                        <h5>Description:</h5>
                        <div class="border rounded p-3 bg-light">
                            {!! nl2br(e($ticket->description)) !!}
                        </div>
                    </div>

                    <!-- Comments/Timeline -->
                    <div>
                        <h5>Comments & Timeline:</h5>
                        <div class="timeline">
                            @forelse($ticket->comments as $comment)
                                <div class="time-label">
                                    <span class="bg-{{ $comment->is_internal ? 'warning' : 'info' }}">
                                        {{ $comment->created_at->format('d M Y H:i') }}
                                    </span>
                                </div>
                                <div>
                                    <i class="fas fa-comment bg-{{ $comment->is_internal ? 'warning' : 'info' }}"></i>
                                    <div class="timeline-item">
                                        <span class="time">
                                            <i class="far fa-clock"></i> {{ $comment->created_at->diffForHumans() }}
                                        </span>
                                        <h3 class="timeline-header">
                                            {{ $comment->user->name ?? 'System' }}
                                            @if($comment->is_internal)
                                                <span class="badge badge-warning ml-2">Internal</span>
                                            @endif
                                        </h3>
                                        <div class="timeline-body">
                                            {!! nl2br(e($comment->comment)) !!}
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="alert alert-info">
                                    No comments yet.
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions Panel -->
        <div class="col-md-4">
            <!-- Quick Actions -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Quick Actions</h3>
                </div>
                <div class="card-body">
                    <!-- Assign Ticket -->
                    <form action="{{ route('helpdesk.tickets.assign', $ticket) }}" method="POST" class="mb-3">
                        @csrf
                        <div class="form-group">
                            <label for="assigned_to">Assign to Agent:</label>
                            <select name="assigned_to" class="form-control" required>
                                <option value="">Select Agent</option>
                                @foreach($agents as $agent)
                                    <option value="{{ $agent->id }}" {{ $ticket->assigned_to == $agent->id ? 'selected' : '' }}>
                                        {{ $agent->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary btn-sm btn-block">
                            <i class="fas fa-user-plus"></i> Assign Ticket
                        </button>
                    </form>

                    <!-- Quick Status Update -->
                    <div class="btn-group-vertical btn-block mb-3">
                        @if($ticket->status == 'open')
                            <form action="{{ route('helpdesk.tickets.update', $ticket) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="status" value="in_progress">
                                <input type="hidden" name="title" value="{{ $ticket->title }}">
                                <input type="hidden" name="description" value="{{ $ticket->description }}">
                                <input type="hidden" name="priority" value="{{ $ticket->priority }}">
                                <input type="hidden" name="type" value="{{ $ticket->type }}">
                                <button type="submit" class="btn btn-warning btn-sm btn-block">
                                    <i class="fas fa-play"></i> Start Progress
                                </button>
                            </form>
                        @endif

                        @if($ticket->status == 'in_progress')
                            <form action="{{ route('helpdesk.tickets.update', $ticket) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="status" value="resolved">
                                <input type="hidden" name="title" value="{{ $ticket->title }}">
                                <input type="hidden" name="description" value="{{ $ticket->description }}">
                                <input type="hidden" name="priority" value="{{ $ticket->priority }}">
                                <input type="hidden" name="type" value="{{ $ticket->type }}">
                                <button type="submit" class="btn btn-success btn-sm btn-block">
                                    <i class="fas fa-check"></i> Mark Resolved
                                </button>
                            </form>
                        @endif

                        @if($ticket->status == 'resolved')
                            <form action="{{ route('helpdesk.tickets.update', $ticket) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="status" value="closed">
                                <input type="hidden" name="title" value="{{ $ticket->title }}">
                                <input type="hidden" name="description" value="{{ $ticket->description }}">
                                <input type="hidden" name="priority" value="{{ $ticket->priority }}">
                                <input type="hidden" name="type" value="{{ $ticket->type }}">
                                <button type="submit" class="btn btn-secondary btn-sm btn-block">
                                    <i class="fas fa-lock"></i> Close Ticket
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Add Comment -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Add Comment</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('helpdesk.tickets.comment', $ticket) }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <textarea name="comment" class="form-control" rows="4" placeholder="Add your comment..." required></textarea>
                        </div>
                        <div class="form-group">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="is_internal" name="is_internal" value="1">
                                <label class="form-check-label" for="is_internal">
                                    Internal comment (not visible to customer)
                                </label>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="fas fa-comment"></i> Add Comment
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .timeline {
        position: relative;
        margin: 0 0 30px 0;
        padding: 0;
        list-style: none;
    }
    .timeline:before {
        content: '';
        position: absolute;
        top: 0;
        bottom: 0;
        left: 15px;
        width: 4px;
        background: #ddd;
    }
    .timeline > li {
        position: relative;
        margin-right: 10px;
        margin-bottom: 15px;
    }
    .timeline > li:before,
    .timeline > li:after {
        content: " ";
        display: table;
    }
    .timeline > li:after {
        clear: both;
    }
    .timeline > li > .timeline-item {
        -webkit-box-shadow: 0 1px 1px rgba(0, 0, 0, 0.1);
        box-shadow: 0 1px 1px rgba(0, 0, 0, 0.1);
        border-radius: 3px;
        margin-top: 0;
        background: #fff;
        color: #444;
        margin-left: 45px;
        margin-right: 15px;
        padding: 0;
        position: relative;
    }
    .timeline > li > .fa,
    .timeline > li > .fas,
    .timeline > li > .far,
    .timeline > li > .fab,
    .timeline > li > .fal,
    .timeline > li > .fad,
    .timeline > li > .fas {
        width: 30px;
        height: 30px;
        font-size: 15px;
        line-height: 30px;
        position: absolute;
        color: #666;
        background: #d2d6de;
        border-radius: 50%;
        text-align: center;
        left: 0;
        top: 0;
    }
    .timeline > .time-label > span {
        font-weight: 600;
        color: #fff;
        border-radius: 4px;
        display: inline-block;
        padding: 5px;
    }
    .timeline-header {
        margin-top: 0;
        color: #555;
        border-bottom: 1px solid #f4f4f4;
        padding: 10px;
        font-weight: 600;
        margin: 0;
        font-size: 16px;
    }
    .timeline-body,
    .timeline-footer {
        padding: 10px;
    }
    .timeline > li > .timeline-item > .time {
        color: #999;
        float: right;
        padding: 10px;
        font-size: 12px;
    }
</style>
@endpush
