<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'whatsapp',
        'dob',
        'gender',
        'nationality',
        'hair_color',
        'eye_color',
        'measurements',
        'city',
        'country',
        'latitude',
        'longitude',
        'about',
        'languages',
        'tags',
        'hourly_rate',
        'availability',
        'is_escort',
        'has_active_subscription',
        'subscription_expires_at',
        'is_verified',
        'is_featured',
        'is_banned',
        'last_online_at'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'dob' => 'date',
        'measurements' => 'array',
        'languages' => 'array',
        'tags' => 'array',
        'is_escort' => 'boolean',
        'has_active_subscription' => 'boolean',
        'is_verified' => 'boolean',
        'is_featured' => 'boolean',
        'is_banned' => 'boolean',
        'subscription_expires_at' => 'datetime',
        'last_online_at' => 'datetime',
        'last_subscription_reminder_at' => 'datetime',
        'hourly_rate' => 'decimal:2',
    ];

    /**
     * Get all subscriptions for the user
     */
    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    public function inactiveSubscription()
    {
        return $this->hasOne(Subscription::class)
            ->where('payment_status', 'pending')
            ->where('end_date', '>', now())
            ->latest();
    }

    /**
     * Get the active subscription
     */
    public function activeSubscription()
    {
        return $this->hasOne(Subscription::class)
            ->where('payment_status', 'paid')
            ->where('end_date', '>', now())
            ->latest();
    }

    /**
     * Get user preferences
     */
    public function preferences()
    {
        return $this->hasOne(UserPreference::class);
    }

    /**
     * Get user services
     */
    public function services()
    {
        return $this->hasMany(UserService::class);
    }

    /**
     * Get user gallery images
     */
    public function gallery()
    {
        return $this->hasMany(UserGallery::class);
    }

    /**
     * Check if user has active subscription
     */
    public function hasActiveSubscription()
    {
        return $this->has_active_subscription &&
            $this->subscription_expires_at &&
            $this->subscription_expires_at->isFuture();
    }

    /**
     * Get primary profile image
     */
    public function primaryImage()
    {
        return $this->gallery()->where('is_primary', true)->first();
    }

    /**
     * Scope for escorts only
     */
    public function scopeEscorts($query)
    {
        return $query->where('is_escort', true);
    }

    /**
     * Scope for verified users
     */
    public function scopeVerified($query)
    {
        return $query->where('is_verified', true);
    }

    /**
     * Scope for featured users
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope for available users
     */
    public function scopeAvailable($query)
    {
        return $query->where('availability', 'available');
    }

    public function getAgeAttribute()
    {
        return $this->dob ? -(now()->diffInYears($this->dob)) : 0;
    }
}
