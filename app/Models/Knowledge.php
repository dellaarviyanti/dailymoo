<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Knowledge extends Model
{
    use HasFactory;

    protected $table = 'knowledge';

    protected $fillable = [
        'title',
        'excerpt',
        'content',
        'category',
        'image',
        'date',
    ];

    /**
     * Get image URL attribute
     */
    public function getImageUrlAttribute(): string
    {
        if (!$this->image) {
            return 'https://placehold.co/800x600/5DB996/ffffff?text=DailyMoo';
        }

        if (Str::startsWith($this->image, ['http://', 'https://'])) {
            return $this->image;
        }

        // Use route to serve image directly (more reliable than storage link)
        return route('knowledge.image', $this->id);
    }
}
