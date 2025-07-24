<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;

trait Alertable
{
    protected static function bootAlertable(): void
    {
        static::created(function (Model $model) {
            $modelName = class_basename($model);
            a('Berhasil!', "Data berhasil dibuat.", 'success');
        });

        static::updated(function (Model $model) {
            $modelName = class_basename($model);
            a('Berhasil!', "Data berhasil terupdate.", 'success');
        });

        static::deleted(function (Model $model) {
            $modelName = class_basename($model);
            a('Berhasil!', "Data berhasil dihapus.", 'success');
        });
    }
}
