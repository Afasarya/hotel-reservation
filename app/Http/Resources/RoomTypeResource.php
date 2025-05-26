<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="RoomType",
 *     type="object",
 *     title="Room Type",
 *     description="Room Type model",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="Standard Room"),
 *     @OA\Property(property="description", type="string", example="Comfortable standard room with basic amenities"),
 *     @OA\Property(property="price_per_night", type="number", format="float", example=500000),
 *     @OA\Property(property="capacity", type="integer", example=2),
 *     @OA\Property(property="total_rooms", type="integer", example=20),
 *     @OA\Property(property="available_rooms", type="integer", example=15),
 *     @OA\Property(property="facilities", type="array", @OA\Items(type="string"), example={"AC", "TV", "WiFi"}),
 *     @OA\Property(property="images", type="array", @OA\Items(type="string"), example={"/images/room1.jpg", "/images/room2.jpg"}),
 *     @OA\Property(property="is_active", type="boolean", example=true),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2023-01-01T00:00:00.000000Z"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2023-01-01T00:00:00.000000Z")
 * )
 */
class RoomTypeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $checkIn = $request->query('check_in_date');
        $checkOut = $request->query('check_out_date');
        
        $availableRooms = null;
        if ($checkIn && $checkOut) {
            $availableRooms = $this->getAvailableRoomsCount($checkIn, $checkOut);
        }

        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'price_per_night' => (float) $this->price_per_night,
            'capacity' => $this->capacity,
            'total_rooms' => $this->total_rooms,
            'available_rooms' => $availableRooms,
            'facilities' => $this->facilities ?? [],
            'images' => $this->images ?? [],
            'is_active' => $this->is_active,
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
        ];
    }
}
