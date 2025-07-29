<?php

namespace Modules\Accounting\app\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BusinessType extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'name',
        'description',
        'icon',
        'color',
        'is_active',
        'sort_order'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer'
    ];

    /**
     * Get dropdown options for business types
     */
    public static function getDropdownOptions()
    {
        return self::where('is_active', true)
                   ->orderBy('sort_order')
                   ->orderBy('name')
                   ->pluck('name', 'key')
                   ->toArray();
    }

    /**
     * Get business type by key
     */
    public static function getByKey($key)
    {
        return self::where('key', $key)
                   ->where('is_active', true)
                   ->first();
    }

    /**
     * Scope for active business types
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for ordered business types
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    /**
     * Get account recommendations for this business type
     */
    public function accountRecommendations()
    {
        return $this->hasMany(AccountRecommendation::class, 'business_type_key', 'key');
    }

    /**
     * Get accounts related to this business type
     */
    public function accounts()
    {
        return $this->hasMany(\Modules\Accounting\Entities\Account::class, 'business_type', 'key');
    }

    /**
     * Get formatted data for frontend
     */
    public function toDropdownArray()
    {
        return [
            'key' => $this->key,
            'name' => $this->name,
            'description' => $this->description,
            'icon' => $this->icon,
            'color' => $this->color
        ];
    }
}
