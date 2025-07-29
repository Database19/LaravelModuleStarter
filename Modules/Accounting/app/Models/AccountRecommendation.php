<?php

namespace Modules\Accounting\app\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AccountRecommendation extends Model
{
    use HasFactory;

    protected $fillable = [
        'setting_key',
        'business_type_key',
        'keywords',
        'priority',
        'description',
        'is_active'
    ];

    protected $casts = [
        'keywords' => 'array',
        'priority' => 'integer',
        'is_active' => 'boolean'
    ];

    /**
     * Get business type relation
     */
    public function businessType()
    {
        return $this->belongsTo(BusinessType::class, 'business_type_key', 'key');
    }

    /**
     * Scope for active recommendations
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for specific setting key
     */
    public function scopeForSetting($query, $settingKey)
    {
        return $query->where('setting_key', $settingKey);
    }

    /**
     * Scope for specific business type
     */
    public function scopeForBusinessType($query, $businessTypeKey)
    {
        return $query->where('business_type_key', $businessTypeKey);
    }

    /**
     * Get recommendations for a specific setting and business type
     */
    public static function getRecommendationsFor($settingKey, $businessTypeKey)
    {
        return self::active()
                   ->forSetting($settingKey)
                   ->where(function($query) use ($businessTypeKey) {
                       $query->forBusinessType($businessTypeKey)
                             ->orWhere('business_type_key', 'all');
                   })
                   ->orderBy('priority', 'desc')
                   ->get();
    }

    /**
     * Find best matching account from a list
     */
    public function findBestMatch($accounts)
    {
        $bestMatch = null;
        $bestScore = 0;

        foreach ($accounts as $account) {
            $score = $this->calculateMatchScore($account);

            if ($score > $bestScore) {
                $bestScore = $score;
                $bestMatch = $account;
            }
        }

        return $bestMatch;
    }

    /**
     * Calculate match score for an account
     */
    protected function calculateMatchScore($account)
    {
        $score = 0;
        $accountText = strtolower($account->account_code . ' ' . $account->name);

        // Score based on keyword matches
        foreach ($this->keywords as $keyword) {
            if (strpos($accountText, strtolower($keyword)) !== false) {
                $score += strlen($keyword) * 2; // Longer keywords get more weight
            }
        }

        // Bonus for matching business type
        if ($account->business_type === $this->business_type_key || $account->business_type === 'all') {
            $score += 20;
        }

        // Apply priority multiplier
        $score *= $this->priority;

        return $score;
    }

    /**
     * Get all recommendations grouped by setting and business type
     */
    public static function getAllRecommendationsGrouped()
    {
        return self::active()
                   ->with('businessType')
                   ->get()
                   ->groupBy('setting_key')
                   ->map(function($group) {
                       return $group->groupBy('business_type_key');
                   });
    }
}
