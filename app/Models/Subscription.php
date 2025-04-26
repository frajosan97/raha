<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
        'payment_references',
        'amount_billed',
        'amount_paid',
        'payment_method',
        'payment_status',
        'start_date',
        'end_date',
        'is_auto_renew',
        'last_reminder_sent_at',
        'currency',
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
        'amount_billed' => 'decimal:2',
        'amount_paid' => 'decimal:2',
        'is_auto_renew' => 'boolean',
        'payment_references' => 'array',
    ];

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'payment_status' => 'pending',
        'payment_method' => 'mpesa',
        'currency' => 'KES',
        'is_auto_renew' => false,
    ];

    /* ======================= */
    /* ===== RELATIONSHIPS === */
    /* ======================= */

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function plan(): BelongsTo
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

    public function scopeExpired($query)
    {
        return $query->where('end_date', '<=', now());
    }

    public function scopePendingPayment($query)
    {
        return $query->whereIn('payment_status', ['pending', 'partial']);
    }

    /* ======================= */
    /* ==== CUSTOM METHODS === */
    /* ======================= */

    public function isActive(): bool
    {
        return $this->payment_status === 'paid'
            && $this->end_date?->isFuture();
    }

    public function isExpired(): bool
    {
        return $this->end_date?->isPast() ?? true;
    }

    public function hasPartialPayment(): bool
    {
        return $this->payment_status === 'partial'
            && $this->amount_paid > 0;
    }

    public function needsPayment(): bool
    {
        return in_array($this->payment_status, ['pending', 'partial'])
            && $this->amount_paid < $this->amount_billed;
    }

    public function markAsPaid(): bool
    {
        return $this->update([
            'payment_status' => 'paid',
            'amount_paid' => $this->amount_billed,
            'end_date' => $this->calculateEndDate(),
        ]);
    }

    protected function calculateEndDate()
    {
        return $this->plan->duration_months
            ? now()->addMonths($this->plan->duration_months)
            : now()->addYear();
    }
}
