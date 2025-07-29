<?php

namespace App\Models;

use App\Traits\Alertable;
use App\Traits\BelongsToCompany;
use App\Traits\BelongsToTenant;
use App\Traits\Userstamps;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Bom extends Model
{
    use HasFactory, Alertable, Userstamps, BelongsToTenant, BelongsToCompany;

    // Nama tabel secara eksplisit jika berbeda dari penamaan standar 'boms'
    protected $table = 'boms';

    protected $fillable = [
        'product_id',
        'name',
        'description',
    ];

    /**
     * Mendapatkan data produk jadi dari resep ini.
     */
    public function finishedGood(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    /**
     * Satu resep memiliki banyak item komponen.
     */
    public function items(): HasMany
    {
        return $this->hasMany(BomItem::class);
    }
}
