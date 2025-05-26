<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'AoraGrand Hotel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-gradient-to-br from-white to-primary-50">
        <div class="min-h-screen flex flex-col justify-center items-center p-4">
            <!-- Logo Section -->
            <div class="text-center mb-8 animate-fade-in">
                <a href="/" class="inline-block">
                    <div class="flex items-center justify-center w-16 h-16 bg-gradient-to-r from-primary-500 to-primary-600 rounded-2xl shadow-lg mb-4 mx-auto">
                        <x-application-logo class="w-8 h-8 text-white" />
                    </div>
                </a>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">AoraGrand Hotel</h1>
                <p class="text-secondary-600">Welcome to luxury experience</p>
            </div>

            <!-- Form Container -->
            <div class="w-full max-w-md animate-slide-up">
                <div class="bg-white/80 backdrop-blur-sm shadow-xl border border-primary-100 rounded-2xl overflow-hidden">
                    <div class="px-8 py-8">
                        {{ $slot }}
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="mt-8 text-center text-sm text-secondary-500">
                <p>&copy; {{ date('Y') }} AoraGrand Hotel. All rights reserved.</p>
            </div>
        </div>
    </body>
</html>
