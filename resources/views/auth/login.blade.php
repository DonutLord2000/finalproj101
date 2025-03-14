@extends('layouts.authentication') 
    @section('content')
    @section('title', 'GRC - Login')
    <div class="relative min-h-screen">
        <!-- Background image with blur -->
        <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('{{ asset('images/bg.png') }}'); filter: blur(8px);"></div>
        
        <!-- Overlay -->
        <div class="absolute inset-0 bg-black bg-opacity-50"></div>
        
        <!-- Content -->
        <div class="relative flex items-center justify-center min-h-screen">
            
                <x-authentication-card class="bg-white/90 backdrop-blur-none p-8 rounded-lg shadow-lg">
                    <!-- Logo -->
                    <div class="flex justify-center mb-8">
                        <img src="{{ asset('images/grc.png') }}" alt="Logo" class="w-[350px] h-[170px] object-contain">
                    </div>

                    <x-validation-errors class="mb-4" />

                    @if (session('status'))
                        <div class="mb-4 font-medium text-sm text-green-600">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}" class="space-y-4">
                        @csrf

                        <div>
                            <x-input id="email" 
                                class="block w-full rounded-md border-gray-300" 
                                type="email" 
                                name="email" 
                                :value="old('email')" 
                                placeholder="Email or phone number"
                                required 
                                autofocus 
                                autocomplete="username" />
                        </div>

                        <div>
                            <div class="relative">
                                <x-input id="password" 
                                    class="block w-full rounded-md border-gray-300 pr-10" 
                                    type="password" 
                                    name="password" 
                                    placeholder="Password"
                                    required 
                                    autocomplete="current-password" />
                                <button type="button" 
                                    class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-500 hover:text-gray-700"
                                    onclick="togglePassword()">
                                    <svg id="eye-icon" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-.27.842-.678 1.633-1.21 2.344M15.73 15.73a9 9 0 01-9.458 0"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <div class="flex items-center justify-between">
                            <label for="remember_me" class="flex items-center">
                                <x-checkbox id="remember_me" name="remember" />
                                <span class="ml-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                            </label>

                            @if (Route::has('password.request'))
                                <a class="text-sm text-blue-600 hover:text-blue-800" href="{{ route('password.request') }}">
                                    {{ __('Forgot password?') }}
                                </a>
                            @endif
                        </div>

                        <div>
                            <x-button class="w-full justify-center bg-red-800 hover:bg-red-600">
                                {{ __('Log in') }}
                            </x-button>
                        </div>

                        <div class="text-center">
                            <a href="{{ route('register') }}" class="text-sm text-gray-600 hover:text-gray-900">
                                Don't have an account? 
                                <span class="text-blue-600 hover:text-blue-800">Register</span>
                            </a>
                        </div>
                    </form>
                </x-authentication-card>
            
        </div>
    </div>

    <script>
        function togglePassword() {
            const passwordField = document.getElementById("password");
            const eyeIcon = document.getElementById("eye-icon");
            
            if (passwordField.type === "password") {
                passwordField.type = "text";
                eyeIcon.classList.add("text-gray-700");
                eyeIcon.classList.remove("text-gray-500");
            } else {
                passwordField.type = "password";
                eyeIcon.classList.add("text-gray-500");
                eyeIcon.classList.remove("text-gray-700");
            }
        }
    </script>
@endsection