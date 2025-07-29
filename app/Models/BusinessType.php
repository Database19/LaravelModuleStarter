<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusinessType extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'name',
        'description',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Scope untuk business type yang aktif
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get all accounts for this business type
     */
    public function accounts()
    {
        return $this->hasMany(Account::class, 'business_type', 'key');
    }

    /**
     * Get business type options for dropdown
     */
    public static function getDropdownOptions()
    {
        return self::active()
            ->orderBy('name')
            ->pluck('name', 'key')
            ->toArray();
    }
}
