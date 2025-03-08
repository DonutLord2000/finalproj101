<x-guest-layout>
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

        <div class="mb-4 text-sm text-gray-600">
            {{ __('This is a secure area of the application. Please confirm your password before continuing.') }}
        </div>

        <x-validation-errors class="mb-4" />

        <form method="POST" action="{{ route('password.confirm') }}">
            @csrf

            <div>
                <x-label for="password" value="{{ __('Password') }}" />
                <x-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="current-password" autofocus />
            </div>

            <div class="flex justify-end mt-4">
                <x-button class="ms-4">
                    {{ __('Confirm') }}
                </x-button>
            </div>
        </form>
    </x-authentication-card>
</x-guest-layout>