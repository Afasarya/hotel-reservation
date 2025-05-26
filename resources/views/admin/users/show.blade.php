<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-secondary-800 leading-tight">
                    User Details: {{ $user->name }}
                </h2>
                <p class="text-sm text-secondary-600 mt-1">
                    User ID: {{ $user->id }} • Joined {{ $user->created_at->format('M j, Y') }}
                </p>
            </div>
            <div class="flex items-center space-x-3">
                @if($user->email_verified_at)
                    <span class="px-3 py-1 text-sm font-medium bg-green-100 text-green-800 rounded-full">
                        Verified
                    </span>
                @else
                    <span class="px-3 py-1 text-sm font-medium bg-red-100 text-red-800 rounded-full">
                        Unverified
                    </span>
                @endif
                <a href="{{ route('admin.users.index') }}" class="inline-flex items-center px-4 py-2 text-secondary-600 font-medium rounded-lg border border-secondary-300 hover:bg-secondary-50 transition duration-300">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Users
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            <!-- User Information -->
            <div class="bg-white rounded-xl shadow-sm border border-primary-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-primary-100 bg-primary-50">
                    <h3 class="text-lg font-semibold text-secondary-900">User Information</h3>
                </div>
                
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-secondary-700 mb-1">Full Name</label>
                                <div class="text-sm text-secondary-900">{{ $user->name }}</div>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-secondary-700 mb-1">Email Address</label>
                                <div class="text-sm text-secondary-900">{{ $user->email }}</div>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-secondary-700 mb-1">Phone Number</label>
                                <div class="text-sm text-secondary-900">{{ $user->phone ?? 'Not provided' }}</div>
                            </div>
                        </div>
                        
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-secondary-700 mb-1">Address</label>
                                <div class="text-sm text-secondary-900">{{ $user->address ?? 'Not provided' }}</div>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-secondary-700 mb-1">Date of Birth</label>
                                <div class="text-sm text-secondary-900">{{ $user->date_of_birth ? $user->date_of_birth->format('M j, Y') : 'Not provided' }}</div>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-secondary-700 mb-1">Account Status</label>
                                <div class="flex items-center space-x-2">
                                    @if($user->email_verified_at)
                                        <span class="px-2 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-full">
                                            Verified
                                        </span>
                                        <span class="text-sm text-secondary-600">since {{ $user->email_verified_at->format('M j, Y') }}</span>
                                    @else
                                        <span class="px-2 py-1 text-xs font-medium bg-red-100 text-red-800 rounded-full">
                                            Unverified
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Actions -->
                    <div class="mt-6 pt-6 border-t border-primary-100">
                        <div class="flex items-center space-x-3">
                            <form method="POST" action="{{ route('admin.users.toggle-status', $user) }}" class="inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" 
                                        onclick="return confirm('Are you sure you want to {{ $user->email_verified_at ? 'deactivate' : 'activate' }} this user?')"
                                        class="inline-flex items-center px-4 py-2 {{ $user->email_verified_at ? 'bg-red-600 hover:bg-red-700' : 'bg-green-600 hover:bg-green-700' }} text-white font-medium rounded-lg transition duration-300">
                                    @if($user->email_verified_at)
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                        Deactivate User
                                    @else
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Activate User
                                    @endif
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Booking History -->
            <div class="bg-white rounded-xl shadow-sm border border-primary-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-primary-100 bg-primary-50">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-secondary-900">Booking History</h3>
                        <span class="text-sm text-secondary-600">{{ $user->bookings->count() }} total bookings</span>
                    </div>
                </div>
                
                @if($user->bookings->count() > 0)
                    <div class="divide-y divide-primary-100">
                        @foreach($user->bookings as $booking)
                            <div class="p-6 hover:bg-primary-50 transition duration-300">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <div class="flex items-center space-x-3 mb-2">
                                            <h4 class="font-medium text-secondary-900">{{ $booking->roomType->name }}</h4>
                                            <span class="px-2 py-1 text-xs font-medium rounded-full
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
                                                <div class="text-secondary-900">{{ $booking->guests }}</div>
                                            </div>
                                            <div>
                                                <span class="font-medium">Total Amount:</span>
                                                <div class="text-secondary-900 font-semibold">Rp {{ number_format($booking->total_amount, 0, ',', '.') }}</div>
                                            </div>
                                        </div>
                                        
                                        @if($booking->payment)
                                            <div class="mt-3 p-3 bg-primary-50 rounded-lg">
                                                <div class="flex items-center justify-between">
                                                    <div>
                                                        <span class="text-sm font-medium text-secondary-700">Payment Status:</span>
                                                        <span class="ml-2 px-2 py-1 text-xs font-medium rounded-full
                                                            @if($booking->payment->status === 'paid') bg-green-100 text-green-800
                                                            @elseif($booking->payment->status === 'pending') bg-yellow-100 text-yellow-800
                                                            @else bg-red-100 text-red-800
                                                            @endif">
                                                            {{ ucfirst($booking->payment->status) }}
                                                        </span>
                                                    </div>
                                                    @if($booking->payment->paid_at)
                                                        <span class="text-sm text-secondary-600">
                                                            Paid on {{ $booking->payment->paid_at->format('M j, Y') }}
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <div class="ml-4">
                                        <a href="{{ route('admin.bookings.show', $booking) }}" 
                                           class="inline-flex items-center px-3 py-2 bg-primary-600 text-white text-sm font-medium rounded-lg hover:bg-primary-700 transition duration-300">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                            View Booking
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="p-12 text-center">
                        <svg class="w-16 h-16 text-secondary-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <h3 class="text-lg font-medium text-secondary-900 mb-2">No Bookings Yet</h3>
                        <p class="text-secondary-600">This user hasn't made any bookings yet.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout> 