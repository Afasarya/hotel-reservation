<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Booking;
use App\Services\MidtransService;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="Payments",
 *     description="API Endpoints for payment processing with Midtrans"
 * )
 */
class PaymentController extends Controller
{
    protected $midtransService;

    public function __construct(MidtransService $midtransService)
    {
        $this->midtransService = $midtransService;
    }

    /**
     * @OA\Post(
     *     path="/api/payments/create",
     *     summary="Create payment for booking",
     *     tags={"Payments"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"booking_id"},
     *             @OA\Property(property="booking_id", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Payment created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Payment created successfully"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="payment_id", type="integer", example=1),
     *                 @OA\Property(property="snap_token", type="string", example="abc123..."),
     *                 @OA\Property(property="redirect_url", type="string", example="https://app.sandbox.midtrans.com/snap/v2/vtweb/abc123...")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid booking or payment already exists"
     *     )
     * )
     */
    public function create(Request $request)
    {
        $request->validate([
            'booking_id' => 'required|exists:bookings,id',
        ]);

        $booking = Booking::with(['user', 'roomType'])->findOrFail($request->booking_id);

        // Check if user owns this booking
        if ($booking->user_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized to create payment for this booking',
            ], 403);
        }

        // Check if booking is pending
        if ($booking->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Can only create payment for pending bookings',
            ], 400);
        }

        // Check if payment already exists
        if ($booking->payment) {
            return response()->json([
                'success' => false,
                'message' => 'Payment already exists for this booking',
            ], 400);
        }

        try {
            $paymentData = $this->midtransService->createTransaction($booking);

            return response()->json([
                'success' => true,
                'message' => 'Payment created successfully',
                'data' => [
                    'payment_id' => $paymentData['payment']->id,
                    'snap_token' => $paymentData['snap_token'],
                    'redirect_url' => $paymentData['redirect_url'],
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create payment: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/payments/{id}",
     *     summary="Get payment details",
     *     tags={"Payments"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Payment ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Payment retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Payment retrieved successfully"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="payment_code", type="string", example="PAY20231201XYZ789"),
     *                 @OA\Property(property="amount", type="number", format="float", example=1000000),
     *                 @OA\Property(property="status", type="string", example="paid"),
     *                 @OA\Property(property="payment_method", type="string", example="credit_card"),
     *                 @OA\Property(property="paid_at", type="string", format="date-time", example="2023-01-01 12:00:00")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Payment not found"
     *     )
     * )
     */
    public function show(Payment $payment)
    {
        // Check if user owns this payment or is admin
        if ($payment->booking->user_id !== auth()->id() && !auth()->user()->isAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized to view this payment',
            ], 403);
        }

        return response()->json([
            'success' => true,
            'message' => 'Payment retrieved successfully',
            'data' => [
                'id' => $payment->id,
                'payment_code' => $payment->payment_code,
                'amount' => (float) $payment->amount,
                'status' => $payment->status,
                'payment_method' => $payment->payment_method,
                'paid_at' => $payment->paid_at?->format('Y-m-d H:i:s'),
                'expired_at' => $payment->expired_at?->format('Y-m-d H:i:s'),
                'booking' => [
                    'id' => $payment->booking->id,
                    'booking_code' => $payment->booking->booking_code,
                    'check_in_date' => $payment->booking->check_in_date->format('Y-m-d'),
                    'check_out_date' => $payment->booking->check_out_date->format('Y-m-d'),
                    'nights' => $payment->booking->nights,
                    'guests' => $payment->booking->guests,
                ],
            ],
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/payments/{id}/status",
     *     summary="Check payment status",
     *     tags={"Payments"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Payment ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Payment status retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Payment status retrieved successfully"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="status", type="string", example="paid"),
     *                 @OA\Property(property="midtrans_status", type="object")
     *             )
     *         )
     *     )
     * )
     */
    public function status(Payment $payment)
    {
        // Check if user owns this payment or is admin
        if ($payment->booking->user_id !== auth()->id() && !auth()->user()->isAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized to check this payment status',
            ], 403);
        }

        try {
            $midtransStatus = $this->midtransService->checkTransactionStatus($payment->midtrans_order_id);

            return response()->json([
                'success' => true,
                'message' => 'Payment status retrieved successfully',
                'data' => [
                    'status' => $payment->status,
                    'midtrans_status' => $midtransStatus,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to check payment status: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/payment/webhook",
     *     summary="Midtrans payment webhook",
     *     tags={"Payments"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="order_id", type="string", example="ORDER-20231201123456-ABCD"),
     *             @OA\Property(property="status_code", type="string", example="200"),
     *             @OA\Property(property="gross_amount", type="string", example="1000000.00"),
     *             @OA\Property(property="signature_key", type="string", example="abc123..."),
     *             @OA\Property(property="transaction_status", type="string", example="settlement"),
     *             @OA\Property(property="transaction_id", type="string", example="xyz789..."),
     *             @OA\Property(property="payment_type", type="string", example="credit_card")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Webhook processed successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Webhook processed successfully")
     *         )
     *     )
     * )
     */
    public function webhook(Request $request)
    {
        try {
            $notification = $request->all();
            $payment = $this->midtransService->handleNotification($notification);

            return response()->json([
                'success' => true,
                'message' => 'Webhook processed successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Webhook processing failed: ' . $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Admin: Get all payments
     */
    public function adminIndex(Request $request)
    {
        $query = Payment::with(['booking.user', 'booking.roomType'])
            ->orderBy('created_at', 'desc');

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $payments = $query->paginate(20);

        return response()->json([
            'success' => true,
            'message' => 'Payments retrieved successfully',
            'data' => $payments->items(),
            'meta' => [
                'current_page' => $payments->currentPage(),
                'last_page' => $payments->lastPage(),
                'per_page' => $payments->perPage(),
                'total' => $payments->total(),
            ],
        ]);
    }

    /**
     * Admin: Get payment receipt
     */
    public function receipt(Payment $payment)
    {
        return response()->json([
            'success' => true,
            'message' => 'Payment receipt retrieved successfully',
            'data' => [
                'payment' => [
                    'id' => $payment->id,
                    'payment_code' => $payment->payment_code,
                    'amount' => (float) $payment->amount,
                    'status' => $payment->status,
                    'payment_method' => $payment->payment_method,
                    'paid_at' => $payment->paid_at?->format('Y-m-d H:i:s'),
                    'midtrans_transaction_id' => $payment->midtrans_transaction_id,
                ],
                'booking' => [
                    'id' => $payment->booking->id,
                    'booking_code' => $payment->booking->booking_code,
                    'check_in_date' => $payment->booking->check_in_date->format('Y-m-d'),
                    'check_out_date' => $payment->booking->check_out_date->format('Y-m-d'),
                    'nights' => $payment->booking->nights,
                    'guests' => $payment->booking->guests,
                    'total_amount' => (float) $payment->booking->total_amount,
                ],
                'user' => [
                    'name' => $payment->booking->user->name,
                    'email' => $payment->booking->user->email,
                    'phone' => $payment->booking->user->phone,
                ],
                'room_type' => [
                    'name' => $payment->booking->roomType->name,
                    'price_per_night' => (float) $payment->booking->roomType->price_per_night,
                ],
            ],
        ]);
    }
}
