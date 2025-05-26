<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'payment_code',
        'midtrans_order_id',
        'midtrans_transaction_id',
        'amount',
        'status',
        'payment_method',
        'midtrans_response',
        'paid_at',
        'expired_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'midtrans_response' => 'array',
        'paid_at' => 'datetime',
        'expired_at' => 'datetime',
    ];

    /**
     * Generate unique payment code
     */
    public static function generatePaymentCode()
    {
        do {
            $code = 'PAY' . date('Ymd') . strtoupper(substr(uniqid(), -6));
        } while (self::where('payment_code', $code)->exists());

        return $code;
    }

    /**
     * Generate unique Midtrans order ID
     */
    public static function generateMidtransOrderId()
    {
        do {
            $orderId = 'ORDER-' . date('YmdHis') . '-' . strtoupper(substr(uniqid(), -4));
        } while (self::where('midtrans_order_id', $orderId)->exists());

        return $orderId;
    }

    /**
     * Get the booking
     */
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    /**
     * Mark payment as paid
     */
    public function markAsPaid($transactionId = null, $paymentMethod = null, $midtransResponse = null)
    {
        $this->update([
            'status' => 'paid',
            'midtrans_transaction_id' => $transactionId,
            'payment_method' => $paymentMethod,
            'midtrans_response' => $midtransResponse,
            'paid_at' => now(),
        ]);

        // Confirm the booking
        $this->booking->confirm();
    }

    /**
     * Mark payment as failed
     */
    public function markAsFailed($midtransResponse = null)
    {
        $this->update([
            'status' => 'failed',
            'midtrans_response' => $midtransResponse,
        ]);
    }

    /**
     * Mark payment as expired
     */
    public function markAsExpired()
    {
        $this->update([
            'status' => 'expired',
        ]);

        // Cancel the booking
        $this->booking->cancel();
    }

    /**
     * Check if payment is pending
     */
    public function isPending()
    {
        return $this->status === 'pending';
    }

    /**
     * Check if payment is paid
     */
    public function isPaid()
    {
        return $this->status === 'paid';
    }

    /**
     * Check if payment is failed
     */
    public function isFailed()
    {
        return $this->status === 'failed';
    }

    /**
     * Check if payment is expired
     */
    public function isExpired()
    {
        return $this->status === 'expired' || ($this->expired_at && $this->expired_at < now());
    }
}
