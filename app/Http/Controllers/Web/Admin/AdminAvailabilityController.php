<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\RoomType;
use App\Models\Booking;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AdminAvailabilityController extends Controller
{
    public function index(Request $request)
    {
        $roomTypes = RoomType::with(['rooms' => function($query) {
            $query->orderBy('room_number');
        }])->get();
        
        // Get date range for availability check
        $startDate = $request->get('start_date', now()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->addDays(30)->format('Y-m-d'));
        
        // Get bookings for the date range
        $bookings = Booking::whereBetween('check_in_date', [$startDate, $endDate])
            ->orWhereBetween('check_out_date', [$startDate, $endDate])
            ->orWhere(function($query) use ($startDate, $endDate) {
                $query->where('check_in_date', '<=', $startDate)
                      ->where('check_out_date', '>=', $endDate);
            })
            ->whereIn('status', ['pending', 'confirmed', 'checked_in'])
            ->with(['room', 'user'])
            ->get();
        
        return view('admin.availability.index', compact('roomTypes', 'bookings', 'startDate', 'endDate'));
    }
    
    public function calendar(Request $request)
    {
        $roomType = null;
        if ($request->has('room_type_id')) {
            $roomType = RoomType::with('rooms')->findOrFail($request->room_type_id);
        }
        
        $month = $request->get('month', now()->format('Y-m'));
        $startDate = Carbon::parse($month . '-01');
        $endDate = $startDate->copy()->endOfMonth();
        
        // Get bookings for the month
        $bookings = Booking::whereBetween('check_in_date', [$startDate, $endDate])
            ->orWhereBetween('check_out_date', [$startDate, $endDate])
            ->orWhere(function($query) use ($startDate, $endDate) {
                $query->where('check_in_date', '<=', $startDate)
                      ->where('check_out_date', '>=', $endDate);
            })
            ->whereIn('status', ['pending', 'confirmed', 'checked_in'])
            ->when($roomType, function($query) use ($roomType) {
                $query->where('room_type_id', $roomType->id);
            })
            ->with(['room', 'user', 'roomType'])
            ->get();
        
        $roomTypes = RoomType::all();
        
        return view('admin.availability.calendar', compact('roomTypes', 'roomType', 'bookings', 'month', 'startDate', 'endDate'));
    }
    
    public function updateRoomStatus(Request $request, Room $room)
    {
        $request->validate([
            'status' => 'required|in:available,maintenance,cleaning,out_of_order',
        ]);
        
        $room->update([
            'status' => $request->status,
        ]);
        
        return back()->with('success', 'Room status updated successfully.');
    }
    
    public function bulkUpdateStatus(Request $request)
    {
        $request->validate([
            'room_ids' => 'required|array',
            'room_ids.*' => 'exists:rooms,id',
            'status' => 'required|in:available,maintenance,cleaning,out_of_order',
        ]);
        
        Room::whereIn('id', $request->room_ids)->update([
            'status' => $request->status,
        ]);
        
        return back()->with('success', count($request->room_ids) . ' rooms updated successfully.');
    }
} 