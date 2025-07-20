<?php
namespace Modules\Accounting\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Accounting\Entities\Account;

class JournalEntryItem extends Model
{
    protected $fillable = ['journal_entry_id', 'account_id', 'debit', 'credit', 'description'];

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }
}
