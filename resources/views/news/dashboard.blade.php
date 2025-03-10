<x-app-layout>
    @section('title', 'GRC - News')
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('News Management') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8 bg-white border-b border-gray-200">
                    <x-button-link href="{{ route('news.create') }}" class="mb-4">
                        {{ __('Create New Post') }}
                    </x-button-link>
                    @foreach ($posts as $post)
                    <div class="mb-8 relative border-l-4 border-l-gray-200 bg-white shadow-md rounded-lg overflow-hidden mx-auto">
                        <div class="absolute left-1 top-1 text-black-600">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 " viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="bg-gray-200 text-black py-2 px-4 flex items-center justify-between">
                                <h2 class="ml-4 font-semibold">{{ $post->title }}</h2>
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('news.edit', $post) }}" class="text-blue-600 hover:text-blue-400 font-semibold">
                                        Edit
                                    </a>
                                    <form action="{{ route('news.destroy', $post) }}" method="POST" class="inline" onsubmit="return confirmDeletion()">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-400 font-semibold">
                                            Delete
                                        </button>
                                    </form>                                    
                            </div>
                        </div>
                            <div class="p-4 pl-12">
                                @if($post->image)
                                    <img 
                                        src="{{ Storage::disk('s3')->temporaryUrl($post->image, now()->addMinutes(5)) }}"
                                        alt="News post image" 
                                        class="mb-2 mt-2 mr-4 rounded-lg" 
                                        style="max-width: 40rem; max-height: 24rem; object-fit: contain;"
                                        onerror="this.onerror=null; this.src='/images/placeholder-image.png'; this.alt='Image not available'"
                                    >
                                @endif
                                @if($post->video)
                                    <video controls class="w-full h-auto mb-4 rounded-lg">
                                        <source src="{{ Storage::disk('s3')->temporaryUrl($post->video, now()->addMinutes(5)) }}" type="video/mp4">
                                        Your browser does not support the video tag.
                                    </video>
                                @endif
                                <div class="prose prose-sm max-w-none mb-2 mt-2 mr-4">
                                    {!! nl2br(e($post->content)) !!}
                                </div>
                            </div>
                            @if($post->source)
                                <div class="px-4 py-2 text-sm text-black-600 border-t">
                                    This is a message from <strong>{{ $post->source }}</strong>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <script>
        function confirmDeletion() {
            return confirm("Are you sure you want to delete this news?");
        }
    </script>
</x-app-layout>