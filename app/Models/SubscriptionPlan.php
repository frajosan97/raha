<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'duration_days',
        'price',
        'listing_priority',
        'is_featured',
        'is_active'
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'price' => 'decimal:2'
    ];

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    /**
     * Get formatted price
     */
    public function getFormattedPriceAttribute()
    {
        return 'Ksh ' . number_format($this->price, 2);
    }

    /**
     * Get duration in words
     */
    public function getDurationInWordsAttribute()
    {
        if ($this->duration_days == 7) return '1 Week';
        if ($this->duration_days == 30) return '1 Month';
        if ($this->duration_days == 180) return '6 Months';
        return $this->duration_days . ' Days';
    }
}
