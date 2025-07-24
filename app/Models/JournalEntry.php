<?php

namespace App\Models;

use App\Traits\Alertable;
use App\Traits\BelongsToTenant;
use App\Traits\Userstamps;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JournalEntry extends Model
{
    use HasFactory, Alertable, Userstamps, BelongsToTenant;

    protected $fillable = [
        'journal_number', 'date', 'description', 'total_debit',
        'total_credit', 'referenceable_type', 'referenceable_id',
        'user_id', 'is_posted'
    ];

    protected $casts = [
        'date' => 'date',
        'total_debit' => 'decimal:2',
        'total_credit' => 'decimal:2',
        'is_posted' => 'boolean'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function items()
    {
        return $this->hasMany(JournalEntryItem::class);
    }

    public function referenceable()
    {
        return $this->morphTo();
    }
}
