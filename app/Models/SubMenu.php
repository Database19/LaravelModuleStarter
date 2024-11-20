<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubMenu extends Model
{
    use HasFactory;

    protected $fillable = ['parent_id', 'name', 'description', 'icons', 'route', 'is_active'];

    public function menu()
    {
        return $this->belongsTo(Menu::class, 'parent_id');
    }
}
