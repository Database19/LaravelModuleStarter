<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'icons', 'route', 'is_active','permission'];

    public function subMenus()
    {
        return $this->hasMany(SubMenu::class, 'parent_id');
    }

    public static function booted()
    {
        static::updated(function ($menu) {
            if ($menu->is_active == 0) {
                $menu->subMenus()->update(['is_active' => 0]);
            }
        });
    }
}

