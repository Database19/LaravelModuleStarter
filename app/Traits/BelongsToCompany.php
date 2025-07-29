<?php

namespace App\Traits;

trait BelongsToCompany
{
    protected static function bootBelongsToCompany()
    {
        // Secara otomatis mengisi company_id saat membuat record baru
        static::creating(function ($model) {
            if (auth()->check() && auth()->user()->company_id) {
                $model->company_id = auth()->user()->company_id;
            }
        });
    }
}
