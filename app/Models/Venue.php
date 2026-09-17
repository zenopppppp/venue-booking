<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Venue extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'location',
        'price_per_day',
        'capacity',
        'description',
        'image',
        'is_available',
    ];

    // Venue belongs to a User/Vendor
    public function vendor()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Venue has many reviews
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    // Average rating helper (keep this as it is)
    public function averageRating()
    {
        return round($this->reviews()->avg('rating'), 1) ?: 'New';
    }
}
