@extends('layouts.admin')
@section('title', 'GRC - News')
@section('content')
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create News Post') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8 bg-white border-b border-gray-200">
                    <form action="{{ route('news.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-4">
                            <x-label for="title" value="{{ __('Title') }}" />
                            <x-input id="title" class="block mt-1 w-full" type="text" name="title" :value="old('title')" required autofocus />
                        </div>

                        <div class="mb-4">
                            <x-label for="content" value="{{ __('Content') }}" />
                            <textarea id="content" name="content" rows="8" class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm block mt-1 w-full" required>{{ old('content') }}</textarea>
                        </div>

                        <div class="mb-4">
                            <x-label for="visible_to" value="{{ __('Visible To') }}" />
                            <select id="visible_to" name="visible_to" class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm block mt-1 w-full" required>
                                <option value="everyone">Everyone</option>
                                <option value="student">Students Only</option>
                                <option value="alumni">Alumni Only</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <x-label for="source" value="{{ __('Source of post') }}" />
                            <x-input id="source" class="block mt-1 w-full" type="text" name="source" :value="old('source')" />
                        </div>

                        <div class="mb-4">
                            <x-label for="image" value="{{ __('Image') }}" />
                            <input id="image" type="file" name="image" class="mt-1 block w-full" accept="image/*">
                        </div>

                        <div class="mb-4">
                            <x-label for="video" value="{{ __('Video') }}" />
                            <input id="video" type="file" name="video" class="mt-1 block w-full" accept="video/*">
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-button class="ml-4">
                                {{ __('Create Post') }}
                            </x-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection