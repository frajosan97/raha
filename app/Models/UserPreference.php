<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPreference extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'gender_preference',
        'min_age',
        'max_age',
        'location_preference',
        'other_preferences'
    ];

    protected $casts = [
        'gender_preference' => 'array',
        'location_preference' => 'array',
        'other_preferences' => 'array'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
