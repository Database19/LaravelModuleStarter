<?php

namespace App\Models;

use App\Traits\Alertable;
use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    use Alertable, BelongsToTenant;

    protected $fillable = ['name', 'company_name', 'email', 'phone', 'source', 'status', 'owner_id', 'notes'];

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }
}
