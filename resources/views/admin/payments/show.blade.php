<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-secondary-800 leading-tight">
                    Payment Details: {{ $payment->payment_code }}
                </h2>
                <p class="text-sm text-secondary-600 mt-1">
                    {{ $payment->created_at->format('F j, Y') }} • {{ $payment->booking->roomType->name }}
                </p>
            </div>
            <div class="flex items-center space-x-3">
                <span class="px-3 py-1 text-sm font-medium rounded-full
                    @if($payment->status === 'paid') bg-green-100 text-green-800
                    @elseif($payment->status === 'pending') bg-yellow-100 text-yellow-800
                    @elseif($payment->status === 'expired') bg-gray-100 text-gray-800
                    @else bg-red-100 text-red-800
                    @endif">
                    {{ ucfirst($payment->status) }}
                </span>
                <a href="{{ route('admin.payments.index') }}" class="inline-flex items-center px-4 py-2 text-secondary-600 font-medium rounded-lg border border-secondary-300 hover:bg-secondary-50 transition duration-300">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Payments
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Payment Information -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-xl shadow-sm border border-primary-100 overflow-hidden">
                        <div class="px-6 py-4 border-b border-primary-100 bg-primary-50">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-semibold text-secondary-900">Payment Information</h3>
                                <a href="{{ route('admin.payments.receipt', $payment) }}" class="inline-flex items-center px-3 py-1 bg-secondary-600 text-white text-xs font-medium rounded hover:bg-secondary-700 transition duration-300">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    View Receipt
                                </a>
                            </div>
                        </div>
                        
                        <div class="p-6">
                            <!-- Payment Details -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                <div>
                                    <h4 class="font-medium text-secondary-900 mb-3">Payment Details</h4>
                                    <div class="space-y-3 text-sm">
                                        <div class="flex justify-between">
                                            <span class="text-secondary-600">Payment Code:</span>
                                            <span class="font-mono font-medium text-secondary-900">{{ $payment->payment_code }}</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-secondary-600">Amount:</span>
                                            <span class="font-medium text-secondary-900">Rp {{ number_format($payment->amount, 0, ',', '.') }}</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-secondary-600">Status:</span>
                                            <span class="px-2 py-0.5 text-xs font-medium rounded-full
                                                @if($payment->status === 'paid') bg-green-100 text-green-800
                                                @elseif($payment->status === 'pending') bg-yellow-100 text-yellow-800
                                                @elseif($payment->status === 'expired') bg-gray-100 text-gray-800
                                                @else bg-red-100 text-red-800
                                                @endif">
                                                {{ ucfirst($payment->status) }}
                                            </span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-secondary-600">Created At:</span>
                                            <span class="text-secondary-900">{{ $payment->created_at->format('M j, Y H:i') }}</span>
                                        </div>
                                        @if($payment->paid_at)
                                        <div class="flex justify-between">
                                            <span class="text-secondary-600">Paid At:</span>
                                            <span class="text-secondary-900">{{ $payment->paid_at->format('M j, Y H:i') }}</span>
                                        </div>
                                        @endif
                                        @if($payment->expired_at)
                                        <div class="flex justify-between">
                                            <span class="text-secondary-600">Expires At:</span>
                                            <span class="text-secondary-900">{{ $payment->expired_at->format('M j, Y H:i') }}</span>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                                
                                <div>
                                    <h4 class="font-medium text-secondary-900 mb-3">Payment Method</h4>
                                    <div class="space-y-3 text-sm">
                                        <div class="flex justify-between">
                                            <span class="text-secondary-600">Payment Method:</span>
                                            <span class="text-secondary-900">{{ $payment->payment_method ?? 'Midtrans' }}</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-secondary-600">Midtrans Order ID:</span>
                                            <span class="font-mono text-secondary-900">{{ $payment->midtrans_order_id ?? 'N/A' }}</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-secondary-600">Transaction ID:</span>
                                            <span class="font-mono text-secondary-900">{{ $payment->midtrans_transaction_id ?? 'N/A' }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="border-t border-primary-100 pt-6 mb-6">
                                <h4 class="font-medium text-secondary-900 mb-3">Guest Information</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div class="space-y-3 text-sm">
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
                                            <span class="text-secondary-900">{{ $payment->booking->user->phone ?? 'Not provided' }}</span>
                                        </div>
                                    </div>
                                    <div class="space-y-3 text-sm">
                                        <div class="flex justify-between">
                                            <span class="text-secondary-600">Number of Guests:</span>
                                            <span class="text-secondary-900">{{ $payment->booking->guests }}</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-secondary-600">Total Nights:</span>
                                            <span class="text-secondary-900">{{ $payment->booking->nights }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Booking Information -->
                            <div class="border-t border-primary-100 pt-6">
                                <h4 class="font-medium text-secondary-900 mb-3">Booking Information</h4>
                                <div class="bg-primary-50 rounded-lg p-4 mb-4">
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
                                            <span class="text-secondary-600">Room Number:</span>
                                            <div class="text-secondary-900 font-medium">
                                                {{ $payment->booking->room ? $payment->booking->room->room_number : 'Not assigned yet' }}
                                            </div>
                                        </div>
                                        <div>
                                            <span class="text-secondary-600">Booking Status:</span>
                                            <div>
                                                <span class="px-2 py-1 text-xs font-medium rounded-full
                                                    @if($payment->booking->status === 'confirmed') bg-green-100 text-green-800
                                                    @elseif($payment->booking->status === 'pending') bg-yellow-100 text-yellow-800
                                                    @elseif($payment->booking->status === 'checked_in') bg-blue-100 text-blue-800
                                                    @elseif($payment->booking->status === 'checked_out') bg-purple-100 text-purple-800
                                                    @else bg-red-100 text-red-800
                                                    @endif">
                                                    {{ ucfirst(str_replace('_', ' ', $payment->booking->status)) }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Price Breakdown -->
                                <div class="space-y-2 text-sm mt-4">
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

                                @if($payment->booking->special_requests)
                                <div class="mt-6 pt-6 border-t border-primary-100">
                                    <h4 class="font-medium text-secondary-900 mb-2">Special Requests</h4>
                                    <p class="text-sm text-secondary-700 bg-primary-50 p-3 rounded-lg">{{ $payment->booking->special_requests }}</p>
                                </div>
                                @endif
                            </div>

                            <!-- Midtrans Response -->
                            @if($payment->midtrans_response)
                            <div class="mt-6 pt-6 border-t border-primary-100">
                                <h4 class="font-medium text-secondary-900 mb-3">Payment Gateway Response</h4>
                                <div class="bg-secondary-50 p-4 rounded-lg overflow-x-auto">
                                    <pre class="text-xs text-secondary-800">{{ json_encode($payment->midtrans_response, JSON_PRETTY_PRINT) }}</pre>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-xl shadow-sm border border-primary-100 p-6 sticky top-8">
                        <h3 class="text-lg font-semibold text-secondary-900 mb-4">Quick Actions</h3>
                        
                        <div class="space-y-4">
                            <!-- View Booking Details -->
                            <a href="{{ route('admin.bookings.show', $payment->booking) }}" 
                               class="w-full inline-flex items-center justify-center px-4 py-3 bg-primary-600 text-white font-medium rounded-lg hover:bg-primary-700 transition duration-300">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                View Booking Details
                            </a>

                            <!-- View Receipt -->
                            <a href="{{ route('admin.payments.receipt', $payment) }}" 
                               class="w-full inline-flex items-center justify-center px-4 py-3 border border-primary-600 text-primary-600 font-medium rounded-lg hover:bg-primary-50 transition duration-300">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                View Receipt
                            </a>

                            <!-- View All Payments -->
                            <a href="{{ route('admin.payments.index') }}" 
                               class="w-full inline-flex items-center justify-center px-4 py-3 border border-secondary-300 text-secondary-700 font-medium rounded-lg hover:bg-secondary-50 transition duration-300">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                </svg>
                                All Payments
                            </a>
                        </div>

                        <!-- Payment Status Info -->
                        <div class="mt-6 pt-6 border-t border-primary-100">
                            <h4 class="font-medium text-secondary-900 mb-3">Payment Status</h4>
                            <div class="p-4 
                                @if($payment->status === 'paid') bg-green-50 border border-green-200
                                @elseif($payment->status === 'pending') bg-yellow-50 border border-yellow-200
                                @elseif($payment->status === 'expired') bg-gray-50 border border-gray-200
                                @else bg-red-50 border border-red-200
                                @endif rounded-lg">
                                
                                <div class="flex items-center mb-2">
                                    <svg class="w-5 h-5 mr-2 
                                        @if($payment->status === 'paid') text-green-500
                                        @elseif($payment->status === 'pending') text-yellow-500 
                                        @elseif($payment->status === 'expired') text-gray-500
                                        @else text-red-500
                                        @endif" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        @if($payment->status === 'paid')
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        @elseif($payment->status === 'pending')
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        @elseif($payment->status === 'expired')
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        @else
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        @endif
                                    </svg>
                                    <span class="font-medium 
                                        @if($payment->status === 'paid') text-green-800
                                        @elseif($payment->status === 'pending') text-yellow-800
                                        @elseif($payment->status === 'expired') text-gray-800
                                        @else text-red-800
                                        @endif">
                                        {{ ucfirst($payment->status) }}
                                    </span>
                                </div>
                                
                                <p class="text-sm 
                                    @if($payment->status === 'paid') text-green-700
                                    @elseif($payment->status === 'pending') text-yellow-700
                                    @elseif($payment->status === 'expired') text-gray-700
                                    @else text-red-700
                                    @endif">
                                    @if($payment->status === 'paid')
                                        Payment was successfully processed on {{ $payment->paid_at ? $payment->paid_at->format('M j, Y \a\t H:i') : 'N/A' }}.
                                    @elseif($payment->status === 'pending')
                                        Payment is awaiting confirmation from payment gateway.
                                    @elseif($payment->status === 'expired')
                                        Payment has expired and the booking has been cancelled.
                                    @else
                                        Payment failed to process. Customer may need to try again with a different payment method.
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 