<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="Booking",
 *     type="object",
 *     title="Booking",
 *     description="Booking model",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="booking_code", type="string", example="BK20231201ABC123"),
 *     @OA\Property(property="user", ref="#/components/schemas/User"),
 *     @OA\Property(property="room_type", ref="#/components/schemas/RoomType"),
 *     @OA\Property(property="room", type="object",
 *         @OA\Property(property="id", type="integer", example=1),
 *         @OA\Property(property="room_number", type="string", example="STD101"),
 *         @OA\Property(property="status", type="string", example="available")
 *     ),
 *     @OA\Property(property="check_in_date", type="string", format="date", example="2023-12-01"),
 *     @OA\Property(property="check_out_date", type="string", format="date", example="2023-12-03"),
 *     @OA\Property(property="nights", type="integer", example=2),
 *     @OA\Property(property="guests", type="integer", example=2),
 *     @OA\Property(property="total_amount", type="number", format="float", example=1000000),
 *     @OA\Property(property="status", type="string", enum={"pending","confirmed","checked_in","checked_out","cancelled"}, example="confirmed"),
 *     @OA\Property(property="special_requests", type="string", example="Late check-in"),
 *     @OA\Property(property="payment", type="object",
 *         @OA\Property(property="id", type="integer", example=1),
 *         @OA\Property(property="payment_code", type="string", example="PAY20231201XYZ789"),
 *         @OA\Property(property="status", type="string", example="paid"),
 *         @OA\Property(property="amount", type="number", format="float", example=1000000)
 *     ),
 *     @OA\Property(property="confirmed_at", type="string", format="date-time", example="2023-01-01T00:00:00.000000Z"),
 *     @OA\Property(property="cancelled_at", type="string", format="date-time", example=null),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2023-01-01T00:00:00.000000Z"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2023-01-01T00:00:00.000000Z")
 * )
 */
class BookingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'booking_code' => $this->booking_code,
            'user' => new UserResource($this->whenLoaded('user')),
            'room_type' => new RoomTypeResource($this->whenLoaded('roomType')),
            'room' => $this->when($this->room, [
                'id' => $this->room?->id,
                'room_number' => $this->room?->room_number,
                'status' => $this->room?->status,
            ]),
            'check_in_date' => $this->check_in_date?->format('Y-m-d'),
            'check_out_date' => $this->check_out_date?->format('Y-m-d'),
            'nights' => $this->nights,
            'guests' => $this->guests,
            'total_amount' => (float) $this->total_amount,
            'status' => $this->status,
            'special_requests' => $this->special_requests,
            'payment' => $this->when($this->payment, [
                'id' => $this->payment?->id,
                'payment_code' => $this->payment?->payment_code,
                'status' => $this->payment?->status,
                'amount' => (float) $this->payment?->amount,
                'payment_method' => $this->payment?->payment_method,
                'paid_at' => $this->payment?->paid_at?->format('Y-m-d H:i:s'),
            ]),
            'confirmed_at' => $this->confirmed_at?->format('Y-m-d H:i:s'),
            'cancelled_at' => $this->cancelled_at?->format('Y-m-d H:i:s'),
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
        ];
    }
}
