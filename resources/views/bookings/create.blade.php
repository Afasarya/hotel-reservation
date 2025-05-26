<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-secondary-800 leading-tight">
                    Book {{ $roomType->name }}
                </h2>
                <p class="text-sm text-secondary-600 mt-1">
                    Complete your reservation details
                </p>
            </div>
            <div class="text-right">
                <div class="text-2xl font-bold text-primary-600">
                    Rp {{ number_format($roomType->price_per_night, 0, ',', '.') }}
                </div>
                <div class="text-sm text-secondary-500">per night</div>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Booking Form -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-xl shadow-sm border border-primary-100 overflow-hidden">
                        <div class="px-6 py-4 border-b border-primary-100">
                            <h3 class="text-lg font-semibold text-secondary-900">Reservation Details</h3>
                        </div>
                        
                        <form method="POST" action="{{ route('bookings.store') }}" class="p-6 space-y-6">
                            @csrf
                            <input type="hidden" name="room_type_id" value="{{ $roomType->id }}">

                            <!-- Check-in and Check-out Dates -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="check_in_date" class="block text-sm font-medium text-secondary-700 mb-2">
                                        Check-in Date
                                    </label>
                                    <input type="date" 
                                           name="check_in_date" 
                                           id="check_in_date"
                                           value="{{ old('check_in_date') }}"
                                           min="{{ now()->addDay()->format('Y-m-d') }}"
                                           class="w-full rounded-lg border-secondary-300 focus:border-primary-500 focus:ring-primary-500 @error('check_in_date') border-red-500 @enderror"
                                           required>
                                    @error('check_in_date')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="check_out_date" class="block text-sm font-medium text-secondary-700 mb-2">
                                        Check-out Date
                                    </label>
                                    <input type="date" 
                                           name="check_out_date" 
                                           id="check_out_date"
                                           value="{{ old('check_out_date') }}"
                                           min="{{ now()->addDays(2)->format('Y-m-d') }}"
                                           class="w-full rounded-lg border-secondary-300 focus:border-primary-500 focus:ring-primary-500 @error('check_out_date') border-red-500 @enderror"
                                           required>
                                    @error('check_out_date')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Number of Guests -->
                            <div>
                                <label for="guests" class="block text-sm font-medium text-secondary-700 mb-2">
                                    Number of Guests
                                </label>
                                <select name="guests" 
                                        id="guests" 
                                        class="w-full rounded-lg border-secondary-300 focus:border-primary-500 focus:ring-primary-500 @error('guests') border-red-500 @enderror"
                                        required>
                                    <option value="">Select number of guests</option>
                                    @for($i = 1; $i <= $roomType->capacity; $i++)
                                        <option value="{{ $i }}" {{ old('guests') == $i ? 'selected' : '' }}>
                                            {{ $i }} {{ $i == 1 ? 'Guest' : 'Guests' }}
                                        </option>
                                    @endfor
                                </select>
                                @error('guests')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-sm text-secondary-500">Maximum capacity: {{ $roomType->capacity }} guests</p>
                            </div>

                            <!-- Special Requests -->
                            <div>
                                <label for="special_requests" class="block text-sm font-medium text-secondary-700 mb-2">
                                    Special Requests (Optional)
                                </label>
                                <textarea name="special_requests" 
                                          id="special_requests"
                                          rows="4"
                                          placeholder="Any special requests or preferences..."
                                          class="w-full rounded-lg border-secondary-300 focus:border-primary-500 focus:ring-primary-500 @error('special_requests') border-red-500 @enderror">{{ old('special_requests') }}</textarea>
                                @error('special_requests')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Terms and Conditions -->
                            <div class="bg-primary-50 border border-primary-200 rounded-lg p-4">
                                <h4 class="font-medium text-secondary-900 mb-2">Booking Terms & Conditions</h4>
                                <ul class="text-sm text-secondary-700 space-y-1">
                                    <li>• Check-in time: 2:00 PM</li>
                                    <li>• Check-out time: 12:00 PM</li>
                                    <li>• Cancellation allowed up to 24 hours before check-in</li>
                                    <li>• Payment must be completed within 24 hours of booking</li>
                                    <li>• Valid ID required at check-in</li>
                                </ul>
                            </div>

                            <!-- Submit Button -->
                            <div class="flex items-center justify-between pt-6 border-t border-primary-100">
                                <a href="{{ route('rooms.show', $roomType) }}" 
                                   class="inline-flex items-center px-6 py-3 border border-secondary-300 text-secondary-700 font-medium rounded-lg hover:bg-secondary-50 transition duration-300">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                    </svg>
                                    Back to Room
                                </a>
                                
                                <button type="submit" 
                                        class="inline-flex items-center px-8 py-3 bg-primary-600 text-white font-semibold rounded-lg hover:bg-primary-700 transition duration-300">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    Continue to Payment
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Room Summary -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-xl shadow-sm border border-primary-100 p-6 sticky top-8">
                        <h3 class="text-lg font-semibold text-secondary-900 mb-4">Booking Summary</h3>
                        
                        <!-- Room Image -->
                        <div class="mb-4">
                            <div class="h-32 bg-gradient-to-br from-primary-100 to-primary-200 rounded-lg overflow-hidden">
                                @if($roomType->images && count($roomType->images) > 0)
                                    <img src="{{ Storage::url($roomType->images[0]) }}" 
                                         alt="{{ $roomType->name }}"
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

                        <!-- Room Details -->
                        <div class="space-y-3 mb-6">
                            <h4 class="font-medium text-secondary-900">{{ $roomType->name }}</h4>
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-secondary-600">Capacity</span>
                                <span class="font-medium text-secondary-900">{{ $roomType->capacity }} guests</span>
                            </div>
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-secondary-600">Price per night</span>
                                <span class="font-medium text-secondary-900">Rp {{ number_format($roomType->price_per_night, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        <!-- Pricing Calculation -->
                        <div class="border-t border-primary-100 pt-4">
                            <div id="pricing-details" class="space-y-2 text-sm">
                                <div class="flex items-center justify-between">
                                    <span class="text-secondary-600">Nights</span>
                                    <span id="nights-count" class="font-medium text-secondary-900">-</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-secondary-600">Subtotal</span>
                                    <span id="subtotal" class="font-medium text-secondary-900">-</span>
                                </div>
                                <div class="flex items-center justify-between pt-2 border-t border-primary-100">
                                    <span class="font-medium text-secondary-900">Total</span>
                                    <span id="total-amount" class="font-bold text-primary-600 text-lg">-</span>
                                </div>
                            </div>
                        </div>

                        <!-- Facilities Preview -->
                        @if($roomType->facilities && count($roomType->facilities) > 0)
                            <div class="mt-6 pt-6 border-t border-primary-100">
                                <h4 class="font-medium text-secondary-900 mb-3">Included Facilities</h4>
                                <div class="space-y-2">
                                    @foreach(array_slice($roomType->facilities, 0, 4) as $facility)
                                        <div class="flex items-center text-sm text-secondary-600">
                                            <svg class="w-4 h-4 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            {{ $facility }}
                                        </div>
                                    @endforeach
                                    @if(count($roomType->facilities) > 4)
                                        <div class="text-sm text-primary-600">
                                            +{{ count($roomType->facilities) - 4 }} more facilities
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const pricePerNight = {{ $roomType->price_per_night }};
        
        function calculateTotal() {
            const checkIn = document.getElementById('check_in_date').value;
            const checkOut = document.getElementById('check_out_date').value;
            
            if (checkIn && checkOut) {
                const checkInDate = new Date(checkIn);
                const checkOutDate = new Date(checkOut);
                const timeDiff = checkOutDate.getTime() - checkInDate.getTime();
                const nights = Math.ceil(timeDiff / (1000 * 3600 * 24));
                
                if (nights > 0) {
                    const subtotal = nights * pricePerNight;
                    
                    document.getElementById('nights-count').textContent = nights + (nights === 1 ? ' night' : ' nights');
                    document.getElementById('subtotal').textContent = 'Rp ' + subtotal.toLocaleString('id-ID');
                    document.getElementById('total-amount').textContent = 'Rp ' + subtotal.toLocaleString('id-ID');
                } else {
                    resetPricing();
                }
            } else {
                resetPricing();
            }
        }
        
        function resetPricing() {
            document.getElementById('nights-count').textContent = '-';
            document.getElementById('subtotal').textContent = '-';
            document.getElementById('total-amount').textContent = '-';
        }
        
        // Update check-out minimum date when check-in changes
        document.getElementById('check_in_date').addEventListener('change', function() {
            const checkInDate = new Date(this.value);
            const minCheckOut = new Date(checkInDate);
            minCheckOut.setDate(minCheckOut.getDate() + 1);
            
            document.getElementById('check_out_date').min = minCheckOut.toISOString().split('T')[0];
            calculateTotal();
        });
        
        document.getElementById('check_out_date').addEventListener('change', calculateTotal);
    </script>
</x-app-layout> 