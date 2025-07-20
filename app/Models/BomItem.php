<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BomItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'bom_id',
        'component_product_id',
        'quantity',
    ];

    // Tabel ini tidak memiliki kolom timestamps (created_at/updated_at)
    public $timestamps = false;

    /**
     * Mendapatkan data resep induk dari item ini.
     */
    public function bom(): BelongsTo
    {
        return $this->belongsTo(Bom::class);
    }

    /**
     * Mendapatkan data produk komponen dari item ini.
     */
    public function component(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'component_product_id');
    }
}
