<?php

namespace App\Models;

use App\Traits\Alertable;
use App\Traits\Userstamps;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FixedAsset extends Model
{
    use HasFactory, Alertable, Userstamps;

    protected $fillable = [
        'asset_name', 'asset_code', 'description', 'purchase_date', 'purchase_cost',
        'useful_life', 'salvage_value', 'monthly_depreciation', 'asset_account_id',
        'accumulated_depreciation_account_id', 'depreciation_expense_account_id',
        'status', 'disposal_date'
    ];

    protected $casts = [
        'purchase_date' => 'date',
        'disposal_date' => 'date',
        'purchase_cost' => 'decimal:2',
        'salvage_value' => 'decimal:2',
        'monthly_depreciation' => 'decimal:2',
    ];
}
