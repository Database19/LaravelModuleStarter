<?php

namespace App\Traits;

use App\Models\Company;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Spatie\Multitenancy\Models\Tenant;

trait BelongsToTenant
{
    public static function bootBelongsToTenant()
    {
        // Filter Otomatis (Global Scope)
        static::addGlobalScope('tenant', function (Builder $builder) {
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
}
