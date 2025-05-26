<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-secondary-800 leading-tight">
                Room Availability Management
            </h2>
            <div class="flex items-center space-x-3">
                <a href="{{ route('admin.availability.calendar') }}" 
                   class="inline-flex items-center px-4 py-2 bg-primary-600 text-white font-medium rounded-lg hover:bg-primary-700 transition duration-300">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    Calendar View
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            <!-- Date Range Filter -->
            <div class="bg-white rounded-xl shadow-sm border border-primary-100 overflow-hidden">
                <div class="p-6">
                    <form method="GET" action="{{ route('admin.availability.index') }}" class="flex items-end space-x-4">
                        <div>
                            <label for="start_date" class="block text-sm font-medium text-secondary-700 mb-2">Start Date</label>
                            <input type="date" 
                                   name="start_date" 
                                   id="start_date"
                                   value="{{ $startDate }}"
                                   class="rounded-lg border-secondary-300 focus:border-primary-500 focus:ring-primary-500">
                        </div>
                        <div>
                            <label for="end_date" class="block text-sm font-medium text-secondary-700 mb-2">End Date</label>
                            <input type="date" 
                                   name="end_date" 
                                   id="end_date"
                                   value="{{ $endDate }}"
                                   class="rounded-lg border-secondary-300 focus:border-primary-500 focus:ring-primary-500">
                        </div>
                        <button type="submit" class="px-6 py-2 bg-primary-600 text-white font-medium rounded-lg hover:bg-primary-700 transition duration-300">
                            Update View
                        </button>
                    </form>
                </div>
            </div>

            <!-- Room Types and Availability -->
            @foreach($roomTypes as $roomType)
                <div class="bg-white rounded-xl shadow-sm border border-primary-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-primary-100 bg-primary-50">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-secondary-900">{{ $roomType->name }}</h3>
                            <div class="text-sm text-secondary-600">
                                {{ $roomType->rooms->count() }} rooms total
                            </div>
                        </div>
                    </div>

                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                            @foreach($roomType->rooms as $room)
                                @php
                                    $roomBookings = $bookings->where('room_id', $room->id);
                                    $isBooked = $roomBookings->count() > 0;
                                    $currentBooking = $roomBookings->first();
                                @endphp
                                
                                <div class="border border-secondary-200 rounded-lg p-4 hover:shadow-md transition duration-300
                                    @if($isBooked) bg-red-50 border-red-200
                                    @elseif($room->status === 'maintenance') bg-yellow-50 border-yellow-200
                                    @elseif($room->status === 'cleaning') bg-blue-50 border-blue-200
                                    @elseif($room->status === 'out_of_order') bg-gray-50 border-gray-200
                                    @else bg-green-50 border-green-200
                                    @endif">
                                    
                                    <div class="flex items-center justify-between mb-3">
                                        <h4 class="font-medium text-secondary-900">Room {{ $room->room_number }}</h4>
                                        <span class="px-2 py-1 text-xs font-medium rounded-full
                                            @if($isBooked) bg-red-100 text-red-800
                                            @elseif($room->status === 'maintenance') bg-yellow-100 text-yellow-800
                                            @elseif($room->status === 'cleaning') bg-blue-100 text-blue-800
                                            @elseif($room->status === 'out_of_order') bg-gray-100 text-gray-800
                                            @else bg-green-100 text-green-800
                                            @endif">
                                            @if($isBooked)
                                                Booked
                                            @else
                                                {{ ucfirst(str_replace('_', ' ', $room->status)) }}
                                            @endif
                                        </span>
                                    </div>

                                    @if($isBooked && $currentBooking)
                                        <div class="text-sm text-secondary-600 mb-3">
                                            <div><strong>Guest:</strong> {{ $currentBooking->user->name }}</div>
                                            <div><strong>Check-in:</strong> {{ $currentBooking->check_in_date->format('M j') }}</div>
                                            <div><strong>Check-out:</strong> {{ $currentBooking->check_out_date->format('M j') }}</div>
                                            <div><strong>Status:</strong> {{ ucfirst($currentBooking->status) }}</div>
                                        </div>
                                    @endif

                                    <!-- Room Status Update -->
                                    @if(!$isBooked)
                                        <form method="POST" action="{{ route('admin.rooms.update-status', $room) }}" class="space-y-2">
                                            @csrf
                                            @method('PATCH')
                                            <select name="status" class="w-full text-sm rounded border-secondary-300 focus:border-primary-500 focus:ring-primary-500">
                                                <option value="available" {{ $room->status === 'available' ? 'selected' : '' }}>Available</option>
                                                <option value="maintenance" {{ $room->status === 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                                                <option value="cleaning" {{ $room->status === 'cleaning' ? 'selected' : '' }}>Cleaning</option>
                                                <option value="out_of_order" {{ $room->status === 'out_of_order' ? 'selected' : '' }}>Out of Order</option>
                                            </select>
                                            <button type="submit" class="w-full px-3 py-1 bg-primary-600 text-white text-sm font-medium rounded hover:bg-primary-700 transition duration-300">
                                                Update Status
                                            </button>
                                        </form>
                                    @else
                                        <a href="{{ route('admin.bookings.show', $currentBooking) }}" 
                                           class="block w-full px-3 py-1 bg-primary-600 text-white text-sm font-medium rounded text-center hover:bg-primary-700 transition duration-300">
                                            View Booking
                                        </a>
                                    @endif
                                </div>
                            @endforeach
                        </div>

                        <!-- Bulk Actions -->
                        @if($roomType->rooms->count() > 0)
                            <div class="mt-6 pt-6 border-t border-primary-100">
                                <form method="POST" action="{{ route('admin.rooms.bulk-status') }}" class="flex items-center space-x-4">
                                    @csrf
                                    @method('PATCH')
                                    
                                    <div class="flex items-center space-x-2">
                                        <input type="checkbox" id="select-all-{{ $roomType->id }}" class="rounded border-secondary-300 text-primary-600 focus:ring-primary-500">
                                        <label for="select-all-{{ $roomType->id }}" class="text-sm text-secondary-700">Select All Available</label>
                                    </div>
                                    
                                    <select name="status" class="rounded border-secondary-300 focus:border-primary-500 focus:ring-primary-500">
                                        <option value="">Change Status To...</option>
                                        <option value="available">Available</option>
                                        <option value="maintenance">Maintenance</option>
                                        <option value="cleaning">Cleaning</option>
                                        <option value="out_of_order">Out of Order</option>
                                    </select>
                                    
                                    <button type="submit" class="px-4 py-2 bg-secondary-600 text-white font-medium rounded-lg hover:bg-secondary-700 transition duration-300">
                                        Update Selected
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach

            @if($roomTypes->count() === 0)
                <!-- Empty State -->
                <div class="bg-white rounded-xl shadow-sm border border-primary-100 p-12 text-center">
                    <svg class="w-16 h-16 text-secondary-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                    <h3 class="text-lg font-medium text-secondary-900 mb-2">No Room Types Found</h3>
                    <p class="text-secondary-600 mb-6">Create room types first to manage room availability.</p>
                    <a href="{{ route('admin.room-types.create') }}" class="inline-flex items-center px-6 py-3 bg-primary-600 text-white font-medium rounded-lg hover:bg-primary-700 transition duration-300">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Create Room Type
                    </a>
                </div>
            @endif
        </div>
    </div>

    <script>
        // Select all functionality
        document.querySelectorAll('[id^="select-all-"]').forEach(selectAll => {
            selectAll.addEventListener('change', function() {
                const roomTypeId = this.id.split('-')[2];
                const checkboxes = document.querySelectorAll(`input[name="room_ids[]"][data-room-type="${roomTypeId}"]`);
                checkboxes.forEach(checkbox => {
                    checkbox.checked = this.checked;
                });
            });
        });
    </script>
</x-app-layout> 