@extends('layouts.app')

@section('title', 'Edit Ticket')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Edit Ticket - {{ $ticket->ticket_number }}</h3>
                    <div class="card-tools">
                        <a href="{{ route('helpdesk.tickets.show', $ticket) }}" class="btn btn-info btn-sm">
                            <i class="fas fa-eye"></i> View
                        </a>
                        <a href="{{ route('helpdesk.tickets.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Back to List
                        </a>
                    </div>
                </div>

                <form action="{{ route('helpdesk.tickets.update', $ticket) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="row">
                            <!-- Left Column -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="title">Title <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('title') is-invalid @enderror"
                                           id="title" name="title" value="{{ old('title', $ticket->title) }}" required>
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="description">Description <span class="text-danger">*</span></label>
                                    <textarea class="form-control @error('description') is-invalid @enderror"
                                              id="description" name="description" rows="5" required>{{ old('description', $ticket->description) }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="priority">Priority <span class="text-danger">*</span></label>
                                            <select class="form-control @error('priority') is-invalid @enderror"
                                                    id="priority" name="priority" required>
                                                <option value="">Select Priority</option>
                                                <option value="low" {{ old('priority', $ticket->priority) == 'low' ? 'selected' : '' }}>Low</option>
                                                <option value="medium" {{ old('priority', $ticket->priority) == 'medium' ? 'selected' : '' }}>Medium</option>
                                                <option value="high" {{ old('priority', $ticket->priority) == 'high' ? 'selected' : '' }}>High</option>
                                                <option value="urgent" {{ old('priority', $ticket->priority) == 'urgent' ? 'selected' : '' }}>Urgent</option>
                                            </select>
                                            @error('priority')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="type">Type <span class="text-danger">*</span></label>
                                            <select class="form-control @error('type') is-invalid @enderror"
                                                    id="type" name="type" required>
                                                <option value="">Select Type</option>
                                                <option value="technical" {{ old('type', $ticket->type) == 'technical' ? 'selected' : '' }}>Technical</option>
                                                <option value="billing" {{ old('type', $ticket->type) == 'billing' ? 'selected' : '' }}>Billing</option>
                                                <option value="general" {{ old('type', $ticket->type) == 'general' ? 'selected' : '' }}>General</option>
                                                <option value="feature_request" {{ old('type', $ticket->type) == 'feature_request' ? 'selected' : '' }}>Feature Request</option>
                                                <option value="bug_report" {{ old('type', $ticket->type) == 'bug_report' ? 'selected' : '' }}>Bug Report</option>
                                            </select>
                                            @error('type')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="status">Status <span class="text-danger">*</span></label>
                                    <select class="form-control @error('status') is-invalid @enderror"
                                            id="status" name="status" required>
                                        <option value="">Select Status</option>
                                        <option value="open" {{ old('status', $ticket->status) == 'open' ? 'selected' : '' }}>Open</option>
                                        <option value="in_progress" {{ old('status', $ticket->status) == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                        <option value="resolved" {{ old('status', $ticket->status) == 'resolved' ? 'selected' : '' }}>Resolved</option>
                                        <option value="closed" {{ old('status', $ticket->status) == 'closed' ? 'selected' : '' }}>Closed</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Right Column -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="customer_id">Customer</label>
                                    <select class="form-control @error('customer_id') is-invalid @enderror"
                                            id="customer_id" name="customer_id">
                                        <option value="">Select Customer (Optional)</option>
                                        @foreach($customers as $customer)
                                            <option value="{{ $customer->id }}" {{ old('customer_id', $ticket->customer_id) == $customer->id ? 'selected' : '' }}>
                                                {{ $customer->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('customer_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="contact_email">Contact Email</label>
                                    <input type="email" class="form-control @error('contact_email') is-invalid @enderror"
                                           id="contact_email" name="contact_email" value="{{ old('contact_email', $ticket->contact_email) }}">
                                    @error('contact_email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="contact_phone">Contact Phone</label>
                                    <input type="text" class="form-control @error('contact_phone') is-invalid @enderror"
                                           id="contact_phone" name="contact_phone" value="{{ old('contact_phone', $ticket->contact_phone) }}">
                                    @error('contact_phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="assigned_to">Assign to Agent</label>
                                    <select class="form-control @error('assigned_to') is-invalid @enderror"
                                            id="assigned_to" name="assigned_to">
                                        <option value="">Leave Unassigned</option>
                                        @foreach($agents as $agent)
                                            <option value="{{ $agent->id }}" {{ old('assigned_to', $ticket->assigned_to) == $agent->id ? 'selected' : '' }}>
                                                {{ $agent->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('assigned_to')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="due_date">Due Date</label>
                                    <input type="datetime-local" class="form-control @error('due_date') is-invalid @enderror"
                                           id="due_date" name="due_date" value="{{ old('due_date', $ticket->due_date ? $ticket->due_date->format('Y-m-d\TH:i') : '') }}">
                                    @error('due_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Show current ticket info -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="card bg-light">
                                    <div class="card-header">
                                        <h4 class="card-title">Current Ticket Information</h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <p><strong>Ticket Number:</strong> {{ $ticket->ticket_number }}</p>
                                                <p><strong>Created:</strong> {{ $ticket->created_at->format('d/m/Y H:i') }}</p>
                                                <p><strong>Requester:</strong> {{ $ticket->requester->name ?? 'N/A' }}</p>
                                            </div>
                                            <div class="col-md-6">
                                                <p><strong>Current Status:</strong>
                                                    <span class="badge badge-{{ $ticket->status == 'open' ? 'primary' : ($ticket->status == 'in_progress' ? 'warning' : ($ticket->status == 'resolved' ? 'success' : 'secondary')) }}">
                                                        {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}
                                                    </span>
                                                </p>
                                                <p><strong>Current Priority:</strong>
                                                    <span class="badge badge-{{ $ticket->priority == 'urgent' ? 'danger' : ($ticket->priority == 'high' ? 'warning' : ($ticket->priority == 'medium' ? 'info' : 'secondary')) }}">
                                                        {{ ucfirst($ticket->priority) }}
                                                    </span>
                                                </p>
                                                <p><strong>Last Updated:</strong> {{ $ticket->updated_at->format('d/m/Y H:i') }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Update Ticket
                        </button>
                        <a href="{{ route('helpdesk.tickets.show', $ticket) }}" class="btn btn-info">
                            <i class="fas fa-eye"></i> View Ticket
                        </a>
                        <a href="{{ route('helpdesk.tickets.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Auto-populate contact fields when customer is selected
    $('#customer_id').change(function() {
        let customerId = $(this).val();
        if (customerId) {
            // You can make an AJAX call here to get customer details
            // and populate contact_email and contact_phone
        }
    });

    // Warn if changing from resolved/closed to open/in_progress
    $('#status').change(function() {
        let newStatus = $(this).val();
        let currentStatus = '{{ $ticket->status }}';

        if ((currentStatus == 'resolved' || currentStatus == 'closed') &&
            (newStatus == 'open' || newStatus == 'in_progress')) {
            if (!confirm('Are you sure you want to reopen this ticket?')) {
                $(this).val(currentStatus);
            }
        }
    });
});
</script>
@endpush
