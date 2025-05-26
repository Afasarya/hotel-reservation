<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-secondary-800 leading-tight">
                Booking Management
            </h2>
            <div class="text-sm text-secondary-600">
                {{ $bookings->total() }} total bookings
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Search and Filter -->
            <div class="bg-white rounded-xl shadow-sm border border-primary-100 mb-8 overflow-hidden">
                <div class="p-6">
                    <form method="GET" action="{{ route('admin.bookings.index') }}" class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <!-- Search -->
                            <div>
                                <label for="search" class="block text-sm font-medium text-secondary-700 mb-2">Search</label>
                                <input type="text" 
                                       name="search" 
                                       id="search"
                                       value="{{ request('search') }}"
                                       placeholder="Search by booking code or guest name"
                                       class="w-full rounded-lg border-secondary-300 focus:border-primary-500 focus:ring-primary-500">
                            </div>

                            <!-- Status Filter -->
                            <div>
                                <label for="status" class="block text-sm font-medium text-secondary-700 mb-2">Status</label>
                                <select name="status" id="status" class="w-full rounded-lg border-secondary-300 focus:border-primary-500 focus:ring-primary-500">
                                    <option value="">All Status</option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                    <option value="checked_in" {{ request('status') == 'checked_in' ? 'selected' : '' }}>Checked In</option>
                                    <option value="checked_out" {{ request('status') == 'checked_out' ? 'selected' : '' }}>Checked Out</option>
                                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                            </div>

                            <!-- Date From -->
                            <div>
                                <label for="date_from" class="block text-sm font-medium text-secondary-700 mb-2">From Date</label>
                                <input type="date" 
                                       name="date_from" 
                                       id="date_from"
                                       value="{{ request('date_from') }}"
                                       class="w-full rounded-lg border-secondary-300 focus:border-primary-500 focus:ring-primary-500">
                            </div>

                            <!-- Date To -->
                            <div>
                                <label for="date_to" class="block text-sm font-medium text-secondary-700 mb-2">To Date</label>
                                <input type="date" 
                                       name="date_to" 
                                       id="date_to"
                                       value="{{ request('date_to') }}"
                                       class="w-full rounded-lg border-secondary-300 focus:border-primary-500 focus:ring-primary-500">
                            </div>
                        </div>
                        
                        <div class="flex justify-end">
                            <a href="{{ route('admin.bookings.index') }}" class="px-4 py-2 text-sm text-secondary-600 hover:text-secondary-900 font-medium">Clear Filters</a>
                            <button type="submit" class="ml-4 px-6 py-2 bg-primary-600 text-white font-medium rounded-lg hover:bg-primary-700 transition duration-300">
                                Filter Results
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Bookings Table -->
            @if($bookings->count() > 0)
                <div class="bg-white rounded-xl shadow-sm border border-primary-100 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-primary-200">
                            <thead class="bg-primary-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-secondary-500 uppercase tracking-wider">Booking Info</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-secondary-500 uppercase tracking-wider">Guest</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-secondary-500 uppercase tracking-wider">Room Details</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-secondary-500 uppercase tracking-wider">Dates</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-secondary-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-secondary-500 uppercase tracking-wider">Payment</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-secondary-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-primary-100">
                                @foreach($bookings as $booking)
                                    <tr class="hover:bg-primary-50 transition duration-300">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-secondary-900">{{ $booking->booking_code }}</div>
                                            <div class="text-sm text-secondary-500">Created: {{ $booking->created_at->format('M d, Y') }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-secondary-900">{{ $booking->user->name }}</div>
                                            <div class="text-sm text-secondary-500">{{ $booking->user->email }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-secondary-900">{{ $booking->roomType->name }}</div>
                                            <div class="text-sm text-secondary-500">
                                                {{ $booking->room ? 'Room: '.$booking->room->room_number : 'No room assigned' }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-secondary-900">{{ $booking->check_in_date->format('M d, Y') }}</div>
                                            <div class="text-sm text-secondary-500">to {{ $booking->check_out_date->format('M d, Y') }}</div>
                                            <div class="text-xs text-secondary-400">{{ $booking->nights }} nights, {{ $booking->guests }} guests</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 py-1 text-xs font-medium rounded-full
                                                @if($booking->status === 'confirmed') bg-green-100 text-green-800
                                                @elseif($booking->status === 'pending') bg-yellow-100 text-yellow-800
                                                @elseif($booking->status === 'checked_in') bg-blue-100 text-blue-800
                                                @elseif($booking->status === 'checked_out') bg-purple-100 text-purple-800
                                                @else bg-red-100 text-red-800
                                                @endif">
                                                {{ ucfirst(str_replace('_', ' ', $booking->status)) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-secondary-900">
                                                Rp {{ number_format($booking->total_amount, 0, ',', '.') }}
                                            </div>
                                            <div class="text-xs text-secondary-500">
                                                @if($booking->payment)
                                                    {{ ucfirst($booking->payment->status) }}
                                                @else
                                                    No payment
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-y-1">
                                            <a href="{{ route('admin.bookings.show', $booking) }}" 
                                               class="inline-flex items-center px-3 py-1 bg-primary-600 text-white text-xs font-medium rounded hover:bg-primary-700 transition duration-300">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                                View
                                            </a>

                                            @if($booking->status === 'pending')
                                                <form method="POST" action="{{ route('admin.bookings.confirm', $booking) }}">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit"
                                                           class="inline-flex items-center px-3 py-1 bg-green-600 text-white text-xs font-medium rounded hover:bg-green-700 transition duration-300">
                                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                        </svg>
                                                        Confirm
                                                    </button>
                                                </form>
                                            @endif

                                            @if($booking->status === 'confirmed')
                                                <form method="POST" action="{{ route('admin.bookings.check-in', $booking) }}">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit"
                                                           class="inline-flex items-center px-3 py-1 bg-blue-600 text-white text-xs font-medium rounded hover:bg-blue-700 transition duration-300">
                                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                                        </svg>
                                                        Check In
                                                    </button>
                                                </form>
                                            @endif

                                            @if($booking->status === 'checked_in')
                                                <form method="POST" action="{{ route('admin.bookings.check-out', $booking) }}">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit"
                                                           class="inline-flex items-center px-3 py-1 bg-purple-600 text-white text-xs font-medium rounded hover:bg-purple-700 transition duration-300">
                                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                                        </svg>
                                                        Check Out
                                                    </button>
                                                </form>
                                            @endif

                                            @if(in_array($booking->status, ['pending', 'confirmed']))
                                                <form method="POST" action="{{ route('admin.bookings.cancel', $booking) }}">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit"
                                                           class="inline-flex items-center px-3 py-1 bg-red-600 text-white text-xs font-medium rounded hover:bg-red-700 transition duration-300">
                                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                        </svg>
                                                        Cancel
                                                    </button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    <div class="px-6 py-4 border-t border-primary-100">
                        {{ $bookings->withQueryString()->links() }}
                    </div>
                </div>
            @else
                <!-- Empty State -->
                <div class="bg-white rounded-xl shadow-sm border border-primary-100 p-12 text-center">
                    <svg class="w-16 h-16 text-secondary-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <h3 class="text-lg font-medium text-secondary-900 mb-2">No bookings found</h3>
                    <p class="text-secondary-600 mb-6">No bookings match your search criteria.</p>
                    <a href="{{ route('admin.bookings.index') }}" class="inline-flex items-center px-6 py-3 bg-primary-600 text-white font-medium rounded-lg hover:bg-primary-700 transition duration-300">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                        View All Bookings
                    </a>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
