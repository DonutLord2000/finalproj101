<x-guest-layout>
    @section('title', 'GRC - Reset Password')
    <!-- Wrapper for background image and blur effect -->
    <div class="relative min-h-screen">
        <!-- Background image with blur applied -->
        <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('{{ asset('images/bg.png') }}'); filter: blur(8px);">
        </div>
        <!-- Overlay to darken the background for better contrast -->
        <div class="absolute inset-0 bg-black bg-opacity-50"></div>
        <!-- Content wrapper to ensure form is above the blurred background -->
        <div class="relative flex items-center justify-center min-h-screen">
            <x-authentication-card class="bg-white bg-opacity-80 p-8 rounded-lg shadow-lg backdrop-blur-none">
                <div>
                    <img src="{{ asset('images/grc.png') }}" alt="Logo" style="width: 350px; height: 170px;">
                </div>

        <x-validation-errors class="mb-4" />

        <form method="POST" action="{{ route('password.update') }}">
            @csrf

            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <div class="block">
                <x-label for="email" value="{{ __('Email') }}" />
                <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email', $request->email)" required autofocus autocomplete="username" />
            </div>

            <div class="mt-4">
                <x-label for="password" value="{{ __('Password') }}" />
                <x-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
            </div>

            <div class="mt-4">
                <x-label for="password_confirmation" value="{{ __('Confirm Password') }}" />
                <x-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
            </div>

            <div class="flex items-center justify-end mt-4">
                <x-button>
                    {{ __('Reset Password') }}
                </x-button>
            </div>
        </form>
    </x-authentication-card>
</x-guest-layout>
