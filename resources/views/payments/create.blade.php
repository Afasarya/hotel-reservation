<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-secondary-800 leading-tight">
                    Payment for {{ $booking->roomType->name }}
                </h2>
                <p class="text-sm text-secondary-600 mt-1">
                    Booking Code: {{ $booking->booking_code }}
                </p>
            </div>
            <div class="text-right">
                <div class="text-2xl font-bold text-primary-600">
                    Rp {{ number_format($booking->total_amount, 0, ',', '.') }}
                </div>
                <div class="text-sm text-secondary-500">Total Amount</div>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Payment Form -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-xl shadow-sm border border-primary-100 overflow-hidden">
                        <div class="px-6 py-4 border-b border-primary-100">
                            <h3 class="text-lg font-semibold text-secondary-900">Complete Your Payment</h3>
                        </div>
                        
                        <div class="p-6">
                            <!-- Payment Methods -->
                            <div class="mb-6">
                                <h4 class="font-medium text-secondary-900 mb-4">Available Payment Methods</h4>
                                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                    <div class="p-4 border border-primary-200 rounded-lg text-center hover:border-primary-400 transition duration-300">
                                        <div class="w-12 h-8 bg-blue-600 rounded mx-auto mb-2 flex items-center justify-center">
                                            <span class="text-white text-xs font-bold">VISA</span>
                                        </div>
                                        <span class="text-sm text-secondary-600">Credit Card</span>
                                    </div>
                                    <div class="p-4 border border-primary-200 rounded-lg text-center hover:border-primary-400 transition duration-300">
                                        <div class="w-12 h-8 bg-red-600 rounded mx-auto mb-2 flex items-center justify-center">
                                            <span class="text-white text-xs font-bold">MC</span>
                                        </div>
                                        <span class="text-sm text-secondary-600">Mastercard</span>
                                    </div>
                                    <div class="p-4 border border-primary-200 rounded-lg text-center hover:border-primary-400 transition duration-300">
                                        <div class="w-12 h-8 bg-blue-500 rounded mx-auto mb-2 flex items-center justify-center">
                                            <span class="text-white text-xs font-bold">BCA</span>
                                        </div>
                                        <span class="text-sm text-secondary-600">Bank Transfer</span>
                                    </div>
                                    <div class="p-4 border border-primary-200 rounded-lg text-center hover:border-primary-400 transition duration-300">
                                        <div class="w-12 h-8 bg-green-600 rounded mx-auto mb-2 flex items-center justify-center">
                                            <span class="text-white text-xs font-bold">OVO</span>
                                        </div>
                                        <span class="text-sm text-secondary-600">E-Wallet</span>
                                    </div>
                                    <div class="p-4 border border-primary-200 rounded-lg text-center hover:border-primary-400 transition duration-300">
                                        <div class="w-12 h-8 bg-blue-700 rounded mx-auto mb-2 flex items-center justify-center">
                                            <span class="text-white text-xs font-bold">DANA</span>
                                        </div>
                                        <span class="text-sm text-secondary-600">E-Wallet</span>
                                    </div>
                                    <div class="p-4 border border-primary-200 rounded-lg text-center hover:border-primary-400 transition duration-300">
                                        <div class="w-12 h-8 bg-purple-600 rounded mx-auto mb-2 flex items-center justify-center">
                                            <span class="text-white text-xs font-bold">QRIS</span>
                                        </div>
                                        <span class="text-sm text-secondary-600">QR Code</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Payment Security -->
                            <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
                                <div class="flex items-start">
                                    <svg class="w-5 h-5 text-green-600 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.707-4.293a1 1 0 010 1.414L9.414 18.707a1 1 0 01-1.414 0L3.293 14a1 1 0 011.414-1.414L9 17.293l11.293-11.293a1 1 0 011.414 0z"></path>
                                    </svg>
                                    <div>
                                        <h4 class="font-medium text-green-800 mb-1">Secure Payment</h4>
                                        <p class="text-sm text-green-700">Your payment is secured by Midtrans with 256-bit SSL encryption. We never store your payment information.</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Payment Terms -->
                            <div class="bg-primary-50 border border-primary-200 rounded-lg p-4 mb-6">
                                <h4 class="font-medium text-secondary-900 mb-2">Payment Terms</h4>
                                <ul class="text-sm text-secondary-700 space-y-1">
                                    <li>• Payment must be completed within 24 hours</li>
                                    <li>• Booking will be automatically cancelled if payment is not received</li>
                                    <li>• Refunds are processed within 3-5 business days</li>
                                    <li>• Additional charges may apply for early check-in or late check-out</li>
                                </ul>
                            </div>

                            <!-- Payment Button -->
                            <form method="POST" action="{{ route('payments.store') }}">
                                @csrf
                                <input type="hidden" name="booking_id" value="{{ $booking->id }}">
                                
                                <div class="flex items-center justify-between pt-6 border-t border-primary-100">
                                    <a href="{{ route('bookings.show', $booking) }}" 
                                       class="inline-flex items-center px-6 py-3 border border-secondary-300 text-secondary-700 font-medium rounded-lg hover:bg-secondary-50 transition duration-300">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                        </svg>
                                        Back to Booking
                                    </a>
                                    
                                    <button type="submit" 
                                            class="inline-flex items-center px-8 py-3 bg-primary-600 text-white font-semibold rounded-lg hover:bg-primary-700 transition duration-300">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v2"></path>
                                        </svg>
                                        Proceed to Payment
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Booking Summary -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-xl shadow-sm border border-primary-100 p-6 sticky top-8">
                        <h3 class="text-lg font-semibold text-secondary-900 mb-4">Booking Summary</h3>
                        
                        <!-- Room Image -->
                        <div class="mb-4">
                            <div class="h-32 bg-gradient-to-br from-primary-100 to-primary-200 rounded-lg overflow-hidden">
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
                        </div>

                        <!-- Booking Details -->
                        <div class="space-y-3 mb-6">
                            <h4 class="font-medium text-secondary-900">{{ $booking->roomType->name }}</h4>
                            <div class="text-sm text-secondary-600 space-y-2">
                                <div class="flex justify-between">
                                    <span>Booking Code:</span>
                                    <span class="font-mono text-secondary-900">{{ $booking->booking_code }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span>Check-in:</span>
                                    <span class="text-secondary-900">{{ $booking->check_in_date->format('M j, Y') }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span>Check-out:</span>
                                    <span class="text-secondary-900">{{ $booking->check_out_date->format('M j, Y') }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span>Duration:</span>
                                    <span class="text-secondary-900">{{ $booking->nights }} {{ $booking->nights == 1 ? 'night' : 'nights' }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span>Guests:</span>
                                    <span class="text-secondary-900">{{ $booking->guests }} {{ $booking->guests == 1 ? 'guest' : 'guests' }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span>Room:</span>
                                    <span class="text-secondary-900">{{ $booking->room ? $booking->room->room_number : 'TBA' }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Pricing Breakdown -->
                        <div class="border-t border-primary-100 pt-4">
                            <div class="space-y-2 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-secondary-600">Room Rate</span>
                                    <span class="text-secondary-900">Rp {{ number_format($booking->roomType->price_per_night, 0, ',', '.') }}/night</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-secondary-600">{{ $booking->nights }} {{ $booking->nights == 1 ? 'night' : 'nights' }}</span>
                                    <span class="text-secondary-900">Rp {{ number_format($booking->total_amount, 0, ',', '.') }}</span>
                                </div>
                                <div class="flex justify-between pt-2 border-t border-primary-100">
                                    <span class="font-medium text-secondary-900">Total Amount</span>
                                    <span class="font-bold text-primary-600 text-lg">Rp {{ number_format($booking->total_amount, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Payment Deadline -->
                        <div class="mt-6 pt-6 border-t border-primary-100">
                            <div class="flex items-center p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                                <svg class="w-5 h-5 text-yellow-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <div>
                                    <div class="text-sm font-medium text-yellow-800">Payment Deadline</div>
                                    <div class="text-sm text-yellow-700">{{ $booking->created_at->addDay()->format('M j, Y H:i') }}</div>
                                </div>
                            </div>
                        </div>

                        <!-- Contact Support -->
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
                                    support@aoragrand.com
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 