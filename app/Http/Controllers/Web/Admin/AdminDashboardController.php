<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\RoomType;
use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // Basic statistics
        $totalUsers = User::where('role', 'user')->count();
        $totalRoomTypes = RoomType::count();
        $totalBookings = Booking::count();
        $totalRevenue = Payment::where('status', 'paid')->sum('amount');
        
        // Recent bookings
        $recentBookings = Booking::with(['user', 'roomType', 'payment'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
        
        // Monthly revenue chart data
        $monthlyRevenue = Payment::where('status', 'paid')
            ->where('paid_at', '>=', now()->subMonths(12))
            ->selectRaw('MONTH(paid_at) as month, YEAR(paid_at) as year, SUM(amount) as total')
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->take(12)
            ->get();
        
        // Booking status distribution
        $bookingStats = Booking::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->get()
            ->pluck('count', 'status');
        
        // Popular room types
        $popularRoomTypes = RoomType::withCount('bookings')
            ->orderBy('bookings_count', 'desc')
            ->take(5)
            ->get();
        
        return view('admin.dashboard', compact(
            'totalUsers',
            'totalRoomTypes', 
            'totalBookings',
            'totalRevenue',
            'recentBookings',
            'monthlyRevenue',
            'bookingStats',
            'popularRoomTypes'
        ));
    }
} 