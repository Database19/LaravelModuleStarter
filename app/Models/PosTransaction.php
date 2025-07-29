<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PosTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_no',
        'type',
        'customer_id',
        'cashier_id',
        'subtotal',
        'tax_amount',
        'discount_amount',
        'total_amount',
        'status',
        'payment_status',
        'transaction_date',
        'notes',
        'reference_transaction_id'
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'transaction_date' => 'datetime'
    ];

    // Relationships
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function cashier()
    {
        return $this->belongsTo(User::class, 'cashier_id');
    }

    public function items()
    {
        return $this->hasMany(PosTransactionItem::class);
    }

    public function payments()
    {
        return $this->hasMany(PosPayment::class);
    }

    public function referenceTransaction()
    {
        return $this->belongsTo(PosTransaction::class, 'reference_transaction_id');
    }

    public function returnTransactions()
    {
        return $this->hasMany(PosTransaction::class, 'reference_transaction_id');
    }

    // Scopes
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeToday($query)
    {
        return $query->whereDate('transaction_date', today());
    }

    // Mutators
    public static function generateTransactionNo()
    {
        $prefix = 'POS-' . date('Ymd') . '-';
        $lastTransaction = self::whereDate('created_at', today())
            ->where('transaction_no', 'like', $prefix . '%')
            ->orderBy('id', 'desc')
            ->first();

        if ($lastTransaction) {
            $lastNumber = intval(substr($lastTransaction->transaction_no, -4));
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return $prefix . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }
}
