<?php
namespace Modules\Accounting\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Accounting\Entities\JournalEntryItem;

class JournalEntry extends Model
{
    protected $fillable = ['date', 'description', 'referenceable_type', 'referenceable_id', 'user_id'];

    public function items(): HasMany
    {
        return $this->hasMany(JournalEntryItem::class);
    }
}
