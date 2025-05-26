<?php

namespace App\Services;

use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Transaction;
use App\Models\Payment;
use App\Models\Booking;

class MidtransService
{
    public function __construct()
    {
        // Set Midtrans configuration
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');
    }

    /**
     * Create payment transaction
     */
    public function createTransaction(Booking $booking)
    {
        // Create payment record
        $payment = Payment::create([
            'booking_id' => $booking->id,
            'payment_code' => Payment::generatePaymentCode(),
            'midtrans_order_id' => Payment::generateMidtransOrderId(),
            'amount' => $booking->total_amount,
            'status' => 'pending',
            'expired_at' => now()->addHours(24), // 24 hours expiry
        ]);

        // Prepare transaction details
        $transactionDetails = [
            'order_id' => $payment->midtrans_order_id,
            'gross_amount' => (int) $booking->total_amount,
        ];

        // Prepare item details
        $itemDetails = [
            [
                'id' => $booking->room_type_id,
                'price' => (int) $booking->roomType->price_per_night,
                'quantity' => $booking->nights,
                'name' => $booking->roomType->name . ' - ' . $booking->nights . ' nights',
            ]
        ];

        // Prepare customer details
        $customerDetails = [
            'first_name' => $booking->user->name,
            'email' => $booking->user->email,
            'phone' => $booking->user->phone,
            'billing_address' => [
                'address' => $booking->user->address,
            ],
        ];

        // Prepare transaction data
        $transactionData = [
            'transaction_details' => $transactionDetails,
            'item_details' => $itemDetails,
            'customer_details' => $customerDetails,
            'callbacks' => [
                'finish' => url('/payment/finish'),
                'unfinish' => url('/payment/unfinish'),
                'error' => url('/payment/error'),
            ],
            'expiry' => [
                'start_time' => date('Y-m-d H:i:s O'),
                'unit' => 'hours',
                'duration' => 24,
            ],
        ];

        try {
            // Get Snap token
            $snapToken = Snap::getSnapToken($transactionData);
            
            return [
                'payment' => $payment,
                'snap_token' => $snapToken,
                'redirect_url' => "https://app.sandbox.midtrans.com/snap/v2/vtweb/" . $snapToken,
            ];
        } catch (\Exception $e) {
            // Delete payment record if failed
            $payment->delete();
            throw $e;
        }
    }

    /**
     * Handle payment notification from Midtrans
     */
    public function handleNotification($notification)
    {
        $orderId = $notification['order_id'];
        $statusCode = $notification['status_code'];
        $grossAmount = $notification['gross_amount'];
        $signatureKey = $notification['signature_key'];

        // Verify signature
        $serverKey = config('midtrans.server_key');
        $mySignatureKey = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);

        if ($signatureKey !== $mySignatureKey) {
            throw new \Exception('Invalid signature key');
        }

        // Find payment
        $payment = Payment::where('midtrans_order_id', $orderId)->first();
        if (!$payment) {
            throw new \Exception('Payment not found');
        }

        // Update payment status based on transaction status
        $transactionStatus = $notification['transaction_status'];
        $fraudStatus = $notification['fraud_status'] ?? null;

        if ($transactionStatus == 'capture') {
            if ($fraudStatus == 'challenge') {
                $payment->update(['status' => 'pending']);
            } else if ($fraudStatus == 'accept') {
                $payment->markAsPaid(
                    $notification['transaction_id'],
                    $notification['payment_type'],
                    $notification
                );
            }
        } else if ($transactionStatus == 'settlement') {
            $payment->markAsPaid(
                $notification['transaction_id'],
                $notification['payment_type'],
                $notification
            );
        } else if ($transactionStatus == 'pending') {
            $payment->update(['status' => 'pending']);
        } else if (in_array($transactionStatus, ['deny', 'expire', 'cancel'])) {
            if ($transactionStatus == 'expire') {
                $payment->markAsExpired();
            } else {
                $payment->markAsFailed($notification);
            }
        }

        return $payment;
    }

    /**
     * Check transaction status
     */
    public function checkTransactionStatus($orderId)
    {
        try {
            $status = Transaction::status($orderId);
            return $status;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Cancel transaction
     */
    public function cancelTransaction($orderId)
    {
        try {
            $cancel = Transaction::cancel($orderId);
            return $cancel;
        } catch (\Exception $e) {
            throw $e;
        }
    }
} 