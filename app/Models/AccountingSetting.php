<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccountingSetting extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'key',
        'value',
    ];
}
