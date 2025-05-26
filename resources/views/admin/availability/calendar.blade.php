<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-secondary-800 leading-tight">
                Availability Calendar
            </h2>
            <div class="flex items-center space-x-3">
                <a href="{{ route('admin.availability.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-secondary-600 text-white font-medium rounded-lg hover:bg-secondary-700 transition duration-300">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                    </svg>
                    List View
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            <!-- Month Navigation and Room Type Filter -->
            <div class="bg-white rounded-xl shadow-sm border border-primary-100 overflow-hidden">
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <!-- Month Navigation -->
                        <div class="flex items-center space-x-4">
                            <a href="{{ route('admin.availability.calendar', ['month' => $startDate->copy()->subMonth()->format('Y-m'), 'room_type_id' => request('room_type_id')]) }}" 
                               class="p-2 text-secondary-600 hover:text-primary-600 hover:bg-primary-50 rounded-lg transition duration-300">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                </svg>
                            </a>
                            <h3 class="text-xl font-semibold text-secondary-900">
                                {{ $startDate->format('F Y') }}
                            </h3>
                            <a href="{{ route('admin.availability.calendar', ['month' => $startDate->copy()->addMonth()->format('Y-m'), 'room_type_id' => request('room_type_id')]) }}" 
                               class="p-2 text-secondary-600 hover:text-primary-600 hover:bg-primary-50 rounded-lg transition duration-300">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        </div>

                        <!-- Room Type Filter -->
                        <div class="flex items-center space-x-4">
                            <form method="GET" action="{{ route('admin.availability.calendar') }}" class="flex items-center space-x-2">
                                <input type="hidden" name="month" value="{{ $month }}">
                                <select name="room_type_id" onchange="this.form.submit()" class="rounded-lg border-secondary-300 focus:border-primary-500 focus:ring-primary-500">
                                    <option value="">All Room Types</option>
                                    @foreach($roomTypes as $type)
                                        <option value="{{ $type->id }}" {{ request('room_type_id') == $type->id ? 'selected' : '' }}>
                                            {{ $type->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Legend -->
            <div class="bg-white rounded-xl shadow-sm border border-primary-100 overflow-hidden">
                <div class="p-6">
                    <h4 class="text-sm font-medium text-secondary-700 mb-3">Legend</h4>
                    <div class="flex flex-wrap items-center gap-4">
                        <div class="flex items-center space-x-2">
                            <div class="w-4 h-4 bg-green-200 rounded"></div>
                            <span class="text-sm text-secondary-600">Available</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <div class="w-4 h-4 bg-red-200 rounded"></div>
                            <span class="text-sm text-secondary-600">Booked</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <div class="w-4 h-4 bg-yellow-200 rounded"></div>
                            <span class="text-sm text-secondary-600">Maintenance</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <div class="w-4 h-4 bg-blue-200 rounded"></div>
                            <span class="text-sm text-secondary-600">Cleaning</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <div class="w-4 h-4 bg-gray-200 rounded"></div>
                            <span class="text-sm text-secondary-600">Out of Order</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Calendar Grid -->
            <div class="bg-white rounded-xl shadow-sm border border-primary-100 overflow-hidden">
                <div class="p-6">
                    @php
                        $daysInMonth = $startDate->daysInMonth;
                        $firstDayOfWeek = $startDate->copy()->startOfMonth()->dayOfWeek;
                        $weeks = [];
                        $currentWeek = [];
                        
                        // Add empty cells for days before the first day of the month
                        for ($i = 0; $i < $firstDayOfWeek; $i++) {
                            $currentWeek[] = null;
                        }
                        
                        // Add all days of the month
                        for ($day = 1; $day <= $daysInMonth; $day++) {
                            $currentWeek[] = $day;
                            
                            // If we've filled a week (7 days), start a new week
                            if (count($currentWeek) == 7) {
                                $weeks[] = $currentWeek;
                                $currentWeek = [];
                            }
                        }
                        
                        // Add the last partial week if it exists
                        if (!empty($currentWeek)) {
                            // Fill remaining days with null
                            while (count($currentWeek) < 7) {
                                $currentWeek[] = null;
                            }
                            $weeks[] = $currentWeek;
                        }
                    @endphp

                    <!-- Calendar Header -->
                    <div class="grid grid-cols-7 gap-1 mb-4">
                        @foreach(['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'] as $dayName)
                            <div class="p-3 text-center text-sm font-medium text-secondary-700 bg-primary-50 rounded">
                                {{ $dayName }}
                            </div>
                        @endforeach
                    </div>

                    <!-- Calendar Body -->
                    <div class="space-y-1">
                        @foreach($weeks as $week)
                            <div class="grid grid-cols-7 gap-1">
                                @foreach($week as $day)
                                    <div class="min-h-[120px] border border-secondary-200 rounded p-2 {{ $day ? 'bg-white' : 'bg-gray-50' }}">
                                        @if($day)
                                            <div class="text-sm font-medium text-secondary-900 mb-2">{{ $day }}</div>
                                            
                                            @php
                                                $currentDate = $startDate->copy()->day($day);
                                                $dayBookings = $bookings->filter(function($booking) use ($currentDate) {
                                                    return $currentDate->between($booking->check_in_date, $booking->check_out_date->subDay());
                                                });
                                            @endphp

                                            @if($dayBookings->count() > 0)
                                                <div class="space-y-1">
                                                    @foreach($dayBookings->take(3) as $booking)
                                                        <div class="text-xs p-1 rounded bg-red-100 text-red-800 truncate" title="{{ $booking->user->name }} - {{ $booking->roomType->name }}">
                                                            {{ $booking->user->name }}
                                                        </div>
                                                    @endforeach
                                                    @if($dayBookings->count() > 3)
                                                        <div class="text-xs text-secondary-600">
                                                            +{{ $dayBookings->count() - 3 }} more
                                                        </div>
                                                    @endif
                                                </div>
                                            @else
                                                <div class="text-xs text-secondary-500">No bookings</div>
                                            @endif
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Booking Summary for Selected Month -->
            @if($bookings->count() > 0)
                <div class="bg-white rounded-xl shadow-sm border border-primary-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-primary-100 bg-primary-50">
                        <h3 class="text-lg font-semibold text-secondary-900">Bookings for {{ $startDate->format('F Y') }}</h3>
                    </div>
                    
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($bookings->sortBy('check_in_date') as $booking)
                                <div class="border border-secondary-200 rounded-lg p-4 hover:shadow-md transition duration-300">
                                    <div class="flex items-center justify-between mb-2">
                                        <h4 class="font-medium text-secondary-900">{{ $booking->user->name }}</h4>
                                        <span class="px-2 py-1 text-xs font-medium rounded-full
                                            @if($booking->status === 'confirmed') bg-green-100 text-green-800
                                            @elseif($booking->status === 'pending') bg-yellow-100 text-yellow-800
                                            @elseif($booking->status === 'checked_in') bg-blue-100 text-blue-800
                                            @elseif($booking->status === 'checked_out') bg-purple-100 text-purple-800
                                            @else bg-red-100 text-red-800
                                            @endif">
                                            {{ ucfirst($booking->status) }}
                                        </span>
                                    </div>
                                    
                                    <div class="text-sm text-secondary-600 space-y-1">
                                        <div><strong>Room:</strong> {{ $booking->roomType->name }}</div>
                                        <div><strong>Check-in:</strong> {{ $booking->check_in_date->format('M j, Y') }}</div>
                                        <div><strong>Check-out:</strong> {{ $booking->check_out_date->format('M j, Y') }}</div>
                                        <div><strong>Nights:</strong> {{ $booking->nights }}</div>
                                    </div>
                                    
                                    <div class="mt-3">
                                        <a href="{{ route('admin.bookings.show', $booking) }}" 
                                           class="inline-flex items-center px-3 py-1 bg-primary-600 text-white text-sm font-medium rounded hover:bg-primary-700 transition duration-300">
                                            View Details
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