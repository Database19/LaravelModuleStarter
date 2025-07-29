<?php

namespace App\Models;

use App\Traits\Alertable;
use App\Traits\BelongsToCompany;
use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class AccountingSetting extends Model
{
    use Alertable, BelongsToTenant, BelongsToCompany;

    public $timestamps = false;

    protected $fillable = [
        'key',
        'value',
    ];
}
