<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-secondary-800 leading-tight">
                Admin Dashboard
            </h2>
            <div class="text-sm text-secondary-600">
                {{ now()->format('l, F j, Y') }}
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Total Users -->
                <div class="bg-white rounded-xl shadow-sm border border-primary-100 p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-blue-100 rounded-lg">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-secondary-600">Total Users</p>
                            <p class="text-2xl font-bold text-secondary-900">{{ $totalUsers }}</p>
                        </div>
                    </div>
                </div>

                <!-- Total Room Types -->
                <div class="bg-white rounded-xl shadow-sm border border-primary-100 p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-primary-100 rounded-lg">
                            <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-4m-5 0H3m2 0h3M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-secondary-600">Room Types</p>
                            <p class="text-2xl font-bold text-secondary-900">{{ $totalRoomTypes }}</p>
                        </div>
                    </div>
                </div>

                <!-- Total Bookings -->
                <div class="bg-white rounded-xl shadow-sm border border-primary-100 p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-green-100 rounded-lg">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-secondary-600">Total Bookings</p>
                            <p class="text-2xl font-bold text-secondary-900">{{ $totalBookings }}</p>
                        </div>
                    </div>
                </div>

                <!-- Total Revenue -->
                <div class="bg-white rounded-xl shadow-sm border border-primary-100 p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-yellow-100 rounded-lg">
                            <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-secondary-600">Total Revenue</p>
                            <p class="text-2xl font-bold text-secondary-900">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Recent Bookings -->
                <div class="bg-white rounded-xl shadow-sm border border-primary-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-primary-100">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-secondary-900">Recent Bookings</h3>
                            <a href="{{ route('admin.bookings.index') }}" class="text-primary-600 hover:text-primary-700 font-medium text-sm">View All</a>
                        </div>
                    </div>
                    
                    @if($recentBookings->count() > 0)
                        <div class="p-6">
                            <div class="space-y-4">
                                @foreach($recentBookings as $booking)
                                    <div class="flex items-center justify-between p-4 bg-gradient-to-r from-primary-50 to-white rounded-lg border border-primary-100">
                                        <div class="flex items-center space-x-3">
                                            <div class="p-2 bg-primary-100 rounded-lg">
                                                <svg class="w-4 h-4 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                </svg>
                                            </div>
                                            <div>
                                                <h4 class="font-medium text-secondary-900">{{ $booking->user->name }}</h4>
                                                <p class="text-sm text-secondary-600">{{ $booking->roomType->name }} • {{ $booking->booking_code }}</p>
                                            </div>
                                        </div>
                                        
                                        <div class="text-right">
                                            <span class="px-2 py-1 text-xs font-medium rounded-full
                                                @if($booking->status === 'confirmed') bg-green-100 text-green-800
                                                @elseif($booking->status === 'pending') bg-yellow-100 text-yellow-800
                                                @elseif($booking->status === 'checked_in') bg-blue-100 text-blue-800
                                                @elseif($booking->status === 'checked_out') bg-purple-100 text-purple-800
                                                @else bg-red-100 text-red-800
                                                @endif">
                                                {{ ucfirst($booking->status) }}
                                            </span>
                                            <div class="text-sm text-secondary-600 mt-1">
                                                Rp {{ number_format($booking->total_amount, 0, ',', '.') }}
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <div class="p-12 text-center">
                            <svg class="w-12 h-12 text-secondary-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <h3 class="text-lg font-medium text-secondary-900 mb-2">No Recent Bookings</h3>
                            <p class="text-secondary-600">No bookings have been made yet.</p>
                        </div>
                    @endif
                </div>

                <!-- Booking Status Distribution -->
                <div class="bg-white rounded-xl shadow-sm border border-primary-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-primary-100">
                        <h3 class="text-lg font-semibold text-secondary-900">Booking Status</h3>
                    </div>
                    
                    <div class="p-6">
                        @if($bookingStats->count() > 0)
                            <div class="space-y-4">
                                @foreach($bookingStats as $status => $count)
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center space-x-3">
                                            <div class="w-4 h-4 rounded-full
                                                @if($status === 'confirmed') bg-green-500
                                                @elseif($status === 'pending') bg-yellow-500
                                                @elseif($status === 'checked_in') bg-blue-500
                                                @elseif($status === 'checked_out') bg-purple-500
                                                @else bg-red-500
                                                @endif">
                                            </div>
                                            <span class="text-sm font-medium text-secondary-900 capitalize">{{ $status }}</span>
                                        </div>
                                        <span class="text-sm font-bold text-secondary-900">{{ $count }}</span>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <p class="text-secondary-600">No booking data available</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Popular Room Types -->
            @if($popularRoomTypes->count() > 0)
                <div class="bg-white rounded-xl shadow-sm border border-primary-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-primary-100">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-secondary-900">Popular Room Types</h3>
                            <a href="{{ route('admin.room-types.index') }}" class="text-primary-600 hover:text-primary-700 font-medium text-sm">Manage Rooms</a>
                        </div>
                    </div>
                    
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($popularRoomTypes as $roomType)
                                <div class="border border-primary-100 rounded-lg p-4 hover:shadow-md transition duration-300">
                                    <div class="flex items-start justify-between mb-3">
                                        <h4 class="font-medium text-secondary-900">{{ $roomType->name }}</h4>
                                        <span class="px-2 py-1 text-xs font-medium bg-primary-100 text-primary-700 rounded-full">
                                            {{ $roomType->bookings_count }} bookings
                                        </span>
                                    </div>
                                    <p class="text-sm text-secondary-600 mb-3">{{ Str::limit($roomType->description, 80) }}</p>
                                    <div class="flex items-center justify-between text-sm">
                                        <span class="font-medium text-primary-600">
                                            Rp {{ number_format($roomType->price_per_night, 0, ',', '.') }}/night
                                        </span>
                                        <span class="text-secondary-500">
                                            {{ $roomType->capacity }} guests
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <!-- Quick Actions -->
            <div class="bg-white rounded-xl shadow-sm border border-primary-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-primary-100">
                    <h3 class="text-lg font-semibold text-secondary-900">Quick Actions</h3>
                </div>
                
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <a href="{{ route('admin.room-types.create') }}" 
                           class="flex items-center p-4 bg-gradient-to-r from-primary-50 to-primary-100 rounded-lg border border-primary-200 hover:shadow-md transition duration-300">
                            <div class="p-2 bg-primary-600 rounded-lg mr-3">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-medium text-secondary-900">Add Room Type</h4>
                                <p class="text-sm text-secondary-600">Create new room</p>
                            </div>
                        </a>

                        <a href="{{ route('admin.bookings.index') }}" 
                           class="flex items-center p-4 bg-gradient-to-r from-green-50 to-green-100 rounded-lg border border-green-200 hover:shadow-md transition duration-300">
                            <div class="p-2 bg-green-600 rounded-lg mr-3">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-medium text-secondary-900">Manage Bookings</h4>
                                <p class="text-sm text-secondary-600">View all bookings</p>
                            </div>
                        </a>

                        <a href="{{ route('admin.payments.index') }}" 
                           class="flex items-center p-4 bg-gradient-to-r from-yellow-50 to-yellow-100 rounded-lg border border-yellow-200 hover:shadow-md transition duration-300">
                            <div class="p-2 bg-yellow-600 rounded-lg mr-3">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v2"></path>
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-medium text-secondary-900">View Payments</h4>
                                <p class="text-sm text-secondary-600">Monitor payments</p>
                            </div>
                        </a>

                        <a href="{{ route('admin.room-types.index') }}" 
                           class="flex items-center p-4 bg-gradient-to-r from-blue-50 to-blue-100 rounded-lg border border-blue-200 hover:shadow-md transition duration-300">
                            <div class="p-2 bg-blue-600 rounded-lg mr-3">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-4m-5 0H3m2 0h3M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-medium text-secondary-900">Room Types</h4>
                                <p class="text-sm text-secondary-600">Manage rooms</p>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 