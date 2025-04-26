<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'plan_id',
        'payment_reference',
        'amount_paid',
        'payment_method',
        'payment_status',
        'start_date',
        'end_date',
        'is_auto_renew',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'last_reminder_sent_at' => 'datetime',
        'amount_paid' => 'decimal:2',
        'is_auto_renew' => 'boolean',
    ];

    /* ======================= */
    /* ===== RELATIONSHIPS === */
    /* ======================= */

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function plan()
    {
        return $this->belongsTo(SubscriptionPlan::class);
    }

    /* ======================= */
    /* ======== SCOPES ======= */
    /* ======================= */

    public function scopeActive($query)
    {
        return $query->where('payment_status', 'paid')
            ->where('end_date', '>', now());
    }

    /* ======================= */
    /* ==== CUSTOM METHODS === */
    /* ======================= */

    /**
     * Determine if the subscription is active.
     *
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->payment_status === 'paid'
            && $this->end_date
            && $this->end_date->isFuture();
    }
}
