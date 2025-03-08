@extends('layouts.admin')
@section('title', 'GRC - User Management')
@section('content')
<div class="container mx-auto mt-10">
    <h1 class="text-center text-2xl font-semibold mb-6">Edit User</h1>
        <div class="container mx-auto mt-8 p-6 bg-white rounded-lg shadow" style="max-width: 400px;">

        <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <x-label for="name" value="{{ __('Name') }}" />
                <x-input type="text" name="name" class="block mt-1 w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm" value="{{ $user->name }}" required autofocus />
            </div>

            <div class="mb-4">
                <x-label for="email" value="{{ __('Email') }}" />
                <x-input type="email" name="email" class="block mt-1 w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm" value="{{ $user->email }}" required />
            </div>

            <div class="mb-4">
                <x-label for="role" value="{{ __('Role') }}" />
                <select name="role" class="block mt-1 w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm">
                    <option value="student" {{ $user->role === 'student' ? 'selected' : '' }}>Student</option>
                    <option value="alumni" {{ $user->role === 'alumni' ? 'selected' : '' }}>Alumni</option>
                    <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
                </select>
            </div>

            <div class="mb-4">
                <x-label for="student_id" value="{{ __('Student ID') }}" />
                <x-input type="text" name="student_id" class="block mt-1 w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm" value="{{ $user->student_id }}" required />
            </div>

            <button type="submit" class="mt-6 w-full btn-custom">
                Update
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
