<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-secondary-800 leading-tight">
                    {{ $roomType->name }}
                </h2>
                <p class="text-sm text-secondary-600 mt-1">
                    {{ $roomType->capacity }} guests • {{ $roomType->rooms->count() }} available rooms
                </p>
            </div>
            <div class="text-right">
                <div class="text-2xl font-bold text-primary-600">
                    Rp {{ number_format($roomType->price_per_night, 0, ',', '.') }}
                </div>
                <div class="text-sm text-secondary-500">per night</div>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Main Content -->
                <div class="lg:col-span-2 space-y-8">
                    <!-- Image Gallery -->
                    <div class="bg-white rounded-xl shadow-sm border border-primary-100 overflow-hidden">
                        @if($roomType->images && count($roomType->images) > 0)
                            <div class="relative">
                                <!-- Main Image -->
                                <div class="h-96 bg-gradient-to-br from-primary-100 to-primary-200">
                                    <img src="{{ Storage::url($roomType->images[0]) }}" 
                                         alt="{{ $roomType->name }}"
                                         class="w-full h-full object-cover"
                                         id="mainImage">
                                </div>
                                
                                <!-- Image Navigation -->
                                @if(count($roomType->images) > 1)
                                    <div class="absolute bottom-4 left-4 right-4">
                                        <div class="flex space-x-2 overflow-x-auto">
                                            @foreach($roomType->images as $index => $image)
                                                <button onclick="changeImage('{{ Storage::url($image) }}')"
                                                        class="flex-shrink-0 w-16 h-16 rounded-lg overflow-hidden border-2 border-white hover:border-primary-300 transition duration-300">
                                                    <img src="{{ Storage::url($image) }}" 
                                                         alt="Room image {{ $index + 1 }}"
                                                         class="w-full h-full object-cover">
                                                </button>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @else
                            <div class="h-96 bg-gradient-to-br from-primary-100 to-primary-200 flex items-center justify-center">
                                <svg class="w-24 h-24 text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-4m-5 0H3m2 0h3M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                            </div>
                        @endif
                    </div>

                    <!-- Room Description -->
                    <div class="bg-white rounded-xl shadow-sm border border-primary-100 p-6">
                        <h3 class="text-lg font-semibold text-secondary-900 mb-4">About This Room</h3>
                        <p class="text-secondary-700 leading-relaxed">{{ $roomType->description }}</p>
                    </div>

                    <!-- Facilities & Amenities -->
                    @if($roomType->facilities && count($roomType->facilities) > 0)
                        <div class="bg-white rounded-xl shadow-sm border border-primary-100 p-6">
                            <h3 class="text-lg font-semibold text-secondary-900 mb-4">Facilities & Amenities</h3>
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                @foreach($roomType->facilities as $facility)
                                    <div class="flex items-center space-x-3 p-3 bg-primary-50 rounded-lg">
                                        <div class="p-2 bg-primary-100 rounded-lg">
                                            <svg class="w-4 h-4 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                        </div>
                                        <span class="text-sm font-medium text-secondary-700">{{ $facility }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Similar Rooms -->
                    @if($similarRooms->count() > 0)
                        <div class="bg-white rounded-xl shadow-sm border border-primary-100 p-6">
                            <h3 class="text-lg font-semibold text-secondary-900 mb-4">Similar Rooms</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach($similarRooms as $similar)
                                    <div class="border border-primary-100 rounded-lg p-4 hover:shadow-md transition duration-300">
                                        <div class="flex items-start justify-between mb-2">
                                            <h4 class="font-medium text-secondary-900">{{ $similar->name }}</h4>
                                            <span class="text-sm font-bold text-primary-600">
                                                Rp {{ number_format($similar->price_per_night, 0, ',', '.') }}
                                            </span>
                                        </div>
                                        <p class="text-sm text-secondary-600 mb-3">{{ Str::limit($similar->description, 80) }}</p>
                                        <a href="{{ route('rooms.show', $similar) }}" 
                                           class="inline-flex items-center text-sm text-primary-600 hover:text-primary-700 font-medium">
                                            View Details
                                            <svg class="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                            </svg>
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Booking Sidebar -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-xl shadow-sm border border-primary-100 p-6 sticky top-8">
                        <div class="text-center mb-6">
                            <div class="text-3xl font-bold text-primary-600 mb-1">
                                Rp {{ number_format($roomType->price_per_night, 0, ',', '.') }}
                            </div>
                            <div class="text-sm text-secondary-500">per night</div>
                        </div>

                        <!-- Quick Info -->
                        <div class="space-y-3 mb-6">
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-secondary-600">Capacity</span>
                                <span class="font-medium text-secondary-900">{{ $roomType->capacity }} guests</span>
                            </div>
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-secondary-600">Available Rooms</span>
                                <span class="font-medium text-secondary-900">{{ $roomType->rooms->count() }}</span>
                            </div>
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-secondary-600">Total Rooms</span>
                                <span class="font-medium text-secondary-900">{{ $roomType->total_rooms }}</span>
                            </div>
                        </div>

                        <!-- Availability Status -->
                        <div class="mb-6">
                            @if($roomType->rooms->count() > 0)
                                <div class="flex items-center p-3 bg-green-50 border border-green-200 rounded-lg">
                                    <svg class="w-5 h-5 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span class="text-sm font-medium text-green-800">Available for booking</span>
                                </div>
                            @else
                                <div class="flex items-center p-3 bg-red-50 border border-red-200 rounded-lg">
                                    <svg class="w-5 h-5 text-red-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                    <span class="text-sm font-medium text-red-800">Currently unavailable</span>
                                </div>
                            @endif
                        </div>

                        <!-- Action Buttons -->
                        <div class="space-y-3">
                            @if($roomType->rooms->count() > 0)
                                <a href="{{ route('bookings.create', $roomType) }}" 
                                   class="w-full inline-flex items-center justify-center px-6 py-3 bg-primary-600 text-white font-semibold rounded-lg hover:bg-primary-700 transition duration-300">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    Book This Room
                                </a>
                            @else
                                <button disabled 
                                        class="w-full inline-flex items-center justify-center px-6 py-3 bg-secondary-300 text-secondary-500 font-semibold rounded-lg cursor-not-allowed">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                    Unavailable
                                </button>
                            @endif
                            
                            <a href="{{ route('rooms.index') }}" 
                               class="w-full inline-flex items-center justify-center px-6 py-3 border border-primary-600 text-primary-600 font-semibold rounded-lg hover:bg-primary-50 transition duration-300">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                </svg>
                                Browse Other Rooms
                            </a>
                        </div>

                        <!-- Contact Info -->
                        <div class="mt-6 pt-6 border-t border-primary-100">
                            <h4 class="font-medium text-secondary-900 mb-3">Need Help?</h4>
                            <div class="space-y-2 text-sm text-secondary-600">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                    </svg>
                                    +62 21 1234 5678
                                </div>
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                    info@aoragrand.com
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function changeImage(src) {
            document.getElementById('mainImage').src = src;
        }
    </script>
</x-app-layout>