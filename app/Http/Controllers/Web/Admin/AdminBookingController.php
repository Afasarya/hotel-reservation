<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;

class AdminBookingController extends Controller
{
    public function index(Request $request)
    {
        $query = Booking::with(['user', 'roomType', 'room', 'payment']);
        
        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }
        
        // Filter by date range
        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('check_in_date', '>=', $request->date_from);
        }
        
        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('check_out_date', '<=', $request->date_to);
        }
        
        // Search by booking code or user name
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('booking_code', 'like', "%{$search}%")
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%")
                               ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }
        
        $bookings = $query->orderBy('created_at', 'desc')->paginate(15);
        
        return view('admin.bookings.index', compact('bookings'));
    }
    
    public function show(Booking $booking)
    {
        $booking->load(['user', 'roomType', 'room', 'payment']);
        
        return view('admin.bookings.show', compact('booking'));
    }
    
    public function confirm(Booking $booking)
    {
        if ($booking->status !== 'pending') {
            return back()->withErrors(['error' => 'Only pending bookings can be confirmed.']);
        }
        
        $booking->update([
            'status' => 'confirmed',
            'confirmed_at' => now(),
        ]);
        
        return back()->with('success', 'Booking confirmed successfully.');
    }
    
    public function checkIn(Booking $booking)
    {
        if ($booking->status !== 'confirmed') {
            return back()->withErrors(['error' => 'Only confirmed bookings can be checked in.']);
        }
        
        $booking->update(['status' => 'checked_in']);
        
        // Update room status
        if ($booking->room) {
            $booking->room->update(['status' => 'occupied']);
        }
        
        return back()->with('success', 'Guest checked in successfully.');
    }
    
    public function checkOut(Booking $booking)
    {
        if ($booking->status !== 'checked_in') {
            return back()->withErrors(['error' => 'Only checked-in bookings can be checked out.']);
        }
        
        $booking->update(['status' => 'checked_out']);
        
        // Update room status
        if ($booking->room) {
            $booking->room->update(['status' => 'cleaning']);
        }
        
        return back()->with('success', 'Guest checked out successfully.');
    }
    
    public function cancel(Booking $booking)
    {
        if (!in_array($booking->status, ['pending', 'confirmed'])) {
            return back()->withErrors(['error' => 'Cannot cancel this booking.']);
        }
        
        $booking->update([
            'status' => 'cancelled',
            'cancelled_at' => now(),
        ]);
        
        return back()->with('success', 'Booking cancelled successfully.');
    }
} 