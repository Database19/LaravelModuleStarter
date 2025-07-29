<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;


class CompanySubscription extends Model
{
    protected $fillable = ['company_id', 'module_key', 'expires_at'];
}
