<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-secondary-800 leading-tight">
                Room Types Management
            </h2>
            <a href="{{ route('admin.room-types.create') }}" 
               class="inline-flex items-center px-4 py-2 bg-primary-600 text-white font-medium rounded-lg hover:bg-primary-700 transition duration-300">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Add Room Type
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                    {{ session('success') }}
                </div>
            @endif

            @if($roomTypes->count() > 0)
                <!-- Room Types Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($roomTypes as $roomType)
                        <div class="bg-white rounded-xl shadow-sm border border-primary-100 overflow-hidden hover:shadow-md transition duration-300">
                            <!-- Room Image -->
                            <div class="aspect-w-16 aspect-h-9 bg-gray-200">
                                @if($roomType->images && count($roomType->images) > 0)
                                    <img src="{{ Storage::url($roomType->images[0]) }}" 
                                         alt="{{ $roomType->name }}" 
                                         class="w-full h-48 object-cover">
                                @else
                                    <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                                        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                @endif
                            </div>
                            
                            <!-- Room Details -->
                            <div class="p-6">
                                <div class="flex items-center justify-between mb-3">
                                    <h3 class="text-lg font-semibold text-secondary-900">{{ $roomType->name }}</h3>
                                    <span class="px-2 py-1 text-xs font-medium {{ $roomType->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }} rounded-full">
                                        {{ $roomType->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </div>
                                
                                @if($roomType->description)
                                    <p class="text-sm text-secondary-600 mb-4 line-clamp-2">
                                        {{ Str::limit($roomType->description, 100) }}
                                    </p>
                                @endif
                                
                                <div class="space-y-2 text-sm text-secondary-600 mb-4">
                                    <div class="flex justify-between">
                                        <span>Price per night:</span>
                                        <span class="font-semibold text-secondary-900">Rp {{ number_format($roomType->price_per_night, 0, ',', '.') }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span>Capacity:</span>
                                        <span class="text-secondary-900">{{ $roomType->capacity }} guests</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span>Total rooms:</span>
                                        <span class="text-secondary-900">{{ $roomType->total_rooms }} rooms</span>
                                    </div>
                                </div>
                                
                                <!-- Facilities -->
                                @if($roomType->facilities && count($roomType->facilities) > 0)
                                    <div class="mb-4">
                                        <h4 class="text-sm font-medium text-secondary-700 mb-2">Facilities</h4>
                                        <div class="flex flex-wrap gap-1">
                                            @foreach(array_slice($roomType->facilities, 0, 3) as $facility)
                                                <span class="px-2 py-1 text-xs bg-primary-100 text-primary-800 rounded">{{ ucfirst(str_replace('_', ' ', $facility)) }}</span>
                                            @endforeach
                                            @if(count($roomType->facilities) > 3)
                                                <span class="px-2 py-1 text-xs bg-primary-100 text-primary-800 rounded">+{{ count($roomType->facilities) - 3 }} more</span>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                                
                                <!-- Actions -->
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('admin.room-types.show', $roomType) }}" 
                                       class="flex-1 inline-flex items-center justify-center px-3 py-2 bg-primary-600 text-white text-sm font-medium rounded hover:bg-primary-700 transition duration-300">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                        View
                                    </a>
                                    <a href="{{ route('admin.room-types.edit', $roomType) }}" 
                                       class="flex-1 inline-flex items-center justify-center px-3 py-2 bg-secondary-600 text-white text-sm font-medium rounded hover:bg-secondary-700 transition duration-300">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                        Edit
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if($roomTypes->hasPages())
                    <div class="mt-8">
                        {{ $roomTypes->links() }}
                    </div>
                @endif
            @else
                <!-- Empty State -->
                <div class="bg-white rounded-xl shadow-sm border border-primary-100 p-12 text-center">
                    <svg class="w-16 h-16 text-secondary-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                    <h3 class="text-lg font-medium text-secondary-900 mb-2">No Room Types Yet</h3>
                    <p class="text-secondary-600 mb-6">Get started by creating your first room type.</p>
                    <a href="{{ route('admin.room-types.create') }}" 
                       class="inline-flex items-center px-6 py-3 bg-primary-600 text-white font-medium rounded-lg hover:bg-primary-700 transition duration-300">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Create Room Type
                    </a>
                </div>
            @endif
        </div>
    </div>
</x-app-layout> 