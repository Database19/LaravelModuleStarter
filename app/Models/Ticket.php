<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_number',
        'title',
        'description',
        'priority',
        'status',
        'type',
        'requester_id',
        'assigned_to',
        'customer_id',
        'contact_email',
        'contact_phone',
        'due_date',
        'resolved_at',
        'closed_at',
        'satisfaction_rating',
        'satisfaction_comment',
        'custom_fields'
    ];

    protected $casts = [
        'due_date' => 'datetime',
        'resolved_at' => 'datetime',
        'closed_at' => 'datetime',
        'custom_fields' => 'array'
    ];

        /**
     * Generate unique ticket number
     */
    public static function generateTicketNumber()
    {
        $prefix = 'TKT';
        $date = now()->format('Ymd');

        // Get the latest ticket number for today
        $lastTicket = self::where('ticket_number', 'like', $prefix . $date . '%')
                         ->orderBy('ticket_number', 'desc')
                         ->first();

        if ($lastTicket) {
            $lastNumber = intval(substr($lastTicket->ticket_number, -4));
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '0001';
        }

        return $prefix . $date . $newNumber;
    }
    public function requester()
    {
        return $this->belongsTo(User::class, 'requester_id');
    }

    public function assignedAgent()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function comments()
    {
        return $this->hasMany(TicketComment::class)->orderBy('created_at');
    }

    public function publicComments()
    {
        return $this->hasMany(TicketComment::class)->where('is_internal', false)->orderBy('created_at');
    }

    public function internalComments()
    {
        return $this->hasMany(TicketComment::class)->where('is_internal', true)->orderBy('created_at');
    }

    // Scopes
    public function scopeOpen($query)
    {
        return $query->where('status', 'open');
    }

    public function scopeAssignedTo($query, $userId)
    {
        return $query->where('assigned_to', $userId);
    }

    public function scopeByPriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }

    public function scopeOverdue($query)
    {
        return $query->where('due_date', '<', now())
                    ->whereNotIn('status', ['resolved', 'closed']);
    }

    // Helper methods
    // public static function generateTicketNumber()
    // {
    //     $prefix = 'TKT-' . date('Ymd') . '-';
    //     $lastTicket = self::whereDate('created_at', today())
    //         ->where('ticket_number', 'like', $prefix . '%')
    //         ->orderBy('id', 'desc')
    //         ->first();

    //     if ($lastTicket) {
    //         $lastNumber = intval(substr($lastTicket->ticket_number, -4));
    //         $newNumber = $lastNumber + 1;
    //     } else {
    //         $newNumber = 1;
    //     }

    //     return $prefix . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    // }

    public function markAsResolved()
    {
        $this->update([
            'status' => 'resolved',
            'resolved_at' => now()
        ]);
    }

    public function markAsClosed()
    {
        $this->update([
            'status' => 'closed',
            'closed_at' => now()
        ]);
    }

    public function assignTo($userId)
    {
        $this->update([
            'assigned_to' => $userId,
            'status' => 'in_progress'
        ]);
    }

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'open' => 'danger',
            'in_progress' => 'warning',
            'waiting_customer' => 'info',
            'resolved' => 'success',
            'closed' => 'secondary',
            default => 'primary'
        };
    }

    public function getPriorityColorAttribute()
    {
        return match($this->priority) {
            'low' => 'success',
            'medium' => 'info',
            'high' => 'warning',
            'urgent' => 'danger',
            default => 'secondary'
        };
    }

    public function getIsOverdueAttribute()
    {
        return $this->due_date &&
               $this->due_date->isPast() &&
               !in_array($this->status, ['resolved', 'closed']);
    }
}
