<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;

    protected $fillable = [
        'account_code', 'name', 'type', 'sub_type', 'parent_id',
        'is_active', 'created_by', 'updated_by'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    public function parent()
    {
        return $this->belongsTo(Account::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Account::class, 'parent_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function journalEntryItems()
    {
        return $this->hasMany(JournalEntryItem::class);
    }
}
