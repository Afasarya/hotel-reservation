<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\RoomType;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Get available room types
        $roomTypes = RoomType::where('is_active', true)
            ->withCount('rooms')
            ->take(6)
            ->get();
        
        // Get user's recent bookings
        $recentBookings = Booking::where('user_id', $user->id)
            ->with(['roomType', 'payment'])
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();
        
        return view('dashboard', compact('roomTypes', 'recentBookings'));
    }
} 