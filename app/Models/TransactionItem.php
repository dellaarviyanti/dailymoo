<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_id',
        'product_id',
        'quantity',
        'price',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'price' => 'decimal:2',
    ];
    
    /**
     * Get the transaction
     */
    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }
    
    /**
     * Get the product
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    
    /**
     * Get subtotal
     */
    public function getSubtotalAttribute()
    {
        return $this->quantity * $this->price;
    }
}
