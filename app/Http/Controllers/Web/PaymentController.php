<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Payment;
use App\Services\MidtransService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    protected $midtransService;
    
    public function __construct(MidtransService $midtransService)
    {
        $this->midtransService = $midtransService;
    }
    
    public function create(Booking $booking)
    {
        // Check if user owns this booking
        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }
        
        // Check if booking already has a payment
        if ($booking->payment) {
            return redirect()->route('payments.show', $booking->payment);
        }
        
        $booking->load(['roomType', 'room']);
        
        return view('payments.create', compact('booking'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'booking_id' => 'required|exists:bookings,id',
        ]);
        
        $booking = Booking::findOrFail($request->booking_id);
        
        // Check if user owns this booking
        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }
        
        // Check if booking already has a payment
        if ($booking->payment) {
            return redirect()->route('payments.show', $booking->payment);
        }
        
        try {
            // Create Midtrans transaction using the booking
            $midtransResponse = $this->midtransService->createTransaction($booking);
            
            $payment = $midtransResponse['payment'];
            $snapToken = $midtransResponse['snap_token'];
            
            // Prepare response data for the view
            $responseData = [
                'token' => $snapToken,
                'order_id' => $payment->midtrans_order_id,
                'redirect_url' => $midtransResponse['redirect_url'] ?? null,
            ];
            
            return view('payments.process', compact('payment', 'midtransResponse'))->with('midtransResponse', $responseData);
            
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Payment processing failed: ' . $e->getMessage()]);
        }
    }
    
    public function show(Payment $payment)
    {
        // Check if user owns this payment
        if ($payment->booking->user_id !== Auth::id()) {
            abort(403);
        }
        
        $payment->load(['booking.roomType', 'booking.room']);
        
        return view('payments.show', compact('payment'));
    }
    
    public function success(Payment $payment)
    {
        // Check if user owns this payment
        if ($payment->booking->user_id !== Auth::id()) {
            abort(403);
        }
        
        $payment->load(['booking.roomType', 'booking.room']);
        
        return view('payments.success', compact('payment'));
    }
} 