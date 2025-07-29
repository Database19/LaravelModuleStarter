<?php

namespace App\Traits;

use App\Models\Company;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Spatie\Multitenancy\Models\Tenant;

trait BelongsToTenant
{
    public static function bootBelongsToTenant()
    {
        // Filter Otomatis (Global Scope) - kecuali untuk Super Admin
        static::addGlobalScope('tenant', function (Builder $builder) {
            // Skip global scope jika user adalah super admin
            $user = Auth::user();
            if ($user && isset($user->is_super_admin) && $user->is_super_admin) {
                return;
            }

            if (Tenant::checkCurrent()) {
                $builder->where('company_id', Tenant::current()->id);
            }
        });

        // Pengisian Otomatis (Model Event)
        static::creating(function (Model $model) {
            if (Tenant::checkCurrent() && is_null($model->company_id)) {
                $model->company_id = Tenant::current()->id;
            }
        });
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Scope untuk bypass tenant restriction (untuk Super Admin)
     */
    public function scopeWithoutTenantRestriction($query)
    {
        return $query->withoutGlobalScope('tenant');
    }

    /**
     * Scope untuk filter by specific company (untuk Super Admin)
     */
    public function scopeForCompany($query, $companyId)
    {
        return $query->withoutGlobalScope('tenant')->where('company_id', $companyId);
    }
}
