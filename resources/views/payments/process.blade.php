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
                    <div id="loading-state" class="mb-8">
                        <div class="inline-flex items-center justify-center w-16 h-16 bg-primary-100 rounded-full mb-4">
                            <svg class="w-8 h-8 text-primary-600 animate-spin" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-secondary-900 mb-2">Preparing Payment Gateway</h3>
                        <p class="text-secondary-600">Please wait while we redirect you to the secure payment page...</p>
                    </div>

                    <!-- Payment Instructions -->
                    <div class="max-w-2xl mx-auto">
                        <div class="bg-primary-50 border border-primary-200 rounded-lg p-6 mb-6">
                            <h4 class="font-medium text-secondary-900 mb-3">Payment Instructions</h4>
                            <div class="text-sm text-secondary-700 space-y-2 text-left">
                                <p>1. You will be redirected to Midtrans secure payment page</p>
                                <p>2. Choose your preferred payment method</p>
                                <p>3. Complete the payment process</p>
                                <p>4. You will receive a confirmation email once payment is successful</p>
                            </div>
                        </div>

                        <!-- Payment Details -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm">
                            <div class="text-left">
                                <h5 class="font-medium text-secondary-900 mb-2">Booking Details</h5>
                                <div class="space-y-1 text-secondary-600">
                                    <div>Booking Code: <span class="font-mono text-secondary-900">{{ $payment->booking->booking_code }}</span></div>
                                    <div>Room: <span class="text-secondary-900">{{ $payment->booking->roomType->name }}</span></div>
                                    <div>Check-in: <span class="text-secondary-900">{{ $payment->booking->check_in_date->format('M j, Y') }}</span></div>
                                    <div>Check-out: <span class="text-secondary-900">{{ $payment->booking->check_out_date->format('M j, Y') }}</span></div>
                                </div>
                            </div>
                            <div class="text-left">
                                <h5 class="font-medium text-secondary-900 mb-2">Payment Details</h5>
                                <div class="space-y-1 text-secondary-600">
                                    <div>Payment Code: <span class="font-mono text-secondary-900">{{ $payment->payment_code }}</span></div>
                                    <div>Amount: <span class="text-secondary-900">Rp {{ number_format($payment->amount, 0, ',', '.') }}</span></div>
                                    <div>Status: <span class="text-yellow-600 font-medium">Pending</span></div>
                                    <div>Expires: <span class="text-secondary-900">{{ $payment->expired_at->format('M j, Y H:i') }}</span></div>
                                </div>
                            </div>
                        </div>

                        <!-- Manual Payment Button (fallback) -->
                        <div class="mt-8">
                            <button id="pay-button" 
                                    class="inline-flex items-center px-8 py-3 bg-primary-600 text-white font-semibold rounded-lg hover:bg-primary-700 transition duration-300"
                                    style="display: none;">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v2"></path>
                                </svg>
                                Open Payment Gateway
                            </button>
                        </div>

                        <!-- Back to Booking -->
                        <div class="mt-6">
                            <a href="{{ route('bookings.show', $payment->booking) }}" 
                               class="inline-flex items-center text-secondary-600 hover:text-primary-600 font-medium">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                </svg>
                                Back to Booking Details
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Midtrans Snap Script -->
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
    
    <script>
        // Auto-trigger payment when page loads
        document.addEventListener('DOMContentLoaded', function() {
            // Show manual button after 3 seconds as fallback
            setTimeout(function() {
                document.getElementById('loading-state').style.display = 'none';
                document.getElementById('pay-button').style.display = 'inline-flex';
            }, 3000);

            // Auto-trigger payment
            setTimeout(function() {
                triggerPayment();
            }, 1500);
        });

        function triggerPayment() {
            const snapToken = @json($midtransResponse['token'] ?? null);
            
            if (snapToken) {
                snap.pay(snapToken, {
                    onSuccess: function(result) {
                        console.log('Payment success:', result);
                        // Redirect to success page
                        window.location.href = '{{ route("payments.success", $payment) }}';
                    },
                    onPending: function(result) {
                        console.log('Payment pending:', result);
                        // Show pending message
                        alert('Payment is being processed. You will receive confirmation once payment is completed.');
                        window.location.href = '{{ route("payments.show", $payment) }}';
                    },
                    onError: function(result) {
                        console.log('Payment error:', result);
                        alert('Payment failed. Please try again.');
                        window.location.href = '{{ route("payments.create", $payment->booking) }}';
                    },
                    onClose: function() {
                        console.log('Payment popup closed');
                        // User closed the popup
                        alert('Payment was cancelled. You can try again anytime.');
                        window.location.href = '{{ route("payments.show", $payment) }}';
                    }
                });
            } else {
                console.error('No snap token available');
                document.getElementById('loading-state').innerHTML = `
                    <div class="text-red-600">
                        <svg class="w-16 h-16 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <h3 class="text-lg font-semibold mb-2">Payment Gateway Error</h3>
                        <p>Unable to initialize payment. Please try again.</p>
                        <a href="{{ route('payments.create', $payment->booking) }}" class="inline-block mt-4 px-6 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700">
                            Try Again
                        </a>
                    </div>
                `;
            }
        }

        // Manual payment button click
        document.getElementById('pay-button').addEventListener('click', function() {
            triggerPayment();
        });
    </script>
</x-app-layout> 