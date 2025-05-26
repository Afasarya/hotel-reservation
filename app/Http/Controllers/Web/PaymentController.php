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
            // Create payment record
            $payment = Payment::create([
                'booking_id' => $booking->id,
                'payment_code' => 'PAY' . strtoupper(uniqid()),
                'amount' => $booking->total_amount,
                'status' => 'pending',
                'expired_at' => now()->addHours(24),
            ]);
            
            // Create Midtrans transaction
            $midtransResponse = $this->midtransService->createTransaction($payment);
            
            if ($midtransResponse) {
                $payment->update([
                    'midtrans_order_id' => $midtransResponse['order_id'],
                    'midtrans_response' => $midtransResponse,
                ]);
                
                return view('payments.process', compact('payment', 'midtransResponse'));
            }
            
            return back()->withErrors(['error' => 'Failed to create payment. Please try again.']);
            
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Payment processing failed. Please try again.']);
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