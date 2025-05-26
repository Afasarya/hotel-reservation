<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-secondary-800 leading-tight">
                My Bookings
            </h2>
            <div class="text-sm text-secondary-600">
                {{ $bookings->total() }} total bookings
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if($bookings->count() > 0)
                <div class="space-y-6">
                    @foreach($bookings as $booking)
                        <div class="bg-white rounded-xl shadow-sm border border-primary-100 overflow-hidden hover:shadow-md transition duration-300">
                            <div class="p-6">
                                <div class="flex items-start justify-between mb-4">
                                    <div class="flex items-start space-x-4">
                                        <!-- Room Image -->
                                        <div class="w-20 h-20 bg-gradient-to-br from-primary-100 to-primary-200 rounded-lg overflow-hidden flex-shrink-0">
                                            @if($booking->roomType->images && count($booking->roomType->images) > 0)
                                                <img src="{{ Storage::url($booking->roomType->images[0]) }}" 
                                                     alt="{{ $booking->roomType->name }}"
                                                     class="w-full h-full object-cover">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center">
                                                    <svg class="w-8 h-8 text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-4m-5 0H3m2 0h3M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                                    </svg>
                                                </div>
                                            @endif
                                        </div>

                                        <!-- Booking Details -->
                                        <div class="flex-1">
                                            <div class="flex items-center space-x-3 mb-2">
                                                <h3 class="text-lg font-semibold text-secondary-900">{{ $booking->roomType->name }}</h3>
                                                <span class="px-3 py-1 text-xs font-medium rounded-full
                                                    @if($booking->status === 'confirmed') bg-green-100 text-green-800
                                                    @elseif($booking->status === 'pending') bg-yellow-100 text-yellow-800
                                                    @elseif($booking->status === 'checked_in') bg-blue-100 text-blue-800
                                                    @elseif($booking->status === 'checked_out') bg-purple-100 text-purple-800
                                                    @else bg-red-100 text-red-800
                                                    @endif">
                                                    {{ ucfirst($booking->status) }}
                                                </span>
                                            </div>
                                            
                                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm text-secondary-600">
                                                <div>
                                                    <span class="font-medium">Booking Code:</span>
                                                    <div class="text-secondary-900 font-mono">{{ $booking->booking_code }}</div>
                                                </div>
                                                <div>
                                                    <span class="font-medium">Check-in:</span>
                                                    <div class="text-secondary-900">{{ $booking->check_in_date->format('M j, Y') }}</div>
                                                </div>
                                                <div>
                                                    <span class="font-medium">Check-out:</span>
                                                    <div class="text-secondary-900">{{ $booking->check_out_date->format('M j, Y') }}</div>
                                                </div>
                                                <div>
                                                    <span class="font-medium">Duration:</span>
                                                    <div class="text-secondary-900">{{ $booking->nights }} {{ $booking->nights == 1 ? 'night' : 'nights' }}</div>
                                                </div>
                                                <div>
                                                    <span class="font-medium">Guests:</span>
                                                    <div class="text-secondary-900">{{ $booking->guests }} {{ $booking->guests == 1 ? 'guest' : 'guests' }}</div>
                                                </div>
                                                <div>
                                                    <span class="font-medium">Room:</span>
                                                    <div class="text-secondary-900">{{ $booking->room ? $booking->room->room_number : 'TBA' }}</div>
                                                </div>
                                            </div>

                                            @if($booking->special_requests)
                                                <div class="mt-3 p-3 bg-primary-50 rounded-lg">
                                                    <span class="text-sm font-medium text-secondary-700">Special Requests:</span>
                                                    <p class="text-sm text-secondary-600 mt-1">{{ $booking->special_requests }}</p>
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Price and Actions -->
                                    <div class="text-right">
                                        <div class="text-2xl font-bold text-primary-600 mb-2">
                                            Rp {{ number_format($booking->total_amount, 0, ',', '.') }}
                                        </div>
                                        
                                        <!-- Payment Status -->
                                        @if($booking->payment)
                                            <div class="mb-3">
                                                <span class="px-2 py-1 text-xs font-medium rounded-full
                                                    @if($booking->payment->status === 'paid') bg-green-100 text-green-800
                                                    @elseif($booking->payment->status === 'pending') bg-yellow-100 text-yellow-800
                                                    @else bg-red-100 text-red-800
                                                    @endif">
                                                    Payment {{ ucfirst($booking->payment->status) }}
                                                </span>
                                            </div>
                                        @endif

                                        <!-- Action Buttons -->
                                        <div class="space-y-2">
                                            <a href="{{ route('bookings.show', $booking) }}" 
                                               class="inline-flex items-center px-4 py-2 bg-primary-600 text-white text-sm font-medium rounded-lg hover:bg-primary-700 transition duration-300">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                                View Details
                                            </a>

                                            @if(!$booking->payment && $booking->status === 'pending')
                                                <a href="{{ route('payments.create', $booking) }}" 
                                                   class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition duration-300">
                                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v2"></path>
                                                    </svg>
                                                    Pay Now
                                                </a>
                                            @endif

                                            @if(in_array($booking->status, ['pending', 'confirmed']) && $booking->check_in_date->gt(now()->addDay()))
                                                <form method="POST" action="{{ route('bookings.destroy', $booking) }}" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            onclick="return confirm('Are you sure you want to cancel this booking?')"
                                                            class="inline-flex items-center px-4 py-2 border border-red-600 text-red-600 text-sm font-medium rounded-lg hover:bg-red-50 transition duration-300">
                                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                        </svg>
                                                        Cancel
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <!-- Booking Timeline -->
                                <div class="border-t border-primary-100 pt-4">
                                    <div class="flex items-center space-x-4 text-sm text-secondary-600">
                                        <div class="flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                            Booked: {{ $booking->created_at->format('M j, Y') }}
                                        </div>
                                        
                                        @if($booking->confirmed_at)
                                            <div class="flex items-center">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                Confirmed: {{ $booking->confirmed_at->format('M j, Y') }}
                                            </div>
                                        @endif

                                        @if($booking->cancelled_at)
                                            <div class="flex items-center text-red-600">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                                Cancelled: {{ $booking->cancelled_at->format('M j, Y') }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-8 flex justify-center">
                    {{ $bookings->links() }}
                </div>
            @else
                <!-- Empty State -->
                <div class="bg-white rounded-xl shadow-sm border border-primary-100 p-12 text-center">
                    <svg class="w-16 h-16 text-secondary-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <h3 class="text-lg font-medium text-secondary-900 mb-2">No bookings yet</h3>
                    <p class="text-secondary-600 mb-6">You haven't made any reservations. Start exploring our rooms!</p>
                    <a href="{{ route('rooms.index') }}" class="inline-flex items-center px-6 py-3 bg-primary-600 text-white font-medium rounded-lg hover:bg-primary-700 transition duration-300">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-4m-5 0H3m2 0h3M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                        Browse Rooms
                    </a>
                </div>
            @endif
        </div>
    </div>
</x-app-layout> 