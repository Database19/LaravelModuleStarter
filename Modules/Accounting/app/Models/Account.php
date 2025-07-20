<?php

namespace Modules\Accounting\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Accounting\Entities\JournalEntryItem;

class Account extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function journalEntryItems()
    {
        return $this->hasMany(JournalEntryItem::class);
    }
}
