<?php

namespace Modules\Helpdesk\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\TicketComment;
use App\Models\User;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Ticket::with(['requester', 'assignedAgent', 'customer']);

        // Filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        if ($request->filled('assigned_to')) {
            $query->where('assigned_to', $request->assigned_to);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('ticket_number', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Show only assigned tickets for non-admin users
        $user = Auth::user();
        if (!$user->is_super_admin) {
            $query->where('assigned_to', Auth::id());
        }

        $tickets = $query->orderBy('created_at', 'desc')->paginate(15);

        // For filters dropdown
        $agents = User::where('is_super_admin', true)
                     ->orWhere('name', 'like', '%support%')
                     ->orWhere('name', 'like', '%helpdesk%')
                     ->orWhere('name', 'like', '%agent%')
                     ->get();

        return view('helpdesk::tickets.index', compact('tickets', 'agents'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $customers = Customer::where('is_active', true)->orderBy('name')->get();
        $agents = User::where('is_super_admin', true)
                     ->orWhere('name', 'like', '%support%')
                     ->orWhere('name', 'like', '%helpdesk%')
                     ->orWhere('name', 'like', '%agent%')
                     ->get();

        return view('helpdesk::tickets.create', compact('customers', 'agents'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'priority' => 'required|in:low,medium,high,urgent',
            'type' => 'required|in:technical,billing,general,feature_request,bug_report',
            'customer_id' => 'nullable|exists:customers,id',
            'contact_email' => 'nullable|email',
            'contact_phone' => 'nullable|string',
            'assigned_to' => 'nullable|exists:users,id',
            'due_date' => 'nullable|date|after:now',
        ]);

        DB::transaction(function () use ($validated) {
            $ticket = Ticket::create([
                'ticket_number' => Ticket::generateTicketNumber(),
                'title' => $validated['title'],
                'description' => $validated['description'],
                'priority' => $validated['priority'],
                'type' => $validated['type'],
                'status' => 'open',
                'requester_id' => Auth::id(),
                'customer_id' => $validated['customer_id'] ?? null,
                'contact_email' => $validated['contact_email'] ?? null,
                'contact_phone' => $validated['contact_phone'] ?? null,
                'assigned_to' => $validated['assigned_to'] ?? null,
                'due_date' => $validated['due_date'] ?? null,
            ]);

            // Auto-assign if assigned_to is provided
            if ($validated['assigned_to']) {
                $ticket->update(['status' => 'in_progress']);
            }

            // Create initial comment with ticket description
            $ticket->comments()->create([
                'user_id' => Auth::id(),
                'comment' => 'Ticket created: ' . $validated['description'],
                'is_internal' => false,
            ]);
        });

        alert()->success('Berhasil!', 'Ticket berhasil dibuat.');
        return redirect()->route('helpdesk.tickets.index');
    }

    /**
     * Show the specified resource.
     */
    public function show(Ticket $ticket)
    {
        $ticket->load([
            'requester',
            'assignedAgent',
            'customer',
            'comments.user'
        ]);

        $agents = User::where('is_super_admin', true)
                     ->orWhere('name', 'like', '%support%')
                     ->orWhere('name', 'like', '%helpdesk%')
                     ->orWhere('name', 'like', '%agent%')
                     ->get();

        return view('helpdesk::tickets.show', compact('ticket', 'agents'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Ticket $ticket)
    {
        $customers = Customer::where('is_active', true)->orderBy('name')->get();
        $agents = User::where('is_super_admin', true)
                     ->orWhere('name', 'like', '%support%')
                     ->orWhere('name', 'like', '%helpdesk%')
                     ->orWhere('name', 'like', '%agent%')
                     ->get();

        return view('helpdesk::tickets.edit', compact('ticket', 'customers', 'agents'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Ticket $ticket): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'priority' => 'required|in:low,medium,high,urgent',
            'type' => 'required|in:technical,billing,general,feature_request,bug_report',
            'status' => 'required|in:open,in_progress,resolved,closed',
            'customer_id' => 'nullable|exists:customers,id',
            'contact_email' => 'nullable|email',
            'contact_phone' => 'nullable|string',
            'assigned_to' => 'nullable|exists:users,id',
            'due_date' => 'nullable|date',
        ]);

        // Track status change
        $oldStatus = $ticket->status;

        $ticket->update($validated);

        // Add comment for status change
        if ($oldStatus !== $validated['status']) {
            $ticket->comments()->create([
                'user_id' => Auth::id(),
                'comment' => "Status changed from {$oldStatus} to {$validated['status']}",
                'is_internal' => true,
            ]);
        }

        alert()->success('Berhasil!', 'Ticket berhasil diupdate.');
        return redirect()->route('helpdesk.tickets.show', $ticket);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ticket $ticket)
    {
        $ticket->delete();

        alert()->success('Berhasil!', 'Ticket berhasil dihapus.');
        return redirect()->route('helpdesk.tickets.index');
    }

    /**
     * Add comment to ticket.
     */
    public function addComment(Request $request, Ticket $ticket)
    {
        $validated = $request->validate([
            'comment' => 'required|string',
            'is_internal' => 'boolean',
        ]);

        $ticket->comments()->create([
            'user_id' => Auth::id(),
            'comment' => $validated['comment'],
            'is_internal' => $validated['is_internal'] ?? false,
        ]);

        alert()->success('Berhasil!', 'Komentar berhasil ditambahkan.');
        return redirect()->route('helpdesk.tickets.show', $ticket);
    }

    /**
     * Assign ticket to agent.
     */
    public function assign(Request $request, Ticket $ticket)
    {
        $validated = $request->validate([
            'assigned_to' => 'required|exists:users,id',
        ]);

        $oldAgent = $ticket->assignedAgent ? $ticket->assignedAgent->name : 'Unassigned';
        $newAgent = User::find($validated['assigned_to'])->name;

        $ticket->update([
            'assigned_to' => $validated['assigned_to'],
            'status' => 'in_progress'
        ]);

        // Add comment for assignment
        $ticket->comments()->create([
            'user_id' => Auth::id(),
            'comment' => "Ticket assigned from {$oldAgent} to {$newAgent}",
            'is_internal' => true,
        ]);

        alert()->success('Berhasil!', 'Ticket berhasil diassign.');
        return redirect()->route('helpdesk.tickets.show', $ticket);
    }
}
