<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionPlan extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'duration_days',
        'price',
        'listing_priority',
        'is_featured',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'price' => 'decimal:2',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'formatted_price',
        'duration_in_words',
    ];

    /* ======================= */
    /* ===== RELATIONSHIPS === */
    /* ======================= */

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    /* ======================= */
    /* ==== ACCESSORS ======== */
    /* ======================= */

    /**
     * Get the formatted price with currency.
     *
     * @return string
     */
    public function getFormattedPriceAttribute(): string
    {
        return 'Ksh ' . number_format($this->price, 2);
    }

    /**
     * Get human-readable duration description.
     *
     * @return string
     */
    public function getDurationInWordsAttribute(): string
    {
        return match ($this->duration_days) {
            7 => '1 Week',
            30 => '1 Month',
            90 => '3 Months',
            180 => '6 Months',
            365 => '1 Year',
            default => $this->duration_days . ' Days',
        };
    }
}
