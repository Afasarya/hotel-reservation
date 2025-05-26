<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-secondary-800 leading-tight">
                Create Room Type
            </h2>
            <a href="{{ route('admin.room-types.index') }}" 
               class="inline-flex items-center px-4 py-2 text-secondary-600 font-medium rounded-lg border border-secondary-300 hover:bg-secondary-50 transition duration-300">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Room Types
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('admin.room-types.store') }}" enctype="multipart/form-data" class="space-y-8">
                @csrf
                
                <!-- Basic Information -->
                <div class="bg-white rounded-xl shadow-sm border border-primary-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-primary-100 bg-primary-50">
                        <h3 class="text-lg font-semibold text-secondary-900">Basic Information</h3>
                    </div>
                    
                    <div class="p-6 space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Room Type Name -->
                            <div>
                                <label for="name" class="block text-sm font-medium text-secondary-700 mb-2">Room Type Name *</label>
                                <input type="text" 
                                       name="name" 
                                       id="name"
                                       value="{{ old('name') }}"
                                       required
                                       class="w-full rounded-lg border-secondary-300 focus:border-primary-500 focus:ring-primary-500"
                                       placeholder="e.g., Standard Room, Deluxe Suite">
                                @error('name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Price per Night -->
                            <div>
                                <label for="price_per_night" class="block text-sm font-medium text-secondary-700 mb-2">Price per Night (Rp) *</label>
                                <input type="number" 
                                       name="price_per_night" 
                                       id="price_per_night"
                                       value="{{ old('price_per_night') }}"
                                       required
                                       min="0"
                                       step="1000"
                                       class="w-full rounded-lg border-secondary-300 focus:border-primary-500 focus:ring-primary-500"
                                       placeholder="500000">
                                @error('price_per_night')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <!-- Capacity -->
                            <div>
                                <label for="capacity" class="block text-sm font-medium text-secondary-700 mb-2">Capacity (guests) *</label>
                                <input type="number" 
                                       name="capacity" 
                                       id="capacity"
                                       value="{{ old('capacity') }}"
                                       required
                                       min="1"
                                       max="10"
                                       class="w-full rounded-lg border-secondary-300 focus:border-primary-500 focus:ring-primary-500"
                                       placeholder="2">
                                @error('capacity')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Size -->
                            <div>
                                <label for="size" class="block text-sm font-medium text-secondary-700 mb-2">Size (m²)</label>
                                <input type="number" 
                                       name="size" 
                                       id="size"
                                       value="{{ old('size') }}"
                                       min="0"
                                       class="w-full rounded-lg border-secondary-300 focus:border-primary-500 focus:ring-primary-500"
                                       placeholder="25">
                                @error('size')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Number of Rooms -->
                            <div>
                                <label for="total_rooms" class="block text-sm font-medium text-secondary-700 mb-2">Number of Rooms *</label>
                                <input type="number" 
                                       name="total_rooms" 
                                       id="total_rooms"
                                       value="{{ old('total_rooms') }}"
                                       required
                                       min="1"
                                       class="w-full rounded-lg border-secondary-300 focus:border-primary-500 focus:ring-primary-500"
                                       placeholder="10">
                                @error('total_rooms')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Description -->
                        <div>
                            <label for="description" class="block text-sm font-medium text-secondary-700 mb-2">Description</label>
                            <textarea name="description" 
                                      id="description"
                                      rows="4"
                                      class="w-full rounded-lg border-secondary-300 focus:border-primary-500 focus:ring-primary-500"
                                      placeholder="Describe the room type, its features, and what makes it special...">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Facilities -->
                <div class="bg-white rounded-xl shadow-sm border border-primary-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-primary-100 bg-primary-50">
                        <h3 class="text-lg font-semibold text-secondary-900">Facilities & Amenities</h3>
                    </div>
                    
                    <div class="p-6">
                        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                            @php
                                $facilities = [
                                    'WiFi' => 'wifi',
                                    'Air Conditioning' => 'ac',
                                    'Television' => 'tv',
                                    'Mini Bar' => 'minibar',
                                    'Safe Box' => 'safe',
                                    'Balcony' => 'balcony',
                                    'City View' => 'city_view',
                                    'Ocean View' => 'ocean_view',
                                    'Room Service' => 'room_service',
                                    'Breakfast' => 'breakfast',
                                    'Bathtub' => 'bathtub',
                                    'Shower' => 'shower',
                                    'Hair Dryer' => 'hair_dryer',
                                    'Coffee Maker' => 'coffee_maker',
                                    'Work Desk' => 'work_desk',
                                    'Sofa' => 'sofa'
                                ];
                            @endphp

                            @foreach($facilities as $label => $value)
                                <div class="flex items-center">
                                    <input type="checkbox" 
                                           name="facilities[]" 
                                           id="facility_{{ $value }}"
                                           value="{{ $value }}"
                                           {{ in_array($value, old('facilities', [])) ? 'checked' : '' }}
                                           class="rounded border-secondary-300 text-primary-600 focus:ring-primary-500">
                                    <label for="facility_{{ $value }}" class="ml-2 text-sm text-secondary-700">{{ $label }}</label>
                                </div>
                            @endforeach
                        </div>
                        @error('facilities')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Images -->
                <div class="bg-white rounded-xl shadow-sm border border-primary-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-primary-100 bg-primary-50">
                        <h3 class="text-lg font-semibold text-secondary-900">Room Images</h3>
                    </div>
                    
                    <div class="p-6">
                        <div class="space-y-4">
                            <!-- Main Image -->
                            <div>
                                <label for="main_image" class="block text-sm font-medium text-secondary-700 mb-2">Main Image *</label>
                                <input type="file" 
                                       name="main_image" 
                                       id="main_image"
                                       accept="image/*"
                                       required
                                       class="w-full rounded-lg border-secondary-300 focus:border-primary-500 focus:ring-primary-500">
                                <p class="mt-1 text-sm text-secondary-500">Upload the main room image (JPG, PNG, max 2MB)</p>
                                @error('main_image')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Additional Images -->
                            <div>
                                <label for="additional_images" class="block text-sm font-medium text-secondary-700 mb-2">Additional Images</label>
                                <input type="file" 
                                       name="additional_images[]" 
                                       id="additional_images"
                                       accept="image/*"
                                       multiple
                                       class="w-full rounded-lg border-secondary-300 focus:border-primary-500 focus:ring-primary-500">
                                <p class="mt-1 text-sm text-secondary-500">Upload additional room images (JPG, PNG, max 2MB each, up to 5 images)</p>
                                @error('additional_images')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="flex items-center justify-end space-x-4">
                    <a href="{{ route('admin.room-types.index') }}" 
                       class="inline-flex items-center px-6 py-3 text-secondary-600 font-medium rounded-lg border border-secondary-300 hover:bg-secondary-50 transition duration-300">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="inline-flex items-center px-6 py-3 bg-primary-600 text-white font-medium rounded-lg hover:bg-primary-700 transition duration-300">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Create Room Type
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Preview images before upload
        document.getElementById('main_image').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    // You can add image preview functionality here
                    console.log('Main image selected:', file.name);
                };
                reader.readAsDataURL(file);
            }
        });

        document.getElementById('additional_images').addEventListener('change', function(e) {
            const files = e.target.files;
            if (files.length > 5) {
                alert('You can only upload up to 5 additional images.');
                e.target.value = '';
                return;
            }
            console.log('Additional images selected:', files.length);
        });
    </script>
</x-app-layout> 