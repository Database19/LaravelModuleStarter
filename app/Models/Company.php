<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;

class Company extends Model
{
    protected $fillable = ['name', 'domain'];

    protected static function boot()
    {
        parent::boot();

        // Pastikan domain unik
        static::creating(function ($company) {
            if (static::where('domain', $company->domain)->exists()) {
                throw new \Exception("Domain {$company->domain} sudah digunakan oleh perusahaan lain.");
            }
        });

        static::updating(function ($company) {
            if (static::where('domain', $company->domain)->where('id', '!=', $company->id)->exists()) {
                throw new \Exception("Domain {$company->domain} sudah digunakan oleh perusahaan lain.");
            }
        });
    }

    public function subscriptions()
    {
        return $this->hasMany(CompanySubscription::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function departments()
    {
        return $this->hasMany(Department::class);
    }

    public function positions()
    {
        return $this->hasMany(Position::class);
    }

    /**
     * Validasi rules untuk domain
     */
    public static function domainValidationRules($ignoreId = null)
    {
        return [
            'domain' => [
                'required',
                'string',
                'max:255',
                'regex:/^[a-zA-Z0-9.-]+$/', // Format domain yang valid
                Rule::unique('companies', 'domain')->ignore($ignoreId),
            ]
        ];
    }
}
