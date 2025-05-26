<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\BookingRequest;
use App\Http\Resources\BookingResource;
use App\Models\Booking;
use App\Models\RoomType;
use App\Models\Room;
use Carbon\Carbon;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="Bookings",
 *     description="API Endpoints for booking management"
 * )
 */
class BookingController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/bookings",
     *     summary="Get user's bookings",
     *     tags={"Bookings"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="status",
     *         in="query",
     *         description="Filter by booking status",
     *         required=false,
     *         @OA\Schema(type="string", enum={"pending","confirmed","checked_in","checked_out","cancelled"})
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Bookings retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Bookings retrieved successfully"),
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Booking"))
     *         )
     *     )
     * )
     */
    public function index(Request $request)
    {
        $query = $request->user()->bookings()
            ->with(['roomType', 'room', 'payment'])
            ->orderBy('created_at', 'desc');

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $bookings = $query->get();

        return response()->json([
            'success' => true,
            'message' => 'Bookings retrieved successfully',
            'data' => BookingResource::collection($bookings),
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/bookings",
     *     summary="Create a new booking",
     *     tags={"Bookings"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"room_type_id","check_in_date","check_out_date","guests"},
     *             @OA\Property(property="room_type_id", type="integer", example=1),
     *             @OA\Property(property="check_in_date", type="string", format="date", example="2023-12-01"),
     *             @OA\Property(property="check_out_date", type="string", format="date", example="2023-12-03"),
     *             @OA\Property(property="guests", type="integer", example=2),
     *             @OA\Property(property="special_requests", type="string", example="Late check-in")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Booking created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Booking created successfully"),
     *             @OA\Property(property="data", ref="#/components/schemas/Booking")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Room not available or validation error"
     *     )
     * )
     */
    public function store(BookingRequest $request)
    {
        $roomType = RoomType::findOrFail($request->room_type_id);
        
        // Check if room type is active
        if (!$roomType->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'Room type is not available',
            ], 400);
        }

        // Check capacity
        if ($request->guests > $roomType->capacity) {
            return response()->json([
                'success' => false,
                'message' => 'Number of guests exceeds room capacity',
            ], 400);
        }

        // Check availability
        if (!$roomType->isAvailable($request->check_in_date, $request->check_out_date)) {
            return response()->json([
                'success' => false,
                'message' => 'Room type is not available for selected dates',
            ], 400);
        }

        // Calculate nights and total amount
        $nights = Booking::calculateNights($request->check_in_date, $request->check_out_date);
        $totalAmount = $nights * $roomType->price_per_night;

        // Create booking
        $booking = Booking::create([
            'booking_code' => Booking::generateBookingCode(),
            'user_id' => $request->user()->id,
            'room_type_id' => $request->room_type_id,
            'check_in_date' => $request->check_in_date,
            'check_out_date' => $request->check_out_date,
            'nights' => $nights,
            'guests' => $request->guests,
            'total_amount' => $totalAmount,
            'status' => 'pending',
            'special_requests' => $request->special_requests,
        ]);

        $booking->load(['roomType', 'user']);

        return response()->json([
            'success' => true,
            'message' => 'Booking created successfully',
            'data' => new BookingResource($booking),
        ], 201);
    }

    /**
     * @OA\Get(
     *     path="/api/bookings/{id}",
     *     summary="Get a specific booking",
     *     tags={"Bookings"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Booking ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Booking retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Booking retrieved successfully"),
     *             @OA\Property(property="data", ref="#/components/schemas/Booking")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Booking not found"
     *     )
     * )
     */
    public function show(Booking $booking)
    {
        // Check if user owns this booking or is admin
        if ($booking->user_id !== auth()->id() && !auth()->user()->isAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized to view this booking',
            ], 403);
        }

        $booking->load(['roomType', 'room', 'user', 'payment']);

        return response()->json([
            'success' => true,
            'message' => 'Booking retrieved successfully',
            'data' => new BookingResource($booking),
        ]);
    }

    /**
     * @OA\Put(
     *     path="/api/bookings/{id}",
     *     summary="Update a booking",
     *     tags={"Bookings"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Booking ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="special_requests", type="string", example="Late check-in")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Booking updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Booking updated successfully"),
     *             @OA\Property(property="data", ref="#/components/schemas/Booking")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Cannot update booking"
     *     )
     * )
     */
    public function update(Request $request, Booking $booking)
    {
        // Check if user owns this booking
        if ($booking->user_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized to update this booking',
            ], 403);
        }

        // Only allow updates for pending bookings
        if ($booking->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Cannot update booking with status: ' . $booking->status,
            ], 400);
        }

        $request->validate([
            'special_requests' => 'nullable|string|max:1000',
        ]);

        $booking->update($request->only('special_requests'));

        return response()->json([
            'success' => true,
            'message' => 'Booking updated successfully',
            'data' => new BookingResource($booking),
        ]);
    }

    /**
     * @OA\Delete(
     *     path="/api/bookings/{id}",
     *     summary="Cancel a booking",
     *     tags={"Bookings"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Booking ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Booking cancelled successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Booking cancelled successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Cannot cancel booking"
     *     )
     * )
     */
    public function destroy(Booking $booking)
    {
        // Check if user owns this booking
        if ($booking->user_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized to cancel this booking',
            ], 403);
        }

        if (!$booking->canBeCancelled()) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot cancel this booking',
            ], 400);
        }

        $booking->cancel();

        return response()->json([
            'success' => true,
            'message' => 'Booking cancelled successfully',
        ]);
    }

    /**
     * Cancel booking (separate endpoint)
     */
    public function cancel(Booking $booking)
    {
        return $this->destroy($booking);
    }

    /**
     * Admin: Get all bookings
     */
    public function adminIndex(Request $request)
    {
        $query = Booking::with(['user', 'roomType', 'room', 'payment'])
            ->orderBy('created_at', 'desc');

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->has('room_type_id')) {
            $query->where('room_type_id', $request->room_type_id);
        }

        $bookings = $query->paginate(20);

        return response()->json([
            'success' => true,
            'message' => 'Bookings retrieved successfully',
            'data' => BookingResource::collection($bookings->items()),
            'meta' => [
                'current_page' => $bookings->currentPage(),
                'last_page' => $bookings->lastPage(),
                'per_page' => $bookings->perPage(),
                'total' => $bookings->total(),
            ],
        ]);
    }

    /**
     * Admin: Confirm booking
     */
    public function confirm(Booking $booking)
    {
        if ($booking->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Can only confirm pending bookings',
            ], 400);
        }

        // Assign available room
        $availableRoom = Room::where('room_type_id', $booking->room_type_id)
            ->where('status', 'available')
            ->whereDoesntHave('bookings', function ($query) use ($booking) {
                $query->where(function ($q) use ($booking) {
                    $q->where('check_in_date', '<=', $booking->check_in_date)
                      ->where('check_out_date', '>', $booking->check_in_date);
                })->orWhere(function ($q) use ($booking) {
                    $q->where('check_in_date', '<', $booking->check_out_date)
                      ->where('check_out_date', '>=', $booking->check_out_date);
                })->orWhere(function ($q) use ($booking) {
                    $q->where('check_in_date', '>=', $booking->check_in_date)
                      ->where('check_out_date', '<=', $booking->check_out_date);
                })->whereIn('status', ['confirmed', 'checked_in']);
            })
            ->first();

        if (!$availableRoom) {
            return response()->json([
                'success' => false,
                'message' => 'No available rooms for this booking',
            ], 400);
        }

        $booking->update(['room_id' => $availableRoom->id]);
        $booking->confirm();

        return response()->json([
            'success' => true,
            'message' => 'Booking confirmed successfully',
            'data' => new BookingResource($booking->load(['roomType', 'room', 'user', 'payment'])),
        ]);
    }

    /**
     * Admin: Check in booking
     */
    public function checkIn(Booking $booking)
    {
        if (!$booking->checkIn()) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot check in this booking',
            ], 400);
        }

        return response()->json([
            'success' => true,
            'message' => 'Booking checked in successfully',
            'data' => new BookingResource($booking->load(['roomType', 'room', 'user', 'payment'])),
        ]);
    }

    /**
     * Admin: Check out booking
     */
    public function checkOut(Booking $booking)
    {
        if (!$booking->checkOut()) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot check out this booking',
            ], 400);
        }

        return response()->json([
            'success' => true,
            'message' => 'Booking checked out successfully',
            'data' => new BookingResource($booking->load(['roomType', 'room', 'user', 'payment'])),
        ]);
    }

    /**
     * Admin: Cancel booking
     */
    public function adminCancel(Booking $booking)
    {
        if (!$booking->cancel()) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot cancel this booking',
            ], 400);
        }

        return response()->json([
            'success' => true,
            'message' => 'Booking cancelled successfully',
        ]);
    }
}
