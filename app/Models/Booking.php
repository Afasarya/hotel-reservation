<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_code',
        'user_id',
        'room_type_id',
        'room_id',
        'check_in_date',
        'check_out_date',
        'nights',
        'guests',
        'total_amount',
        'status',
        'special_requests',
        'confirmed_at',
        'cancelled_at',
    ];

    protected $casts = [
        'check_in_date' => 'date',
        'check_out_date' => 'date',
        'total_amount' => 'decimal:2',
        'confirmed_at' => 'datetime',
        'cancelled_at' => 'datetime',
    ];

    /**
     * Generate unique booking code
     */
    public static function generateBookingCode()
    {
        do {
            $code = 'BK' . date('Ymd') . strtoupper(substr(uniqid(), -6));
        } while (self::where('booking_code', $code)->exists());

        return $code;
    }

    /**
     * Calculate nights between check-in and check-out
     */
    public static function calculateNights($checkIn, $checkOut)
    {
        return Carbon::parse($checkIn)->diffInDays(Carbon::parse($checkOut));
    }

    /**
     * Get the user who made the booking
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the room type
     */
    public function roomType()
    {
        return $this->belongsTo(RoomType::class);
    }

    /**
     * Get the assigned room
     */
    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    /**
     * Get the payment for this booking
     */
    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    /**
     * Check if booking can be cancelled
     */
    public function canBeCancelled()
    {
        return in_array($this->status, ['pending', 'confirmed']) && 
               $this->check_in_date > now()->toDateString();
    }

    /**
     * Cancel the booking
     */
    public function cancel()
    {
        if ($this->canBeCancelled()) {
            $this->update([
                'status' => 'cancelled',
                'cancelled_at' => now(),
            ]);

            // Free up the room if assigned
            if ($this->room_id) {
                $this->room->update(['status' => 'available']);
            }

            return true;
        }

        return false;
    }

    /**
     * Confirm the booking
     */
    public function confirm()
    {
        if ($this->status === 'pending') {
            $this->update([
                'status' => 'confirmed',
                'confirmed_at' => now(),
            ]);

            return true;
        }

        return false;
    }

    /**
     * Check in the booking
     */
    public function checkIn()
    {
        if ($this->status === 'confirmed' && $this->check_in_date <= now()->toDateString()) {
            $this->update(['status' => 'checked_in']);

            // Update room status
            if ($this->room_id) {
                $this->room->update(['status' => 'occupied']);
            }

            return true;
        }

        return false;
    }

    /**
     * Check out the booking
     */
    public function checkOut()
    {
        if ($this->status === 'checked_in') {
            $this->update(['status' => 'checked_out']);

            // Update room status
            if ($this->room_id) {
                $this->room->update(['status' => 'cleaning']);
            }

            return true;
        }

        return false;
    }
}
