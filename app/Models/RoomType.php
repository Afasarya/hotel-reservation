<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoomType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price_per_night',
        'capacity',
        'total_rooms',
        'facilities',
        'images',
        'is_active',
    ];

    protected $casts = [
        'price_per_night' => 'decimal:2',
        'facilities' => 'array',
        'images' => 'array',
        'is_active' => 'boolean',
    ];

    /**
     * Get rooms of this type
     */
    public function rooms()
    {
        return $this->hasMany(Room::class);
    }

    /**
     * Get bookings for this room type
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Get available rooms count for specific dates
     */
    public function getAvailableRoomsCount($checkIn, $checkOut)
    {
        $bookedRooms = $this->bookings()
            ->where(function ($query) use ($checkIn, $checkOut) {
                $query->where(function ($q) use ($checkIn, $checkOut) {
                    $q->where('check_in_date', '<=', $checkIn)
                      ->where('check_out_date', '>', $checkIn);
                })->orWhere(function ($q) use ($checkIn, $checkOut) {
                    $q->where('check_in_date', '<', $checkOut)
                      ->where('check_out_date', '>=', $checkOut);
                })->orWhere(function ($q) use ($checkIn, $checkOut) {
                    $q->where('check_in_date', '>=', $checkIn)
                      ->where('check_out_date', '<=', $checkOut);
                });
            })
            ->whereIn('status', ['confirmed', 'checked_in'])
            ->count();

        return $this->total_rooms - $bookedRooms;
    }

    /**
     * Check if room type is available for booking
     */
    public function isAvailable($checkIn, $checkOut)
    {
        return $this->is_active && $this->getAvailableRoomsCount($checkIn, $checkOut) > 0;
    }
}
