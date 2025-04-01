<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserGallery extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'image_path',
        'is_public',
        'is_primary',
        'sort_order'
    ];

    protected $casts = [
        'is_public' => 'boolean',
        'is_primary' => 'boolean'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get full image URL
     */
    public function getImageUrlAttribute()
    {
        return asset($this->image_path);
    }

    /**
     * Scope for public images
     */
    public function scopePublic($query)
    {
        return $query->where('is_public', true);
    }
}
