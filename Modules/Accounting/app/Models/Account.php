<?php

namespace Modules\Accounting\Entities;

use App\Models\BusinessType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Accounting\Entities\JournalEntryItem;

class Account extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $fillable = [
        'account_code',
        'name',
        'type',
        'business_type',
        'is_active',
        'created_by',
        'updated_by',
        'company_id'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function journalEntryItems()
    {
        return $this->hasMany(JournalEntryItem::class);
    }

    /**
     * Get business type relation
     */
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
