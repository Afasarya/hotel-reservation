<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\RoomTypeResource;
use App\Models\RoomType;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="Room Types",
 *     description="API Endpoints for room types management"
 * )
 */
class RoomTypeController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/room-types",
     *     summary="Get all room types",
     *     tags={"Room Types"},
     *     @OA\Parameter(
     *         name="check_in_date",
     *         in="query",
     *         description="Check-in date to check availability",
     *         required=false,
     *         @OA\Schema(type="string", format="date", example="2023-12-01")
     *     ),
     *     @OA\Parameter(
     *         name="check_out_date",
     *         in="query",
     *         description="Check-out date to check availability",
     *         required=false,
     *         @OA\Schema(type="string", format="date", example="2023-12-03")
     *     ),
     *     @OA\Parameter(
     *         name="guests",
     *         in="query",
     *         description="Number of guests",
     *         required=false,
     *         @OA\Schema(type="integer", example=2)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Room types retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Room types retrieved successfully"),
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/RoomType"))
     *         )
     *     )
     * )
     */
    public function index(Request $request)
    {
        $query = RoomType::where('is_active', true);

        // Filter by capacity if guests parameter is provided
        if ($request->has('guests')) {
            $query->where('capacity', '>=', $request->guests);
        }

        $roomTypes = $query->get();

        return response()->json([
            'success' => true,
            'message' => 'Room types retrieved successfully',
            'data' => RoomTypeResource::collection($roomTypes),
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/admin/room-types",
     *     summary="Create a new room type (Admin only)",
     *     tags={"Room Types"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name","description","price_per_night","capacity","total_rooms"},
     *             @OA\Property(property="name", type="string", example="Standard Room"),
     *             @OA\Property(property="description", type="string", example="Comfortable standard room"),
     *             @OA\Property(property="price_per_night", type="number", format="float", example=500000),
     *             @OA\Property(property="capacity", type="integer", example=2),
     *             @OA\Property(property="total_rooms", type="integer", example=20),
     *             @OA\Property(property="facilities", type="array", @OA\Items(type="string"), example={"AC", "TV", "WiFi"}),
     *             @OA\Property(property="images", type="array", @OA\Items(type="string"), example={"/images/room1.jpg"}),
     *             @OA\Property(property="is_active", type="boolean", example=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Room type created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Room type created successfully"),
     *             @OA\Property(property="data", ref="#/components/schemas/RoomType")
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Unauthorized - Admin access required"
     *     )
     * )
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:room_types,name',
            'description' => 'required|string',
            'price_per_night' => 'required|numeric|min:0',
            'capacity' => 'required|integer|min:1',
            'total_rooms' => 'required|integer|min:1',
            'facilities' => 'nullable|array',
            'facilities.*' => 'string',
            'images' => 'nullable|array',
            'images.*' => 'string',
            'is_active' => 'boolean',
        ]);

        $roomType = RoomType::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Room type created successfully',
            'data' => new RoomTypeResource($roomType),
        ], 201);
    }

    /**
     * @OA\Get(
     *     path="/api/room-types/{id}",
     *     summary="Get a specific room type",
     *     tags={"Room Types"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Room type ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="check_in_date",
     *         in="query",
     *         description="Check-in date to check availability",
     *         required=false,
     *         @OA\Schema(type="string", format="date", example="2023-12-01")
     *     ),
     *     @OA\Parameter(
     *         name="check_out_date",
     *         in="query",
     *         description="Check-out date to check availability",
     *         required=false,
     *         @OA\Schema(type="string", format="date", example="2023-12-03")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Room type retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Room type retrieved successfully"),
     *             @OA\Property(property="data", ref="#/components/schemas/RoomType")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Room type not found"
     *     )
     * )
     */
    public function show(RoomType $roomType)
    {
        return response()->json([
            'success' => true,
            'message' => 'Room type retrieved successfully',
            'data' => new RoomTypeResource($roomType),
        ]);
    }

    /**
     * @OA\Put(
     *     path="/api/admin/room-types/{id}",
     *     summary="Update a room type (Admin only)",
     *     tags={"Room Types"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Room type ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="Standard Room"),
     *             @OA\Property(property="description", type="string", example="Comfortable standard room"),
     *             @OA\Property(property="price_per_night", type="number", format="float", example=500000),
     *             @OA\Property(property="capacity", type="integer", example=2),
     *             @OA\Property(property="total_rooms", type="integer", example=20),
     *             @OA\Property(property="facilities", type="array", @OA\Items(type="string"), example={"AC", "TV", "WiFi"}),
     *             @OA\Property(property="images", type="array", @OA\Items(type="string"), example={"/images/room1.jpg"}),
     *             @OA\Property(property="is_active", type="boolean", example=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Room type updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Room type updated successfully"),
     *             @OA\Property(property="data", ref="#/components/schemas/RoomType")
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Unauthorized - Admin access required"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Room type not found"
     *     )
     * )
     */
    public function update(Request $request, RoomType $roomType)
    {
        $request->validate([
            'name' => 'sometimes|string|max:255|unique:room_types,name,' . $roomType->id,
            'description' => 'sometimes|string',
            'price_per_night' => 'sometimes|numeric|min:0',
            'capacity' => 'sometimes|integer|min:1',
            'total_rooms' => 'sometimes|integer|min:1',
            'facilities' => 'nullable|array',
            'facilities.*' => 'string',
            'images' => 'nullable|array',
            'images.*' => 'string',
            'is_active' => 'boolean',
        ]);

        $roomType->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Room type updated successfully',
            'data' => new RoomTypeResource($roomType),
        ]);
    }

    /**
     * @OA\Delete(
     *     path="/api/admin/room-types/{id}",
     *     summary="Delete a room type (Admin only)",
     *     tags={"Room Types"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Room type ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Room type deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Room type deleted successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Unauthorized - Admin access required"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Room type not found"
     *     )
     * )
     */
    public function destroy(RoomType $roomType)
    {
        // Check if there are any active bookings for this room type
        $activeBookings = $roomType->bookings()
            ->whereIn('status', ['pending', 'confirmed', 'checked_in'])
            ->exists();

        if ($activeBookings) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete room type with active bookings',
            ], 400);
        }

        $roomType->delete();

        return response()->json([
            'success' => true,
            'message' => 'Room type deleted successfully',
        ]);
    }
}
