<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use App\Traits\Userstamps;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JournalEntryItem extends Model
{
    use HasFactory, Userstamps, BelongsToTenant;

    protected $fillable = [
        'journal_entry_id', 'description', 'account_id', 'debit',
        'credit'
    ];

    protected $casts = [
        'debit' => 'decimal:2',
        'credit' => 'decimal:2'
    ];

    public function journalEntry()
    {
        return $this->belongsTo(JournalEntry::class);
    }

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
