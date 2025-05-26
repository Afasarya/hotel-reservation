<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-secondary-800 leading-tight">
                Our Rooms
            </h2>
            <div class="text-sm text-secondary-600">
                {{ $roomTypes->total() }} room types available
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Search and Filter Section -->
            <div class="bg-white rounded-xl shadow-sm border border-primary-100 mb-8 overflow-hidden">
                <div class="p-6">
                    <form method="GET" action="{{ route('rooms.index') }}" class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                            <!-- Search -->
                            <div>
                                <label for="search" class="block text-sm font-medium text-secondary-700 mb-2">Search Rooms</label>
                                <input type="text" 
                                       name="search" 
                                       id="search"
                                       value="{{ request('search') }}"
                                       placeholder="Search by room name..."
                                       class="w-full rounded-lg border-secondary-300 focus:border-primary-500 focus:ring-primary-500">
                            </div>

                            <!-- Capacity -->
                            <div>
                                <label for="capacity" class="block text-sm font-medium text-secondary-700 mb-2">Guests</label>
                                <select name="capacity" id="capacity" class="w-full rounded-lg border-secondary-300 focus:border-primary-500 focus:ring-primary-500">
                                    <option value="">Any capacity</option>
                                    <option value="1" {{ request('capacity') == '1' ? 'selected' : '' }}>1 Guest</option>
                                    <option value="2" {{ request('capacity') == '2' ? 'selected' : '' }}>2 Guests</option>
                                    <option value="3" {{ request('capacity') == '3' ? 'selected' : '' }}>3 Guests</option>
                                    <option value="4" {{ request('capacity') == '4' ? 'selected' : '' }}>4+ Guests</option>
                                </select>
                            </div>

                            <!-- Price Range -->
                            <div>
                                <label for="min_price" class="block text-sm font-medium text-secondary-700 mb-2">Min Price</label>
                                <input type="number" 
                                       name="min_price" 
                                       id="min_price"
                                       value="{{ request('min_price') }}"
                                       placeholder="Min price"
                                       class="w-full rounded-lg border-secondary-300 focus:border-primary-500 focus:ring-primary-500">
                            </div>

                            <div>
                                <label for="max_price" class="block text-sm font-medium text-secondary-700 mb-2">Max Price</label>
                                <input type="number" 
                                       name="max_price" 
                                       id="max_price"
                                       value="{{ request('max_price') }}"
                                       placeholder="Max price"
                                       class="w-full rounded-lg border-secondary-300 focus:border-primary-500 focus:ring-primary-500">
                            </div>
                        </div>

                        <div class="flex items-center space-x-4">
                            <button type="submit" class="inline-flex items-center px-6 py-2 bg-primary-600 text-white font-medium rounded-lg hover:bg-primary-700 transition duration-300">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                                Search Rooms
                            </button>
                            
                            @if(request()->hasAny(['search', 'capacity', 'min_price', 'max_price']))
                                <a href="{{ route('rooms.index') }}" class="inline-flex items-center px-4 py-2 text-secondary-600 font-medium rounded-lg border border-secondary-300 hover:bg-secondary-50 transition duration-300">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                    Clear Filters
                                </a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>

            <!-- Room Types Grid -->
            @if($roomTypes->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-8">
                    @foreach($roomTypes as $roomType)
                        <div class="bg-white rounded-xl shadow-sm border border-primary-100 overflow-hidden hover:shadow-lg transition duration-300 group">
                            <!-- Room Image -->
                            <div class="relative h-48 bg-gradient-to-br from-primary-100 to-primary-200">
                                @if($roomType->images && count($roomType->images) > 0)
                                    <img src="{{ Storage::url($roomType->images[0]) }}" 
                                         alt="{{ $roomType->name }}"
                                         class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center">
                                        <svg class="w-16 h-16 text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-4m-5 0H3m2 0h3M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                        </svg>
                                    </div>
                                @endif
                                
                                <!-- Availability Badge -->
                                <div class="absolute top-4 right-4">
                                    <span class="px-3 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-full">
                                        {{ $roomType->rooms_count }} available
                                    </span>
                                </div>
                            </div>

                            <!-- Room Details -->
                            <div class="p-6">
                                <div class="flex items-start justify-between mb-3">
                                    <h3 class="text-lg font-semibold text-secondary-900 group-hover:text-primary-600 transition duration-300">
                                        {{ $roomType->name }}
                                    </h3>
                                    <div class="text-right">
                                        <div class="text-xl font-bold text-primary-600">
                                            Rp {{ number_format($roomType->price_per_night, 0, ',', '.') }}
                                        </div>
                                        <div class="text-sm text-secondary-500">per night</div>
                                    </div>
                                </div>

                                <p class="text-secondary-600 text-sm mb-4 line-clamp-2">
                                    {{ $roomType->description }}
                                </p>

                                <!-- Room Features -->
                                <div class="flex items-center space-x-4 mb-4 text-sm text-secondary-600">
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                        {{ $roomType->capacity }} guests
                                    </div>
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-4m-5 0H3m2 0h3M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                        </svg>
                                        {{ $roomType->total_rooms }} rooms
                                    </div>
                                </div>

                                <!-- Facilities -->
                                @if($roomType->facilities && count($roomType->facilities) > 0)
                                    <div class="mb-4">
                                        <div class="flex flex-wrap gap-2">
                                            @foreach(array_slice($roomType->facilities, 0, 3) as $facility)
                                                <span class="px-2 py-1 text-xs bg-primary-100 text-primary-700 rounded-full">
                                                    {{ $facility }}
                                                </span>
                                            @endforeach
                                            @if(count($roomType->facilities) > 3)
                                                <span class="px-2 py-1 text-xs bg-secondary-100 text-secondary-700 rounded-full">
                                                    +{{ count($roomType->facilities) - 3 }} more
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                @endif

                                <!-- Action Buttons -->
                                <div class="flex space-x-3">
                                    <a href="{{ route('rooms.show', $roomType) }}" 
                                       class="flex-1 text-center px-4 py-2 bg-primary-600 text-white font-medium rounded-lg hover:bg-primary-700 transition duration-300">
                                        View Details
                                    </a>
                                    <a href="{{ route('bookings.create', $roomType) }}" 
                                       class="flex-1 text-center px-4 py-2 border border-primary-600 text-primary-600 font-medium rounded-lg hover:bg-primary-50 transition duration-300">
                                        Book Now
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="flex justify-center">
                    {{ $roomTypes->withQueryString()->links() }}
                </div>
            @else
                <!-- Empty State -->
                <div class="bg-white rounded-xl shadow-sm border border-primary-100 p-12 text-center">
                    <svg class="w-16 h-16 text-secondary-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    <h3 class="text-lg font-medium text-secondary-900 mb-2">No rooms found</h3>
                    <p class="text-secondary-600 mb-6">Try adjusting your search criteria or browse all available rooms.</p>
                    <a href="{{ route('rooms.index') }}" class="inline-flex items-center px-6 py-3 bg-primary-600 text-white font-medium rounded-lg hover:bg-primary-700 transition duration-300">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                        View All Rooms
                    </a>
                </div>
            @endif
        </div>
    </div>
</x-app-layout> 