<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-secondary-800 leading-tight">
                    Payment Successful!
                </h2>
                <p class="text-sm text-secondary-600 mt-1">
                    Your booking has been confirmed
                </p>
            </div>
            <div class="text-right">
                <div class="text-2xl font-bold text-green-600">
                    Rp {{ number_format($payment->amount, 0, ',', '.') }}
                </div>
                <div class="text-sm text-secondary-500">Paid Successfully</div>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Success Message -->
            <div class="bg-green-50 border border-green-200 rounded-xl p-8 mb-8 text-center">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-green-100 rounded-full mb-4">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-green-800 mb-2">Payment Successful!</h3>
                <p class="text-green-700 mb-4">Your booking has been confirmed and you will receive a confirmation email shortly.</p>
                <div class="flex items-center justify-center space-x-4 text-sm text-green-600">
                    <div class="flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        Email confirmation sent
                    </div>
                    <div class="flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Booking confirmed
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Receipt -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-xl shadow-sm border border-primary-100 overflow-hidden">
                        <div class="px-6 py-4 border-b border-primary-100 bg-gradient-to-r from-primary-50 to-white">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-semibold text-secondary-900">Payment Receipt</h3>
                                <span class="px-3 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-full">
                                    Paid
                                </span>
                            </div>
                        </div>
                        
                        <div class="p-6">
                            <!-- Hotel Header -->
                            <div class="text-center mb-6 pb-6 border-b border-primary-100">
                                <div class="flex items-center justify-center mb-2">
                                    <div class="w-12 h-12 bg-gradient-to-br from-primary-500 to-primary-700 rounded-lg flex items-center justify-center mr-3">
                                        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.84L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.84l-7-3z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h2 class="text-2xl font-bold bg-gradient-to-r from-primary-600 to-primary-800 bg-clip-text text-transparent">
                                            AoraGrand Hotel
                                        </h2>
                                        <p class="text-sm text-secondary-600">Luxury Hotel & Resort</p>
                                    </div>
                                </div>
                                <div class="text-sm text-secondary-600">
                                    <p>Jl. Sudirman No. 123, Jakarta Pusat 10110</p>
                                    <p>Phone: +62 21 1234 5678 | Email: info@aoragrand.com</p>
                                </div>
                            </div>

                            <!-- Payment Information -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                <div>
                                    <h4 class="font-medium text-secondary-900 mb-3">Payment Details</h4>
                                    <div class="space-y-2 text-sm">
                                        <div class="flex justify-between">
                                            <span class="text-secondary-600">Payment Code:</span>
                                            <span class="font-mono text-secondary-900">{{ $payment->payment_code }}</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-secondary-600">Transaction ID:</span>
                                            <span class="font-mono text-secondary-900">{{ $payment->midtrans_transaction_id ?? 'N/A' }}</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-secondary-600">Payment Method:</span>
                                            <span class="text-secondary-900">{{ $payment->payment_method ?? 'Midtrans' }}</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-secondary-600">Payment Date:</span>
                                            <span class="text-secondary-900">{{ $payment->paid_at ? $payment->paid_at->format('M j, Y H:i') : now()->format('M j, Y H:i') }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <h4 class="font-medium text-secondary-900 mb-3">Guest Information</h4>
                                    <div class="space-y-2 text-sm">
                                        <div class="flex justify-between">
                                            <span class="text-secondary-600">Guest Name:</span>
                                            <span class="text-secondary-900">{{ $payment->booking->user->name }}</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-secondary-600">Email:</span>
                                            <span class="text-secondary-900">{{ $payment->booking->user->email }}</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-secondary-600">Phone:</span>
                                            <span class="text-secondary-900">{{ $payment->booking->user->phone ?? 'N/A' }}</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-secondary-600">Number of Guests:</span>
                                            <span class="text-secondary-900">{{ $payment->booking->guests }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Booking Information -->
                            <div class="mb-6">
                                <h4 class="font-medium text-secondary-900 mb-3">Booking Information</h4>
                                <div class="bg-primary-50 rounded-lg p-4">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                                        <div>
                                            <span class="text-secondary-600">Booking Code:</span>
                                            <div class="font-mono text-secondary-900 font-medium">{{ $payment->booking->booking_code }}</div>
                                        </div>
                                        <div>
                                            <span class="text-secondary-600">Room Type:</span>
                                            <div class="text-secondary-900 font-medium">{{ $payment->booking->roomType->name }}</div>
                                        </div>
                                        <div>
                                            <span class="text-secondary-600">Check-in Date:</span>
                                            <div class="text-secondary-900 font-medium">{{ $payment->booking->check_in_date->format('l, M j, Y') }}</div>
                                        </div>
                                        <div>
                                            <span class="text-secondary-600">Check-out Date:</span>
                                            <div class="text-secondary-900 font-medium">{{ $payment->booking->check_out_date->format('l, M j, Y') }}</div>
                                        </div>
                                        <div>
                                            <span class="text-secondary-600">Duration:</span>
                                            <div class="text-secondary-900 font-medium">{{ $payment->booking->nights }} {{ $payment->booking->nights == 1 ? 'night' : 'nights' }}</div>
                                        </div>
                                        <div>
                                            <span class="text-secondary-600">Room Number:</span>
                                            <div class="text-secondary-900 font-medium">{{ $payment->booking->room ? $payment->booking->room->room_number : 'Will be assigned' }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Amount Breakdown -->
                            <div class="border-t border-primary-100 pt-4">
                                <h4 class="font-medium text-secondary-900 mb-3">Amount Breakdown</h4>
                                <div class="space-y-2 text-sm">
                                    <div class="flex justify-between">
                                        <span class="text-secondary-600">Room Rate (per night)</span>
                                        <span class="text-secondary-900">Rp {{ number_format($payment->booking->roomType->price_per_night, 0, ',', '.') }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-secondary-600">Number of Nights</span>
                                        <span class="text-secondary-900">{{ $payment->booking->nights }}</span>
                                    </div>
                                    <div class="flex justify-between border-t border-primary-100 pt-2">
                                        <span class="font-medium text-secondary-900">Total Amount</span>
                                        <span class="font-bold text-primary-600 text-lg">Rp {{ number_format($payment->amount, 0, ',', '.') }}</span>
                                    </div>
                                </div>
                            </div>

                            @if($payment->booking->special_requests)
                                <div class="mt-6 pt-6 border-t border-primary-100">
                                    <h4 class="font-medium text-secondary-900 mb-2">Special Requests</h4>
                                    <p class="text-sm text-secondary-700 bg-primary-50 p-3 rounded-lg">{{ $payment->booking->special_requests }}</p>
                                </div>
                            @endif

                            <!-- Footer -->
                            <div class="mt-8 pt-6 border-t border-primary-100 text-center text-sm text-secondary-600">
                                <p>Thank you for choosing AoraGrand Hotel!</p>
                                <p>For any inquiries, please contact us at +62 21 1234 5678 or info@aoragrand.com</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-xl shadow-sm border border-primary-100 p-6 sticky top-8">
                        <h3 class="text-lg font-semibold text-secondary-900 mb-4">What's Next?</h3>
                        
                        <div class="space-y-4">
                            <!-- Download Receipt -->
                            <a href="#" onclick="window.print()" 
                               class="w-full inline-flex items-center justify-center px-4 py-3 bg-primary-600 text-white font-medium rounded-lg hover:bg-primary-700 transition duration-300">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                Print Receipt
                            </a>

                            <!-- View Booking -->
                            <a href="{{ route('bookings.show', $payment->booking) }}" 
                               class="w-full inline-flex items-center justify-center px-4 py-3 border border-primary-600 text-primary-600 font-medium rounded-lg hover:bg-primary-50 transition duration-300">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                View Booking Details
                            </a>

                            <!-- My Bookings -->
                            <a href="{{ route('bookings.index') }}" 
                               class="w-full inline-flex items-center justify-center px-4 py-3 border border-secondary-300 text-secondary-700 font-medium rounded-lg hover:bg-secondary-50 transition duration-300">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                My Bookings
                            </a>

                            <!-- Book Another Room -->
                            <a href="{{ route('rooms.index') }}" 
                               class="w-full inline-flex items-center justify-center px-4 py-3 border border-secondary-300 text-secondary-700 font-medium rounded-lg hover:bg-secondary-50 transition duration-300">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-4m-5 0H3m2 0h3M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                                Book Another Room
                            </a>
                        </div>

                        <!-- Important Information -->
                        <div class="mt-6 pt-6 border-t border-primary-100">
                            <h4 class="font-medium text-secondary-900 mb-3">Important Information</h4>
                            <div class="space-y-3 text-sm text-secondary-600">
                                <div class="flex items-start">
                                    <svg class="w-4 h-4 mr-2 mt-0.5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <div>
                                        <div class="font-medium">Check-in Time</div>
                                        <div>2:00 PM onwards</div>
                                    </div>
                                </div>
                                <div class="flex items-start">
                                    <svg class="w-4 h-4 mr-2 mt-0.5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <div>
                                        <div class="font-medium">Check-out Time</div>
                                        <div>12:00 PM</div>
                                    </div>
                                </div>
                                <div class="flex items-start">
                                    <svg class="w-4 h-4 mr-2 mt-0.5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path>
                                    </svg>
                                    <div>
                                        <div class="font-medium">Valid ID Required</div>
                                        <div>Please bring valid identification</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        @media print {
            .sticky { position: static !important; }
            .lg\:col-span-1 { display: none !important; }
            .lg\:col-span-2 { grid-column: span 3 !important; }
        }
    </style>
</x-app-layout> 