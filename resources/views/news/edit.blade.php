<x-app-layout>
    @section('title', 'GRC - News')
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit News Post') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8 bg-white border-b border-gray-200">
                    <form action="{{ route('news.update', $post) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-4">
                            <x-label for="title" value="{{ __('Title') }}" />
                            <x-input id="title" class="block mt-1 w-full" type="text" name="title" :value="old('title', $post->title)" required autofocus />
                        </div>

                        <div class="mb-4">
                            <x-label for="content" value="{{ __('Content') }}" />
                            <textarea id="content" name="content" class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm block mt-1 w-full" rows="6" required>{{ old('content', $post->content) }}</textarea>
                        </div>

                        <div class="mb-4">
                            <x-label for="visible_to" value="{{ __('Visible To') }}" />
                            <select id="visible_to" name="visible_to" class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm block mt-1 w-full" required>
                                <option value="students" {{ old('visible_to', $post->visible_to) == 'students' ? 'selected' : '' }}>Students</option>
                                <option value="alumni" {{ old('visible_to', $post->visible_to) == 'alumni' ? 'selected' : '' }}>Alumni</option>
                                <option value="everyone" {{ old('visible_to', $post->visible_to) == 'everyone' ? 'selected' : '' }}>Everyone</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <x-label for="source" value="{{ __('Source') }}" />
                            <x-input id="source" class="block mt-1 w-full" type="text" name="source" :value="old('source', $post->source)" required />
                        </div>

                        <div class="mb-4">
                            <x-label for="image" value="{{ __('Image') }}" />
                            <input id="image" type="file" name="image" class="mt-1">
                            @if($post->image)
                                <img src="{{ Storage::disk('s3')->url($post->image) }}" alt="Current image" class="mt-2" style="max-width: 200px;">
                            @endif
                        </div>

                        <div class="mb-4">
                            <x-label for="video" value="{{ __('Video') }}" />
                            <input id="video" type="file" name="video" class="mt-1">
                            @if($post->video)
                                <video controls class="mt-2" style="max-width: 200px;">
                                    <source src="{{ Storage::disk('s3')->url($post->video) }}" type="video/mp4">
                                    Your browser does not support the video tag.
                                </video>
                            @endif
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-button class="ml-4">
                                {{ __('Update News Post') }}
                            </x-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>