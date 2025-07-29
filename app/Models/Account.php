<?php

namespace App\Models;

use App\Traits\Alertable;
use App\Traits\BelongsToCompany;
use App\Traits\BelongsToTenant;
use App\Traits\Userstamps;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory, Alertable, Userstamps, BelongsToTenant, BelongsToCompany;

    protected $fillable = [
        'account_code', 'name', 'type', 'sub_type', 'parent_id',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    public function parent()
    {
        return $this->belongsTo(Account::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Account::class, 'parent_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function journalEntryItems()
    {
        return $this->hasMany(JournalEntryItem::class);
    }

    public function businessType()
    {
        return $this->belongsTo(BusinessType::class, 'business_type', 'key');
    }

    /**
     * Scope untuk filter berdasarkan business type
     */
    public function scopeForBusinessType($query, $businessType)
    {
        if ($businessType === 'all') {
            return $query;
        }

        return $query->where(function($q) use ($businessType) {
            $q->where('business_type', 'all')
              ->orWhere('business_type', 'like', "%{$businessType}%");
        });
    }

    /**
     * Scope untuk akun aktif
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get accounts grouped by type for specific business type
     */
    public static function getAccountsByTypeForBusiness($businessType = 'all')
    {
        return self::active()
            ->forBusinessType($businessType)
            ->orderBy('account_code')
            ->get()
            ->groupBy('type');
    }

    /**
     * Get dropdown options for specific business type
     */
    public static function getDropdownForBusiness($businessType = 'all', $type = null)
    {
        $query = self::active()->forBusinessType($businessType);

        if ($type) {
            $query->where('type', $type);
        }

        return $query->orderBy('account_code')
            ->pluck('name', 'id')
            ->toArray();
    }
}
