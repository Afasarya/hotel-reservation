<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-secondary-800 leading-tight">
                Welcome back, {{ Auth::user()->name }}!
            </h2>
            <div class="text-sm text-secondary-600">
                {{ now()->format('l, F j, Y') }}
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            <!-- Hero Section -->
            <div class="bg-gradient-to-r from-primary-600 to-primary-800 rounded-2xl shadow-xl overflow-hidden">
                <div class="px-8 py-12 text-white">
                    <div class="max-w-3xl">
                        <h1 class="text-4xl font-bold mb-4">Experience Luxury at AoraGrand</h1>
                        <p class="text-xl text-primary-100 mb-8">Discover our premium rooms and enjoy world-class hospitality in the heart of the city.</p>
                        <a href="{{ route('rooms.index') }}" class="inline-flex items-center px-6 py-3 bg-white text-primary-600 font-semibold rounded-lg hover:bg-primary-50 transition duration-300">
                            <span>Explore Rooms</span>
                            <svg class="ml-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white rounded-xl shadow-sm border border-primary-100 p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-primary-100 rounded-lg">
                            <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-4m-5 0H3m2 0h3M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-secondary-600">Available Rooms</p>
                            <p class="text-2xl font-bold text-secondary-900">{{ $roomTypes->count() }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-primary-100 p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-green-100 rounded-lg">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-secondary-600">My Bookings</p>
                            <p class="text-2xl font-bold text-secondary-900">{{ $recentBookings->count() }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-primary-100 p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-blue-100 rounded-lg">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-secondary-600">Member Since</p>
                            <p class="text-2xl font-bold text-secondary-900">{{ Auth::user()->created_at->format('Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Featured Room Types -->
            <div class="bg-white rounded-xl shadow-sm border border-primary-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-primary-100">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-secondary-900">Featured Room Types</h3>
                        <a href="{{ route('rooms.index') }}" class="text-primary-600 hover:text-primary-700 font-medium text-sm">View All</a>
                    </div>
                </div>
                
                @if($roomTypes->count() > 0)
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($roomTypes->take(6) as $roomType)
                                <div class="group cursor-pointer" onclick="window.location='{{ route('rooms.show', $roomType) }}'">
                                    <div class="bg-gradient-to-br from-primary-50 to-white rounded-lg border border-primary-100 p-6 hover:shadow-lg transition duration-300 group-hover:border-primary-300">
                                        <div class="flex items-start justify-between mb-4">
                                            <div class="p-2 bg-primary-100 rounded-lg group-hover:bg-primary-200 transition duration-300">
                                                <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-4m-5 0H3m2 0h3M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                                </svg>
                                            </div>
                                            <span class="text-xs font-medium text-primary-600 bg-primary-100 px-2 py-1 rounded-full">
                                                {{ $roomType->rooms_count }} rooms
                                            </span>
                                        </div>
                                        
                                        <h4 class="font-semibold text-secondary-900 mb-2 group-hover:text-primary-600 transition duration-300">{{ $roomType->name }}</h4>
                                        <p class="text-sm text-secondary-600 mb-4 line-clamp-2">{{ Str::limit($roomType->description, 80) }}</p>
                                        
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <span class="text-lg font-bold text-primary-600">Rp {{ number_format($roomType->price_per_night, 0, ',', '.') }}</span>
                                                <span class="text-sm text-secondary-500">/night</span>
                                            </div>
                                            <div class="flex items-center text-sm text-secondary-500">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                </svg>
                                                {{ $roomType->capacity }} guests
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @else
                    <div class="p-12 text-center">
                        <svg class="w-16 h-16 text-secondary-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-4m-5 0H3m2 0h3M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                        <h3 class="text-lg font-medium text-secondary-900 mb-2">No Room Types Available</h3>
                        <p class="text-secondary-600">Check back later for available rooms.</p>
                    </div>
                @endif
            </div>

            <!-- Recent Bookings -->
            @if($recentBookings->count() > 0)
                <div class="bg-white rounded-xl shadow-sm border border-primary-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-primary-100">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-secondary-900">Recent Bookings</h3>
                            <a href="{{ route('bookings.index') }}" class="text-primary-600 hover:text-primary-700 font-medium text-sm">View All</a>
                        </div>
                    </div>
                    
                    <div class="p-6">
                        <div class="space-y-4">
                            @foreach($recentBookings as $booking)
                                <div class="flex items-center justify-between p-4 bg-gradient-to-r from-primary-50 to-white rounded-lg border border-primary-100 hover:shadow-md transition duration-300">
                                    <div class="flex items-center space-x-4">
                                        <div class="p-2 bg-primary-100 rounded-lg">
                                            <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <h4 class="font-medium text-secondary-900">{{ $booking->roomType->name }}</h4>
                                            <p class="text-sm text-secondary-600">{{ $booking->booking_code }} • {{ $booking->check_in_date->format('M j') }} - {{ $booking->check_out_date->format('M j, Y') }}</p>
                                        </div>
                                    </div>
                                    
                                    <div class="flex items-center space-x-4">
                                        <span class="px-3 py-1 text-xs font-medium rounded-full
                                            @if($booking->status === 'confirmed') bg-green-100 text-green-800
                                            @elseif($booking->status === 'pending') bg-yellow-100 text-yellow-800
                                            @elseif($booking->status === 'checked_in') bg-blue-100 text-blue-800
                                            @elseif($booking->status === 'checked_out') bg-purple-100 text-purple-800
                                            @else bg-red-100 text-red-800
                                            @endif">
                                            {{ ucfirst($booking->status) }}
                                        </span>
                                        <a href="{{ route('bookings.show', $booking) }}" class="text-primary-600 hover:text-primary-700">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
