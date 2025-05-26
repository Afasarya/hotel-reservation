<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\RoomType;
use App\Models\Booking;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BookingController extends Controller
{
    public function index()
    {
        $bookings = Booking::where('user_id', Auth::id())
            ->with(['roomType', 'room', 'payment'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        return view('bookings.index', compact('bookings'));
    }
    
    public function create(RoomType $roomType)
    {
        return view('bookings.create', compact('roomType'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'room_type_id' => 'required|exists:room_types,id',
            'check_in_date' => 'required|date|after:today',
            'check_out_date' => 'required|date|after:check_in_date',
            'guests' => 'required|integer|min:1',
            'special_requests' => 'nullable|string|max:1000',
        ]);
        
        $roomType = RoomType::findOrFail($request->room_type_id);
        
        // Check if guests exceed capacity
        if ($request->guests > $roomType->capacity) {
            return back()->withErrors(['guests' => 'Number of guests exceeds room capacity.']);
        }
        
        $checkIn = Carbon::parse($request->check_in_date);
        $checkOut = Carbon::parse($request->check_out_date);
        $nights = $checkIn->diffInDays($checkOut);
        
        // Check room availability
        $availableRoom = Room::where('room_type_id', $roomType->id)
            ->where('status', 'available')
            ->whereDoesntHave('bookings', function($query) use ($checkIn, $checkOut) {
                $query->where(function($q) use ($checkIn, $checkOut) {
                    $q->whereBetween('check_in_date', [$checkIn, $checkOut])
                      ->orWhereBetween('check_out_date', [$checkIn, $checkOut])
                      ->orWhere(function($q2) use ($checkIn, $checkOut) {
                          $q2->where('check_in_date', '<=', $checkIn)
                             ->where('check_out_date', '>=', $checkOut);
                      });
                })->whereIn('status', ['pending', 'confirmed', 'checked_in']);
            })
            ->first();
        
        if (!$availableRoom) {
            return back()->withErrors(['check_in_date' => 'No rooms available for the selected dates.']);
        }
        
        $totalAmount = $nights * $roomType->price_per_night;
        
        DB::beginTransaction();
        try {
            $booking = Booking::create([
                'booking_code' => 'BK' . strtoupper(uniqid()),
                'user_id' => Auth::id(),
                'room_type_id' => $roomType->id,
                'room_id' => $availableRoom->id,
                'check_in_date' => $checkIn,
                'check_out_date' => $checkOut,
                'nights' => $nights,
                'guests' => $request->guests,
                'total_amount' => $totalAmount,
                'status' => 'pending',
                'special_requests' => $request->special_requests,
            ]);
            
            DB::commit();
            
            return redirect()->route('payments.create', $booking)
                ->with('success', 'Booking created successfully! Please proceed with payment.');
                
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Failed to create booking. Please try again.']);
        }
    }
    
    public function show(Booking $booking)
    {
        // Check if user owns this booking
        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }
        
        $booking->load(['roomType', 'room', 'payment']);
        
        return view('bookings.show', compact('booking'));
    }
    
    public function destroy(Booking $booking)
    {
        // Check if user owns this booking
        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }
        
        // Only allow cancellation if booking is pending or confirmed
        if (!in_array($booking->status, ['pending', 'confirmed'])) {
            return back()->withErrors(['error' => 'Cannot cancel this booking.']);
        }
        
        $booking->update([
            'status' => 'cancelled',
            'cancelled_at' => now(),
        ]);
        
        return redirect()->route('bookings.index')
            ->with('success', 'Booking cancelled successfully.');
    }
} 