<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\RoomType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminRoomTypeController extends Controller
{
    public function index()
    {
        $roomTypes = RoomType::withCount(['rooms', 'bookings'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        return view('admin.room-types.index', compact('roomTypes'));
    }
    
    public function create()
    {
        return view('admin.room-types.create');
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price_per_night' => 'required|numeric|min:0',
            'capacity' => 'required|integer|min:1',
            'total_rooms' => 'required|integer|min:1',
            'size' => 'nullable|numeric|min:0',
            'facilities' => 'nullable|array',
            'facilities.*' => 'string',
            'main_image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'additional_images' => 'nullable|array|max:5',
            'additional_images.*' => 'image|mimes:jpeg,png,jpg|max:2048',
        ]);
        
        $images = [];
        
        // Handle main image
        if ($request->hasFile('main_image')) {
            $mainImagePath = $request->file('main_image')->store('room-types', 'public');
            $images[] = $mainImagePath;
        }
        
        // Handle additional images
        if ($request->hasFile('additional_images')) {
            foreach ($request->file('additional_images') as $image) {
                $path = $image->store('room-types', 'public');
                $images[] = $path;
            }
        }
        
        RoomType::create([
            'name' => $request->name,
            'description' => $request->description,
            'price_per_night' => $request->price_per_night,
            'capacity' => $request->capacity,
            'total_rooms' => $request->total_rooms,
            'facilities' => $request->facilities ?? [],
            'images' => $images,
            'is_active' => true,
        ]);
        
        return redirect()->route('admin.room-types.index')
            ->with('success', 'Room type created successfully.');
    }
    
    public function show(RoomType $roomType)
    {
        $roomType->load(['rooms', 'bookings.user']);
        
        return view('admin.room-types.show', compact('roomType'));
    }
    
    public function edit(RoomType $roomType)
    {
        return view('admin.room-types.edit', compact('roomType'));
    }
    
    public function update(Request $request, RoomType $roomType)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price_per_night' => 'required|numeric|min:0',
            'capacity' => 'required|integer|min:1',
            'total_rooms' => 'required|integer|min:1',
            'facilities' => 'required|array',
            'facilities.*' => 'string',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'boolean',
        ]);
        
        $images = $roomType->images ?? [];
        
        if ($request->hasFile('images')) {
            // Delete old images
            foreach ($images as $image) {
                Storage::disk('public')->delete($image);
            }
            
            // Upload new images
            $images = [];
            foreach ($request->file('images') as $image) {
                $path = $image->store('room-types', 'public');
                $images[] = $path;
            }
        }
        
        $roomType->update([
            'name' => $request->name,
            'description' => $request->description,
            'price_per_night' => $request->price_per_night,
            'capacity' => $request->capacity,
            'total_rooms' => $request->total_rooms,
            'facilities' => $request->facilities,
            'images' => $images,
            'is_active' => $request->has('is_active'),
        ]);
        
        return redirect()->route('admin.room-types.index')
            ->with('success', 'Room type updated successfully.');
    }
    
    public function destroy(RoomType $roomType)
    {
        // Check if room type has active bookings
        $activeBookings = $roomType->bookings()
            ->whereIn('status', ['pending', 'confirmed', 'checked_in'])
            ->count();
        
        if ($activeBookings > 0) {
            return back()->withErrors(['error' => 'Cannot delete room type with active bookings.']);
        }
        
        // Delete images
        if ($roomType->images) {
            foreach ($roomType->images as $image) {
                Storage::disk('public')->delete($image);
            }
        }
        
        $roomType->delete();
        
        return redirect()->route('admin.room-types.index')
            ->with('success', 'Room type deleted successfully.');
    }
} 