<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <a href="{{ route('bookings.index') }}" 
                   class="inline-flex items-center text-secondary-600 hover:text-secondary-900 transition duration-300">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    Back to Bookings
                </a>
                <h2 class="font-semibold text-xl text-secondary-800 leading-tight">
                    Booking Details
                </h2>
            </div>
            <div class="text-sm text-secondary-600">
                {{ $booking->booking_code }}
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Booking Status Alert -->
            @if($booking->status === 'cancelled')
                <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-red-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div>
                            <h3 class="text-sm font-medium text-red-800">Booking Cancelled</h3>
                            <p class="text-sm text-red-700 mt-1">This booking was cancelled on {{ $booking->cancelled_at->format('M j, Y \a\t g:i A') }}</p>
                        </div>
                    </div>
                </div>
            @elseif($booking->status === 'pending' && !$booking->payment)
                <div class="mb-6 bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-yellow-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div>
                                <h3 class="text-sm font-medium text-yellow-800">Payment Required</h3>
                                <p class="text-sm text-yellow-700 mt-1">Please complete your payment to confirm this booking</p>
                            </div>
                        </div>
                        <a href="{{ route('payments.create', $booking) }}" 
                           class="inline-flex items-center px-4 py-2 bg-yellow-600 text-white text-sm font-medium rounded-lg hover:bg-yellow-700 transition duration-300">
                            Pay Now
                        </a>
                    </div>
                </div>
            @elseif($booking->status === 'confirmed')
                <div class="mb-6 bg-green-50 border border-green-200 rounded-lg p-4">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-green-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div>
                            <h3 class="text-sm font-medium text-green-800">Booking Confirmed</h3>
                            <p class="text-sm text-green-700 mt-1">Your booking is confirmed and ready for check-in</p>
                        </div>
                    </div>
                </div>
            @endif

            <div class="space-y-6">
                <!-- Main Booking Card -->
                <div class="bg-white rounded-xl shadow-sm border border-primary-100 overflow-hidden">
                    <div class="p-6">
                        <div class="flex items-start justify-between mb-6">
                            <div>
                                <h1 class="text-2xl font-bold text-secondary-900 mb-2">{{ $booking->roomType->name }}</h1>
                                <div class="flex items-center space-x-3">
                                    <span class="px-3 py-1 text-sm font-medium rounded-full
                                        @if($booking->status === 'confirmed') bg-green-100 text-green-800
                                        @elseif($booking->status === 'pending') bg-yellow-100 text-yellow-800
                                        @elseif($booking->status === 'checked_in') bg-blue-100 text-blue-800
                                        @elseif($booking->status === 'checked_out') bg-purple-100 text-purple-800
                                        @else bg-red-100 text-red-800
                                        @endif">
                                        {{ ucfirst($booking->status) }}
                                    </span>
                                    @if($booking->payment)
                                        <span class="px-3 py-1 text-sm font-medium rounded-full
                                            @if($booking->payment->status === 'paid') bg-green-100 text-green-800
                                            @elseif($booking->payment->status === 'pending') bg-yellow-100 text-yellow-800
                                            @else bg-red-100 text-red-800
                                            @endif">
                                            Payment {{ ucfirst($booking->payment->status) }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="text-3xl font-bold text-primary-600">
                                    Rp {{ number_format($booking->total_amount, 0, ',', '.') }}
                                </div>
                                <div class="text-sm text-secondary-600">
                                    {{ $booking->nights }} {{ $booking->nights == 1 ? 'night' : 'nights' }}
                                </div>
                            </div>
                        </div>

                        <!-- Room Images -->
                        @if($booking->roomType->images && count($booking->roomType->images) > 0)
                            <div class="mb-6">
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    @foreach($booking->roomType->images as $index => $image)
                                        @if($index < 3)
                                            <div class="aspect-w-16 aspect-h-10 {{ $index === 0 ? 'md:col-span-2 md:row-span-2' : '' }}">
                                                <img src="{{ Storage::url($image) }}" 
                                                     alt="{{ $booking->roomType->name }}"
                                                     class="w-full h-full object-cover rounded-lg">
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Booking Details Grid -->
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
                            <div class="bg-primary-50 rounded-lg p-4">
                                <div class="flex items-center mb-2">
                                    <svg class="w-5 h-5 text-primary-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <h3 class="font-semibold text-secondary-900">Check-in</h3>
                                </div>
                                <p class="text-lg font-medium text-secondary-800">{{ $booking->check_in_date->format('M j, Y') }}</p>
                                <p class="text-sm text-secondary-600">{{ $booking->check_in_date->format('l') }}</p>
                            </div>

                            <div class="bg-primary-50 rounded-lg p-4">
                                <div class="flex items-center mb-2">
                                    <svg class="w-5 h-5 text-primary-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <h3 class="font-semibold text-secondary-900">Check-out</h3>
                                </div>
                                <p class="text-lg font-medium text-secondary-800">{{ $booking->check_out_date->format('M j, Y') }}</p>
                                <p class="text-sm text-secondary-600">{{ $booking->check_out_date->format('l') }}</p>
                            </div>

                            <div class="bg-primary-50 rounded-lg p-4">
                                <div class="flex items-center mb-2">
                                    <svg class="w-5 h-5 text-primary-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                    <h3 class="font-semibold text-secondary-900">Guests</h3>
                                </div>
                                <p class="text-lg font-medium text-secondary-800">{{ $booking->guests }}</p>
                                <p class="text-sm text-secondary-600">{{ $booking->guests == 1 ? 'guest' : 'guests' }}</p>
                            </div>

                            <div class="bg-secondary-50 rounded-lg p-4">
                                <div class="flex items-center mb-2">
                                    <svg class="w-5 h-5 text-secondary-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-4m-5 0H3m2 0h3M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                    </svg>
                                    <h3 class="font-semibold text-secondary-900">Room Number</h3>
                                </div>
                                <p class="text-lg font-medium text-secondary-800">{{ $booking->room ? $booking->room->room_number : 'To be assigned' }}</p>
                                <p class="text-sm text-secondary-600">{{ $booking->roomType->name }}</p>
                            </div>

                            <div class="bg-secondary-50 rounded-lg p-4">
                                <div class="flex items-center mb-2">
                                    <svg class="w-5 h-5 text-secondary-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    <h3 class="font-semibold text-secondary-900">Booking Code</h3>
                                </div>
                                <p class="text-lg font-medium text-secondary-800 font-mono">{{ $booking->booking_code }}</p>
                                <p class="text-sm text-secondary-600">Reference number</p>
                            </div>

                            <div class="bg-secondary-50 rounded-lg p-4">
                                <div class="flex items-center mb-2">
                                    <svg class="w-5 h-5 text-secondary-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                    </svg>
                                    <h3 class="font-semibold text-secondary-900">Price per Night</h3>
                                </div>
                                <p class="text-lg font-medium text-secondary-800">Rp {{ number_format($booking->roomType->price_per_night, 0, ',', '.') }}</p>
                                <p class="text-sm text-secondary-600">per night</p>
                            </div>
                        </div>

                        <!-- Room Description -->
                        @if($booking->roomType->description)
                            <div class="mb-6">
                                <h3 class="text-lg font-semibold text-secondary-900 mb-3">Room Description</h3>
                                <p class="text-secondary-700 leading-relaxed">{{ $booking->roomType->description }}</p>
                            </div>
                        @endif

                        <!-- Room Amenities -->
                        @if($booking->roomType->amenities && count($booking->roomType->amenities) > 0)
                            <div class="mb-6">
                                <h3 class="text-lg font-semibold text-secondary-900 mb-3">Room Amenities</h3>
                                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
                                    @foreach($booking->roomType->amenities as $amenity)
                                        <div class="flex items-center space-x-2 text-sm text-secondary-700">
                                            <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            <span>{{ $amenity }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Special Requests -->
                        @if($booking->special_requests)
                            <div class="mb-6">
                                <h3 class="text-lg font-semibold text-secondary-900 mb-3">Special Requests</h3>
                                <div class="bg-primary-50 rounded-lg p-4">
                                    <p class="text-secondary-700">{{ $booking->special_requests }}</p>
                                </div>
                            </div>
                        @endif

                        <!-- Booking Timeline -->
                        <div class="border-t border-primary-100 pt-6">
                            <h3 class="text-lg font-semibold text-secondary-900 mb-4">Booking Timeline</h3>
                            <div class="space-y-3">
                                <div class="flex items-center space-x-3">
                                    <div class="w-2 h-2 bg-blue-500 rounded-full"></div>
                                    <div class="text-sm">
                                        <span class="font-medium text-secondary-900">Booking Created:</span>
                                        <span class="text-secondary-600">{{ $booking->created_at->format('M j, Y \a\t g:i A') }}</span>
                                    </div>
                                </div>

                                @if($booking->payment && $booking->payment->status === 'paid')
                                    <div class="flex items-center space-x-3">
                                        <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                                        <div class="text-sm">
                                            <span class="font-medium text-secondary-900">Payment Completed:</span>
                                            <span class="text-secondary-600">{{ $booking->payment->paid_at ? $booking->payment->paid_at->format('M j, Y \a\t g:i A') : 'N/A' }}</span>
                                        </div>
                                    </div>
                                @endif

                                @if($booking->confirmed_at)
                                    <div class="flex items-center space-x-3">
                                        <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                                        <div class="text-sm">
                                            <span class="font-medium text-secondary-900">Booking Confirmed:</span>
                                            <span class="text-secondary-600">{{ $booking->confirmed_at->format('M j, Y \a\t g:i A') }}</span>
                                        </div>
                                    </div>
                                @endif

                                @if($booking->cancelled_at)
                                    <div class="flex items-center space-x-3">
                                        <div class="w-2 h-2 bg-red-500 rounded-full"></div>
                                        <div class="text-sm">
                                            <span class="font-medium text-secondary-900">Booking Cancelled:</span>
                                            <span class="text-secondary-600">{{ $booking->cancelled_at->format('M j, Y \a\t g:i A') }}</span>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payment Information -->
                @if($booking->payment)
                    <div class="bg-white rounded-xl shadow-sm border border-primary-100 overflow-hidden">
                        <div class="px-6 py-4 border-b border-primary-100">
                            <h3 class="text-lg font-semibold text-secondary-900">Payment Information</h3>
                        </div>
                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <div class="space-y-3">
                                        <div>
                                            <span class="text-sm font-medium text-secondary-600">Payment Method:</span>
                                            <p class="text-secondary-900">{{ ucfirst($booking->payment->payment_method) }}</p>
                                        </div>
                                        <div>
                                            <span class="text-sm font-medium text-secondary-600">Amount:</span>
                                            <p class="text-secondary-900">Rp {{ number_format($booking->payment->amount, 0, ',', '.') }}</p>
                                        </div>
                                        <div>
                                            <span class="text-sm font-medium text-secondary-600">Status:</span>
                                            <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full
                                                @if($booking->payment->status === 'paid') bg-green-100 text-green-800
                                                @elseif($booking->payment->status === 'pending') bg-yellow-100 text-yellow-800
                                                @else bg-red-100 text-red-800
                                                @endif">
                                                {{ ucfirst($booking->payment->status) }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    @if($booking->payment->status === 'paid')
                                        <div class="space-y-3">
                                            <div>
                                                <span class="text-sm font-medium text-secondary-600">Transaction ID:</span>
                                                <p class="text-secondary-900 font-mono text-sm">{{ $booking->payment->transaction_id }}</p>
                                            </div>
                                            @if($booking->payment->paid_at)
                                                <div>
                                                    <span class="text-sm font-medium text-secondary-600">Paid at:</span>
                                                    <p class="text-secondary-900">{{ $booking->payment->paid_at->format('M j, Y \a\t g:i A') }}</p>
                                                </div>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Action Buttons -->
                <div class="bg-white rounded-xl shadow-sm border border-primary-100 p-6">
                    <div class="flex flex-wrap gap-3">
                        @if(!$booking->payment && $booking->status === 'pending')
                            <a href="{{ route('payments.create', $booking) }}" 
                               class="inline-flex items-center px-6 py-3 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 transition duration-300">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v2"></path>
                                </svg>
                                Complete Payment
                            </a>
                        @endif

                        @if($booking->payment && $booking->payment->status === 'paid')
                            <a href="{{ route('payments.show', $booking->payment) }}" 
                               class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition duration-300">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                View Receipt
                            </a>
                        @endif

                        @if(in_array($booking->status, ['pending', 'confirmed']) && $booking->check_in_date->gt(now()->addDay()))
                            <form method="POST" action="{{ route('bookings.destroy', $booking) }}" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        onclick="return confirm('Are you sure you want to cancel this booking?')"
                                        class="inline-flex items-center px-6 py-3 border border-red-600 text-red-600 font-medium rounded-lg hover:bg-red-50 transition duration-300">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                    Cancel Booking
                                </button>
                            </form>
                        @endif

                        <a href="{{ route('rooms.index') }}" 
                           class="inline-flex items-center px-6 py-3 border border-secondary-300 text-secondary-700 font-medium rounded-lg hover:bg-secondary-50 transition duration-300">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-4m-5 0H3m2 0h3M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                            Browse More Rooms
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>