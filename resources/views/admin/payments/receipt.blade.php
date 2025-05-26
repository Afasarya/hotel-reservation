<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-secondary-800 leading-tight">
                    Payment Receipt: {{ $payment->payment_code }}
                </h2>
                <p class="text-sm text-secondary-600 mt-1">
                    {{ $payment->booking->roomType->name }} • {{ $payment->booking->check_in_date->format('M j') }} - {{ $payment->booking->check_out_date->format('M j, Y') }}
                </p>
            </div>
            <div class="flex items-center space-x-3">
                <button onclick="window.print()" class="inline-flex items-center px-4 py-2 bg-secondary-600 text-white font-medium rounded-lg hover:bg-secondary-700 transition duration-300">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                    </svg>
                    Print Receipt
                </button>
                <a href="{{ route('admin.payments.show', $payment) }}" class="inline-flex items-center px-4 py-2 text-secondary-600 font-medium rounded-lg border border-secondary-300 hover:bg-secondary-50 transition duration-300">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Payment
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8 print:py-0">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 print:px-0">
            <div class="bg-white rounded-xl shadow-sm border border-primary-100 overflow-hidden print:shadow-none print:border-0">
                <div class="px-6 py-4 border-b border-primary-100 bg-gradient-to-r from-primary-50 to-white print:hidden">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-secondary-900">Payment Receipt</h3>
                        <span class="px-3 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-full">
                            {{ ucfirst($payment->status) }}
                        </span>
                    </div>
                </div>
                
                <div class="p-6 print:p-0">
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

                    <div class="text-center mb-6">
                        <h1 class="text-xl font-bold text-secondary-900 mb-1">OFFICIAL RECEIPT</h1>
                        <p class="text-secondary-600">{{ $payment->created_at->format('F j, Y') }}</p>
                    </div>

                    <!-- Payment Information -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <h4 class="font-medium text-secondary-900 mb-3">Payment Details</h4>
                            <div class="space-y-2 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-secondary-600">Receipt Number:</span>
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
                                    <span class="text-secondary-900">{{ $payment->paid_at ? $payment->paid_at->format('M j, Y H:i') : $payment->created_at->format('M j, Y H:i') }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-secondary-600">Status:</span>
                                    <span class="font-medium 
                                        @if($payment->status === 'paid') text-green-600
                                        @elseif($payment->status === 'pending') text-yellow-600
                                        @else text-red-600
                                        @endif">
                                        {{ ucfirst($payment->status) }}
                                    </span>
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
                        <div class="bg-primary-50 rounded-lg p-4 print:bg-white print:border print:border-secondary-200">
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
                    <div class="border-t border-primary-100 pt-4 mb-8">
                        <h4 class="font-medium text-secondary-900 mb-3">Amount Breakdown</h4>
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="border-b border-primary-100">
                                    <th class="text-left py-2 text-secondary-600">Description</th>
                                    <th class="text-right py-2 text-secondary-600">Price</th>
                                    <th class="text-right py-2 text-secondary-600">Quantity</th>
                                    <th class="text-right py-2 text-secondary-600">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="py-2 text-secondary-900">
                                        {{ $payment->booking->roomType->name }} <br>
                                        <span class="text-xs text-secondary-600">{{ $payment->booking->check_in_date->format('M j') }} - {{ $payment->booking->check_out_date->format('M j, Y') }}</span>
                                    </td>
                                    <td class="py-2 text-right text-secondary-900">Rp {{ number_format($payment->booking->roomType->price_per_night, 0, ',', '.') }}</td>
                                    <td class="py-2 text-right text-secondary-900">{{ $payment->booking->nights }}</td>
                                    <td class="py-2 text-right text-secondary-900">Rp {{ number_format($payment->booking->roomType->price_per_night * $payment->booking->nights, 0, ',', '.') }}</td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr class="border-t border-primary-200">
                                    <th colspan="3" class="text-left py-3 font-medium text-secondary-900">Total Amount</th>
                                    <th class="text-right py-3 font-bold text-primary-600">Rp {{ number_format($payment->amount, 0, ',', '.') }}</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    @if($payment->booking->special_requests)
                        <div class="mt-6 pt-6 border-t border-primary-100">
                            <h4 class="font-medium text-secondary-900 mb-2">Special Requests</h4>
                            <p class="text-sm text-secondary-700 bg-primary-50 p-3 rounded-lg print:bg-white print:border print:border-secondary-200">{{ $payment->booking->special_requests }}</p>
                        </div>
                    @endif

                    <!-- Footer -->
                    <div class="mt-8 pt-6 border-t border-primary-100 text-center text-sm text-secondary-600">
                        <p>Thank you for choosing AoraGrand Hotel!</p>
                        <p>For any inquiries, please contact us at +62 21 1234 5678 or info@aoragrand.com</p>
                        <p class="mt-4 text-xs text-secondary-400">This receipt was generated on {{ now()->format('F j, Y \a\t H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        @media print {
            body * {
                visibility: hidden;
            }
            .app-content, .app-content * {
                visibility: visible;
            }
            .print\:hidden {
                display: none !important;
            }
            .app-content {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
            }
            html, body {
                margin: 0;
                padding: 0;
            }
            @page {
                size: A4;
                margin: 1cm;
            }
        }
    </style>
</x-app-layout> 