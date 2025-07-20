<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ManufacturingOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'mo_number',
        'product_id',
        'bom_id',
        'quantity_to_produce',
        'quantity_produced',
        'start_date',
        'completed_date',
        'status',
        'notes',
        'created_by',
        'updated_by',
    ];

    /**
     * Mendapatkan data produk jadi yang diproduksi.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Mendapatkan resep (BOM) yang digunakan.
     */
    public function bom(): BelongsTo
    {
        return $this->belongsTo(Bom::class);
    }
}
