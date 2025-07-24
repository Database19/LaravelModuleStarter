<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

trait Userstamps
{
    protected static function booted(): void
    {
        parent::booted();

        // Event ini berjalan SEBELUM data baru disimpan ke database.
        // Sangat cocok untuk mengisi 'created_by' dan 'updated_by'.
        static::creating(function (Model $model) {
            if (Auth::check()) {
                // Periksa apakah kolom 'created_by' ada di tabel.
                if (Schema::hasColumn($model->getTable(), 'created_by')) {
                    $model->created_by = Auth::id();
                }
                // Periksa apakah kolom 'updated_by' ada di tabel.
                if (Schema::hasColumn($model->getTable(), 'updated_by')) {
                    $model->updated_by = Auth::id();
                }
            }
        });

        // Event ini berjalan SEBELUM data yang ada di-update.
        // Cocok untuk mengisi 'updated_by'.
        static::updating(function (Model $model) {
            if (Auth::check()) {
                // Periksa apakah kolom 'updated_by' ada di tabel.
                if (Schema::hasColumn($model->getTable(), 'updated_by')) {
                    $model->updated_by = Auth::id();
                }
            }
        });

        // Event ini khusus untuk model yang menggunakan SoftDeletes.
        // Event berjalan SEBELUM data ditandai sebagai "dihapus".
        if (in_array(SoftDeletes::class, class_uses_recursive(static::class))) {
            static::deleting(function (Model $model) {
                if (Auth::check()) {
                    // Periksa apakah kolom 'deleted_by' ada di tabel.
                    if (Schema::hasColumn($model->getTable(), 'deleted_by')) {
                        // Isi 'deleted_by' dan simpan sebelum model di-soft delete.
                        $model->deleted_by = Auth::id();
                        $model->save();
                    }
                }
            });
        }
    }
}
