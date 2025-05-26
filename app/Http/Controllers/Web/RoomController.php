<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\RoomType;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function index(Request $request)
    {
        $query = RoomType::where('is_active', true)
            ->withCount('rooms');
        
        // Filter by capacity if provided
        if ($request->has('capacity') && $request->capacity) {
            $query->where('capacity', '>=', $request->capacity);
        }
        
        // Filter by price range if provided
        if ($request->has('min_price') && $request->min_price) {
            $query->where('price_per_night', '>=', $request->min_price);
        }
        
        if ($request->has('max_price') && $request->max_price) {
            $query->where('price_per_night', '<=', $request->max_price);
        }
        
        // Search by name if provided
        if ($request->has('search') && $request->search) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        
        $roomTypes = $query->paginate(9);
        
        return view('rooms.index', compact('roomTypes'));
    }
    
    public function show(RoomType $roomType)
    {
        $roomType->load(['rooms' => function($query) {
            $query->where('status', 'available');
        }]);
        
        // Get similar room types
        $similarRooms = RoomType::where('is_active', true)
            ->where('id', '!=', $roomType->id)
            ->where('price_per_night', '>=', $roomType->price_per_night * 0.8)
            ->where('price_per_night', '<=', $roomType->price_per_night * 1.2)
            ->take(3)
            ->get();
        
        return view('rooms.show', compact('roomType', 'similarRooms'));
    }
} 