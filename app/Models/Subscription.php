<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'plan_id',
        'payment_reference',
        'amount_paid',
        'payment_method',
        'payment_status',
        'start_date',
        'end_date',
        'is_auto_renew'
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'last_reminder_sent_at' => 'datetime',
        'amount_paid' => 'decimal:2',
        'is_auto_renew' => 'boolean'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function plan()
    {
        return $this->belongsTo(SubscriptionPlan::class);
    }

    /**
     * Check if subscription is active
     */
    public function isActive()
    {
        return $this->payment_status === 'paid' &&
            $this->end_date &&
            $this->end_date->isFuture();
    }

    /**
     * Scope for active subscriptions
     */
    public function scopeActive($query)
    {
        return $query->where('payment_status', 'paid')
            ->where('end_date', '>', now());
    }
}
