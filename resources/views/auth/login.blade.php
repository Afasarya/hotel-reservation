<x-guest-layout>
    <div class="text-center mb-6">
        <h2 class="text-2xl font-bold text-gray-900 mb-2">Welcome Back</h2>
        <p class="text-secondary-600">Sign in to your account</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-6">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email Address')" class="text-secondary-700 font-medium" />
            <x-text-input id="email" 
                class="block mt-2 w-full px-4 py-3 border border-secondary-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-200 bg-white/50" 
                type="email" 
                name="email" 
                :value="old('email')" 
                required 
                autofocus 
                autocomplete="username"
                placeholder="Enter your email" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('Password')" class="text-secondary-700 font-medium" />
            <x-text-input id="password" 
                class="block mt-2 w-full px-4 py-3 border border-secondary-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-200 bg-white/50"
                type="password"
                name="password"
                required 
                autocomplete="current-password"
                placeholder="Enter your password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center justify-between">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" 
                    class="rounded border-secondary-300 text-primary-600 shadow-sm focus:ring-primary-500 focus:ring-2" 
                    name="remember">
                <span class="ms-2 text-sm text-secondary-600">{{ __('Remember me') }}</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-sm text-primary-600 hover:text-primary-700 font-medium transition-colors duration-200" 
                   href="{{ route('password.request') }}">
                    {{ __('Forgot password?') }}
                </a>
            @endif
        </div>

        <!-- Submit Button -->
        <div class="space-y-4">
            <x-primary-button class="w-full justify-center py-3 px-4 bg-gradient-to-r from-primary-500 to-primary-600 hover:from-primary-600 hover:to-primary-700 text-white font-semibold rounded-xl shadow-lg transition-all duration-200 transform hover:scale-[1.02]">
                {{ __('Sign In') }}
            </x-primary-button>

            <div class="text-center">
                <span class="text-secondary-600">Don't have an account? </span>
                <a href="{{ route('register') }}" class="text-primary-600 hover:text-primary-700 font-medium transition-colors duration-200">
                    {{ __('Sign up here') }}
                </a>
            </div>
        </div>
    </form>
</x-guest-layout>
