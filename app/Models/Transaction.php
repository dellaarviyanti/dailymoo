<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'customer_name',
        'customer_address',
        'customer_phone',
        'total_amount',
        'status',
        'payment_proof',
        'bank_account',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
    ];

    /**
     * Get payment proof URL
     */
    public function getPaymentProofUrlAttribute(): ?string
    {
        if (!$this->payment_proof) {
            return null;
        }

        // If it's already a full URL, return as is
        if (Str::startsWith($this->payment_proof, ['http://', 'https://'])) {
            return $this->payment_proof;
        }

        // Use route-based URL (most reliable)
        return route('payment.proof', $this->id);
    }
    
    /**
     * Get the user that owns the transaction
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    /**
     * Get transaction items
     */
    public function items()
    {
        return $this->hasMany(TransactionItem::class);
    }
    
    /**
     * Get status badge color
     */
    public function getStatusBadgeAttribute()
    {
        return match($this->status) {
            'pending' => 'bg-gray-100 text-gray-700',
            'pending_payment' => 'bg-yellow-100 text-yellow-700',
            'payment_verification' => 'bg-orange-100 text-orange-700',
            'processing' => 'bg-blue-100 text-blue-700',
            'shipped' => 'bg-purple-100 text-purple-700',
            'completed' => 'bg-green-100 text-green-700',
            'cancelled' => 'bg-red-100 text-red-700',
            default => 'bg-gray-100 text-gray-700',
        };
    }
    
    /**
     * Get status label
     */
    public function getStatusLabelAttribute()
    {
        return match($this->status) {
            'pending' => 'Menunggu',
            'pending_payment' => 'Menunggu Pembayaran',
            'payment_verification' => 'Verifikasi Pembayaran',
            'processing' => 'Diproses',
            'shipped' => 'Dikirim',
            'completed' => 'Selesai',
            'cancelled' => 'Dibatalkan',
            default => 'Unknown',
        };
    }
}
