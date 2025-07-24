<?php

namespace App\Models;

use App\Traits\Alertable;
use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MenuItem extends Model
{
    use HasFactory, Alertable, BelongsToTenant;

    protected $guarded = [];

    public function children(): HasMany
    {
        // Relasi ke dirinya sendiri, diurutkan berdasarkan 'order'
        return $this->hasMany(MenuItem::class, 'parent_id')->orderBy('order');
    }
}
