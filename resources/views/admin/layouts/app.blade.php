<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Aora Hotel Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    @stack('styles')
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <div class="bg-gray-800 text-white w-64 space-y-6 py-7 px-2 absolute inset-y-0 left-0 transform -translate-x-full md:relative md:translate-x-0 transition duration-200 ease-in-out">
            <div class="flex items-center space-x-2 px-4">
                <span class="text-2xl font-extrabold">Aora Hotel</span>
            </div>
            <nav>
                <a href="{{ route('admin.dashboard') }}" class="block py-2.5 px-4 rounded transition duration-200 {{ request()->routeIs('admin.dashboard') ? 'bg-gray-900' : 'hover:bg-gray-700' }}">
                    <i class="fas fa-home mr-2"></i>Dashboard
                </a>
                <a href="{{ route('admin.rooms.index') }}" class="block py-2.5 px-4 rounded transition duration-200 {{ request()->routeIs('admin.rooms.*') ? 'bg-gray-900' : 'hover:bg-gray-700' }}">
                    <i class="fas fa-bed mr-2"></i>Room Types
                </a>
                <a href="{{ route('admin.bookings.index') }}" class="block py-2.5 px-4 rounded transition duration-200 {{ request()->routeIs('admin.bookings.*') ? 'bg-gray-900' : 'hover:bg-gray-700' }}">
                    <i class="fas fa-calendar-check mr-2"></i>Bookings
                </a>
                <a href="{{ route('admin.payments.index') }}" class="block py-2.5 px-4 rounded transition duration-200 {{ request()->routeIs('admin.payments.*') ? 'bg-gray-900' : 'hover:bg-gray-700' }}">
                    <i class="fas fa-credit-card mr-2"></i>Payments
                </a>
                <a href="{{ route('admin.users.index') }}" class="block py-2.5 px-4 rounded transition duration-200 {{ request()->routeIs('admin.users.*') ? 'bg-gray-900' : 'hover:bg-gray-700' }}">
                    <i class="fas fa-users mr-2"></i>Users
                </a>
                <a href="{{ route('admin.notifications.index') }}" class="block py-2.5 px-4 rounded transition duration-200 {{ request()->routeIs('admin.notifications.*') ? 'bg-gray-900' : 'hover:bg-gray-700' }}">
                    <i class="fas fa-bell mr-2"></i>Notifications
                </a>
                <a href="{{ route('admin.availability.index') }}" class="block py-2.5 px-4 rounded transition duration-200 {{ request()->routeIs('admin.availability.*') ? 'bg-gray-900' : 'hover:bg-gray-700' }}">
                    <i class="fas fa-calendar-alt mr-2"></i>Availability
                </a>
            </nav>
        </div>

        <!-- Content -->
        <div class="flex-1">
            <!-- Top Navigation -->
            <header class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8 flex justify-between items-center">
                    <h1 class="text-3xl font-bold text-gray-900">
                        @yield('header')
                    </h1>
                    <div class="flex items-center space-x-4">
                        <span class="text-gray-700">{{ Auth::user()->name }}</span>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="text-gray-700 hover:text-gray-900">
                                <i class="fas fa-sign-out-alt"></i> Logout
                            </button>
                        </form>
                    </div>
                </div>
            </header>

            <!-- Main Content -->
            <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                @endif

                @if(session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <span class="block sm:inline">{{ session('error') }}</span>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    @stack('scripts')
</body>
</html> 