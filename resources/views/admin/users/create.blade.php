@extends('layouts.admin')
@section('title', 'GRC - User Management')
@section('content')
    <div class="container mx-auto mt-10">
        <h1 class="text-center text-2xl font-semibold mb-6">Create New User</h1>

        <div class="mx-auto bg-white shadow-md rounded-lg p-6" style="max-width: 400px;"> <!-- Custom max-width -->
            <form action="{{ route('admin.users.store') }}" method="POST">
                @csrf

                <div>
                    <x-label for="name" value="{{ __('Student Name') }}" />
                    <x-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
                </div>

                <div class="mt-4">
                    <x-label for="email" value="{{ __('Email') }}" />
                    <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
                </div>

                <div class="mt-4">
                    <x-label for="password" value="{{ __('Password') }}" />
                    <div class="relative">
                        <input id="password" class="block mt-1 w-full border-gray-300 rounded-lg shadow-sm pr-10" type="password" name="password" required autocomplete="new-password" />
                        <span class="absolute inset-y-0 right-0 flex items-center pr-3 cursor-pointer" onclick="togglePassword('password', 'eye-icon-password')">
                            <svg id="eye-icon-password" class="h-5 w-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-.27.842-.678 1.633-1.21 2.344M15.73 15.73a9 9 0 01-9.458 0"></path>
                            </svg>
                        </span>
                    </div>
                </div>
    
                <div class="mt-4">
                    <x-label for="password_confirmation" value="{{ __('Confirm Password') }}" />
                    <div class="relative">
                        <input id="password_confirmation" class="block mt-1 w-full border-gray-300 rounded-lg shadow-sm pr-10" type="password" name="password_confirmation" required autocomplete="new-password" />
                        <span class="absolute inset-y-0 right-0 flex items-center pr-3 cursor-pointer" onclick="togglePassword('password_confirmation', 'eye-icon-confirm-password')">
                            <svg id="eye-icon-confirm-password" class="h-5 w-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-.27.842-.678 1.633-1.21 2.344M15.73 15.73a9 9 0 01-9.458 0"></path>
                            </svg>
                        </span>
                    </div>
                </div>
    
                <script>
                    function togglePassword(fieldId, iconId) {
                        const passwordField = document.getElementById(fieldId);
                        const eyeIcon = document.getElementById(iconId);
    
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
    
                <style>
                    .relative {
                            position: relative;
                        }
    
                        .absolute {
                            position: absolute;
                        }
    
                        .inset-y-0 {
                            top: 50%;
                            transform: translateY(-50%);
                        }
    
                        .right-0 {
                            right: 0;
                        }
    
                        .pr-3 {
                            padding-right: 0.75rem; /* Adjust if needed */
                        }
                </style>

                <div class="mt-4">
                    <x-label for="role" value="{{ __('Register as:') }}" />
                    <select name="role" x-model="role" class="block mt-1 w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm">
                        <option value="guest">Guest</option>
                        <option value="student">Student</option>
                        <option value="alumni">Alumni</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>

                <button type="submit" class="mt-6 w-full btn-custom">
                    Create
                </button>
                    
                <style>
                    .btn-custom {
                        background-color: #ff6b6b; /* Light red */
                        color: white; /* Text color */
                        font-weight: 600; /* Semi-bold */
                        padding: 0.5rem; /* Vertical padding */
                        border-radius: 0.375rem; /* Rounded corners */
                        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); /* Subtle shadow */
                        transition: background-color 0.3s, transform 0.3s;
                    }

                    .btn-custom:hover {
                        background-color: #ff4d4d; /* Darker shade on hover */
                        transform: scale(1.05); /* Slightly scale up on hover */
                    }

                </style>
                
            </form>
        </div>
    </div>
@endsection
