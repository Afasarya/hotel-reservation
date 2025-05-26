<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\Request;

class AdminNotificationController extends Controller
{
    public function index()
    {
        // Get recent notifications
        $notifications = collect();
        
        // New bookings (last 24 hours)
        $newBookings = Booking::where('created_at', '>=', now()->subDay())
            ->with(['user', 'roomType'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function($booking) {
                return [
                    'type' => 'new_booking',
                    'title' => 'New Booking',
                    'message' => "New booking from {$booking->user->name} for {$booking->roomType->name}",
                    'data' => $booking,
                    'created_at' => $booking->created_at,
                    'url' => route('admin.bookings.show', $booking),
                ];
            });
        
        // Pending payments (last 24 hours)
        $pendingPayments = Payment::where('status', 'pending')
            ->where('created_at', '>=', now()->subDay())
            ->with(['booking.user', 'booking.roomType'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function($payment) {
                return [
                    'type' => 'pending_payment',
                    'title' => 'Pending Payment',
                    'message' => "Payment pending for booking {$payment->booking->booking_code}",
                    'data' => $payment,
                    'created_at' => $payment->created_at,
                    'url' => route('admin.payments.show', $payment),
                ];
            });
        
        // Expired payments
        $expiredPayments = Payment::where('status', 'pending')
            ->where('expired_at', '<', now())
            ->with(['booking.user', 'booking.roomType'])
            ->orderBy('expired_at', 'desc')
            ->get()
            ->map(function($payment) {
                return [
                    'type' => 'expired_payment',
                    'title' => 'Expired Payment',
                    'message' => "Payment expired for booking {$payment->booking->booking_code}",
                    'data' => $payment,
                    'created_at' => $payment->expired_at,
                    'url' => route('admin.payments.show', $payment),
                ];
            });
        
        // New users (last 7 days)
        $newUsers = User::where('role', 'user')
            ->where('created_at', '>=', now()->subWeek())
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function($user) {
                return [
                    'type' => 'new_user',
                    'title' => 'New User Registration',
                    'message' => "New user registered: {$user->name}",
                    'data' => $user,
                    'created_at' => $user->created_at,
                    'url' => route('admin.users.show', $user),
                ];
            });
        
        // Merge and sort all notifications
        $notifications = $newBookings
            ->concat($pendingPayments)
            ->concat($expiredPayments)
            ->concat($newUsers)
            ->sortByDesc('created_at')
            ->take(50);
        
        return view('admin.notifications.index', compact('notifications'));
    }
    
    public function markAsRead(Request $request)
    {
        // This would typically update a notifications table
        // For now, we'll just return success
        return response()->json(['success' => true]);
    }
} 