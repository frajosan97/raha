<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MpesaPayment extends Model
{
    use HasFactory;

    // Define which attributes are mass assignable
    protected $fillable = [
        'user_id',
        'subscription_id',
        'transaction_id',
        'reference',
        'phone_number',
        'name',
        'amount',
        'status',
    ];

    /**
     * Relationship: Payment belongs to a User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relationship: Payment belongs to a Subscription
     */
    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }
}
