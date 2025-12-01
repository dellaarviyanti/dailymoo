<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'category',
        'price',
        'stock',
        'image',
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];
    
    public function transactionItems()
    {
        return $this->hasMany(TransactionItem::class);
    }
    
    public function isInStock(): bool
    {
        return $this->stock > 0;
    }

    public function getImageUrlAttribute(): string
    {
        if (!$this->image) {
            return 'https://placehold.co/600x600/F4F4F4/888888?text=DailyMoo';
        }

        if (Str::startsWith($this->image, ['http://', 'https://'])) {
            return $this->image;
        }

        // Use route to serve image directly (more reliable than storage link)
        return route('products.image', $this->id);
    }
}
