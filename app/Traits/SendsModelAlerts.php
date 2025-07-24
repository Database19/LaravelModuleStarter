<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;

trait SendsModelAlerts
{
    /**
     * Boot the trait.
     * Secara otomatis mendaftarkan event listener saat model di-boot.
     */
    protected static function booted(): void
    {
        // Event setelah data berhasil dibuat
        static::created(function (Model $model) {
            $modelName = class_basename($model); // Mendapatkan nama model, misal: "Post"
            session()->flash('alert', [
                'type'  => 'success',
                'title' => 'Berhasil Dibuat!',
                'text'  => "Data {$modelName} baru berhasil disimpan.",
            ]);
        });

        // Event setelah data berhasil diperbarui
        static::updated(function (Model $model) {
            $modelName = class_basename($model);
            session()->flash('alert', [
                'type'  => 'success',
                'title' => 'Berhasil Diperbarui!',
                'text'  => "Data {$modelName} berhasil diperbarui.",
            ]);
        });

        // Event setelah data berhasil dihapus
        static::deleted(function (Model $model) {
            $modelName = class_basename($model);
            session()->flash('alert', [
                'type'  => 'warning',
                'title' => 'Berhasil Dihapus!',
                'text'  => "Data {$modelName} telah dihapus.",
            ]);
        });
    }
}
