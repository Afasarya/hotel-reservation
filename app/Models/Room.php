<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_type_id',
        'room_number',
        'status',
        'notes',
    ];

    /**
     * Get the room type
     */
    public function roomType()
    {
        return $this->belongsTo(RoomType::class);
    }

    /**
     * Get bookings for this room
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Check if room is available for specific dates
     */
    public function isAvailable($checkIn, $checkOut)
    {
        if ($this->status !== 'available') {
            return false;
        }

        $conflictingBookings = $this->bookings()
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
            ->exists();

        return !$conflictingBookings;
    }

    /**
     * Get current booking if room is occupied
     */
    public function currentBooking()
    {
        return $this->bookings()
            ->where('status', 'checked_in')
            ->where('check_in_date', '<=', now()->toDateString())
            ->where('check_out_date', '>', now()->toDateString())
            ->first();
    }
}
