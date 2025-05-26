<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;

class AdminPaymentController extends Controller
{
    public function index(Request $request)
    {
        $query = Payment::with(['booking.user', 'booking.roomType']);
        
        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }
        
        // Filter by date range
        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        
        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        
        // Search by payment code or booking code
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('payment_code', 'like', "%{$search}%")
                  ->orWhereHas('booking', function($bookingQuery) use ($search) {
                      $bookingQuery->where('booking_code', 'like', "%{$search}%");
                  });
            });
        }
        
        $payments = $query->orderBy('created_at', 'desc')->paginate(15);
        
        return view('admin.payments.index', compact('payments'));
    }
    
    public function show(Payment $payment)
    {
        $payment->load(['booking.user', 'booking.roomType', 'booking.room']);
        
        return view('admin.payments.show', compact('payment'));
    }
    
    public function receipt(Payment $payment)
    {
        $payment->load(['booking.user', 'booking.roomType', 'booking.room']);
        
        return view('admin.payments.receipt', compact('payment'));
    }
} 