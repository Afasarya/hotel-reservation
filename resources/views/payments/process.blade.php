<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-secondary-800 leading-tight">
                    Processing Payment
                </h2>
                <p class="text-sm text-secondary-600 mt-1">
                    Payment Code: {{ $payment->payment_code }}
                </p>
            </div>
            <div class="text-right">
                <div class="text-2xl font-bold text-primary-600">
                    Rp {{ number_format($payment->amount, 0, ',', '.') }}
                </div>
                <div class="text-sm text-secondary-500">Total Amount</div>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-xl shadow-sm border border-primary-100 overflow-hidden">
                <div class="p-8 text-center">
                    <!-- Loading State -->
                    <div id="loading-state" class="space-y-6">
                        <div class="w-16 h-16 bg-gradient-to-br from-primary-500 to-primary-700 rounded-full flex items-center justify-center mx-auto">
                            <svg class="w-8 h-8 text-white animate-spin" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </div>
                        
                        <div>
                            <h3 class="text-xl font-semibold text-secondary-900 mb-2">Preparing Your Payment</h3>
                            <p class="text-secondary-600">Please wait while we set up your secure payment...</p>
                        </div>
                    </div>

                    <!-- Payment Ready State -->
                    <div id="payment-ready" class="space-y-6 hidden">
                        <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-green-700 rounded-full flex items-center justify-center mx-auto">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        
                        <div>
                            <h3 class="text-xl font-semibold text-secondary-900 mb-2">Payment Ready</h3>
                            <p class="text-secondary-600 mb-4">Click the button below to proceed with your payment</p>
                            <button id="pay-button" class="inline-flex items-center px-6 py-3 bg-primary-600 text-white font-medium rounded-lg hover:bg-primary-700 transition duration-300">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                </svg>
                                Pay Now - Rp {{ number_format($payment->amount, 0, ',', '.') }}
                            </button>
                        </div>
                    </div>

                    <!-- Error State -->
                    <div id="error-state" class="space-y-6 hidden">
                        <div class="w-16 h-16 bg-gradient-to-br from-red-500 to-red-700 rounded-full flex items-center justify-center mx-auto">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </div>
                        
                        <div>
                            <h3 class="text-xl font-semibold text-secondary-900 mb-2">Payment Setup Failed</h3>
                            <p class="text-secondary-600 mb-4">There was an error setting up your payment. Please try again.</p>
                            <div class="space-x-3">
                                <a href="{{ route('payments.create', $payment->booking) }}" class="inline-flex items-center px-4 py-2 bg-primary-600 text-white font-medium rounded-lg hover:bg-primary-700 transition duration-300">
                                    Try Again
                                </a>
                                <a href="{{ route('bookings.show', $payment->booking) }}" class="inline-flex items-center px-4 py-2 text-secondary-600 font-medium rounded-lg border border-secondary-300 hover:bg-secondary-50 transition duration-300">
                                    Back to Booking
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payment Information -->
                <div class="border-t border-primary-100 bg-primary-50 p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h4 class="font-medium text-secondary-900 mb-3">Booking Details</h4>
                            <div class="space-y-2 text-sm text-secondary-600">
                                <div><span class="font-medium">Booking Code:</span> {{ $payment->booking->booking_code }}</div>
                                <div><span class="font-medium">Room Type:</span> {{ $payment->booking->roomType->name }}</div>
                                <div><span class="font-medium">Check-in:</span> {{ $payment->booking->check_in_date->format('M j, Y') }}</div>
                                <div><span class="font-medium">Check-out:</span> {{ $payment->booking->check_out_date->format('M j, Y') }}</div>
                                <div><span class="font-medium">Nights:</span> {{ $payment->booking->nights }}</div>
                            </div>
                        </div>
                        
                        <div>
                            <h4 class="font-medium text-secondary-900 mb-3">Payment Information</h4>
                            <div class="space-y-2 text-sm text-secondary-600">
                                <div><span class="font-medium">Payment Code:</span> {{ $payment->payment_code }}</div>
                                <div><span class="font-medium">Amount:</span> Rp {{ number_format($payment->amount, 0, ',', '.') }}</div>
                                <div><span class="font-medium">Status:</span> 
                                    <span class="px-2 py-1 text-xs font-medium bg-yellow-100 text-yellow-800 rounded-full">
                                        {{ ucfirst($payment->status) }}
                                    </span>
                                </div>
                                <div><span class="font-medium">Expires:</span> {{ $payment->expired_at->format('M j, Y g:i A') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Midtrans Snap Script -->
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Check if we have the necessary data
            @if(isset($midtransResponse) && isset($midtransResponse['token']))
                const snapToken = '{{ $midtransResponse["token"] }}';
                
                // Hide loading and show payment ready
                setTimeout(function() {
                    document.getElementById('loading-state').classList.add('hidden');
                    document.getElementById('payment-ready').classList.remove('hidden');
                }, 1500);

                // Set up pay button click handler
                document.getElementById('pay-button').addEventListener('click', function() {
                    snap.pay(snapToken, {
                        onSuccess: function(result) {
                            console.log('Payment success:', result);
                            // Redirect to success page
                            window.location.href = '{{ route("payments.success", $payment) }}';
                        },
                        onPending: function(result) {
                            console.log('Payment pending:', result);
                            // Show pending message or redirect
                            alert('Payment is being processed. Please wait for confirmation.');
                            window.location.href = '{{ route("payments.show", $payment) }}';
                        },
                        onError: function(result) {
                            console.log('Payment error:', result);
                            alert('Payment failed. Please try again.');
                        },
                        onClose: function() {
                            console.log('Payment popup closed');
                            // User closed the popup without completing payment
                        }
                    });
                });

                // Auto-trigger payment after 3 seconds (optional)
                setTimeout(function() {
                    if (confirm('Ready to proceed with payment?')) {
                        document.getElementById('pay-button').click();
                    }
                }, 3000);

            @else
                // Show error if no token
                setTimeout(function() {
                    document.getElementById('loading-state').classList.add('hidden');
                    document.getElementById('error-state').classList.remove('hidden');
                }, 1500);
            @endif
        });
    </script>
</x-app-layout> 