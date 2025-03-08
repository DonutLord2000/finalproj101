<x-app-layout>
    @section('title', 'GRC - Threads')
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Discussion Area') }}
        </h2>
    </x-slot>

    <div class="py-12 mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 mb-6 mx-auto w-full lg:w-[60rem]">
            <h3 class="text-lg font-semibold mb-4">Create a new post</h3>
            <form action="{{ route('threads.store') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="content" class="block text-sm font-medium text-gray-700">Content</label>
                    <textarea name="content" id="content" rows="3" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"></textarea>
                </div>
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Create post
                </button>
            </form>
        </div>

        <div class="space-y-6">
            @foreach ($threads as $thread)
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg mx-auto w-full lg:w-[60rem]" x-data="{ isCommentsOpen: false }">
                    <div class="p-6">
                        <div class="flex justify-between items-start mb-4">
                            <div class="flex items-center space-x-3">
                                <img src="{{ $thread->user->profile_photo_url }}" alt="{{ $thread->user->name }}" class="w-10 h-10 rounded-full">
                                <div>
                                    <h4 class="text-lg font-semibold text-gray-900">
                                        {{ $thread->user->name }}
                                        @php
                                        $bgColor = match($thread->user->role) {
                                            'alumni' => 'inline-block px-2 py-1 bg-green-500 text-green-800 rounded',
                                            'admin' => 'text-white inline-block px-2 py-1 bg-red-500 text-red-800 rounded',
                                            'student' => 'inline-block px-2 py-1 bg-blue-500 text-blue-800 rounded',
                                            default => 'bg-gray-200 text-gray-700',
                                        };
                                        @endphp

                                        <span class="text-sm font-normal {{ $bgColor }} px-2 py-1 rounded-full ml-2">
                                            {{ $thread->user->role }}
                                        </span>
                                    </h4>
                                    <p class="text-sm text-gray-500">
                                        {{ $thread->created_at->format('M d, Y h:i A') }}
                                    </p>
                                </div>
                            </div>
                            @if(auth()->user()->id === $thread->user_id || auth()->user()->role === 'admin')
                                <div class="relative inline-block text-left">
                                    <div>
                                        <button type="button" class="inline-flex justify-center w-8 h-8 rounded-full border border-gray-300 shadow-sm bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-100 focus:ring-indigo-500" id="options-menu-{{ $thread->id }}" aria-haspopup="true" aria-expanded="true">
                                            <svg class="w-5 h-5 m-auto" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                <path d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z" />
                                            </svg>
                                        </button>
                                    </div>
                                    <div class="origin-top-right absolute right-0 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 hidden" id="dropdown-menu-{{ $thread->id }}">
                                        <div class="py-1" role="menu" aria-orientation="vertical" aria-labelledby="options-menu-{{ $thread->id }}">
                                            <a href="{{ route('threads.edit', $thread) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900" role="menuitem">Edit</a>
                                            <form action="{{ route('threads.destroy', $thread) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900" role="menuitem">Delete</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="mt-3 text-gray-700">
                            <a href="{{ route('threads.show', $thread) }}" class="block">{{ $thread->content }}</a>
                        </div>
                    </div>
                    <div class="px-6 py-4 bg-gray-100 flex justify-between items-center">
                        <div class="flex space-x-4">
                            @php
                                $user = auth()->user();
                                $userUpvoted = $thread->reactions()->where('user_id', $user->id)->where('type', 'upvote')->exists();
                                $userHearted = $thread->reactions()->where('user_id', $user->id)->where('type', 'heart')->exists();
                            @endphp

                            <button class="react-btn flex items-center space-x-1 text-gray-500" 
                                    data-type="upvote" 
                                    data-thread="{{ $thread->id }}" 
                                    data-reacted="{{ $userUpvoted ? 'true' : 'false' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 upvote-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5" />
                                </svg>
                                <span class="upvote-count-{{ $thread->id }}">{{ $thread->upvotes }}</span>
                            </button>
                            
                            <button class="react-btn flex items-center space-x-1 text-gray-500 ml-2" 
                                    data-type="heart" 
                                    data-thread="{{ $thread->id }}" 
                                    data-reacted="{{ $userHearted ? 'true' : 'false' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 heart-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                </svg>
                                <span class="heart-count-{{ $thread->id }}">{{ $thread->hearts }}</span>
                            </button>                    

                        </div>
                        <button @click="isCommentsOpen = !isCommentsOpen" class="flex items-center space-x-1 text-gray-500 hover:text-blue-500 transition-colors duration-200">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                            </svg>
                            <span>{{ $thread->comments_count }} comments</span>
                        </button>
                    </div>
                    <div x-show="isCommentsOpen" class="px-6 py-4 bg-gray-100">
                        @foreach ($thread->comments as $comment)
                            <div class="flex items-start space-x-3 mb-4">
                                <img src="{{ $comment->user->profile_photo_url }}" alt="{{ $comment->user->name }}" class="w-8 h-8 rounded-full">
                                <div>
                                    <p class="font-semibold text-gray-900">
                                        {{ $comment->user->name }}
                                        @php
                                            $bgColor = match($comment->user->role) {
                                                default => 'bg-gray-200 text-gray-700',
                                            };
                                        @endphp

                                        <span class="text-xs {{ $bgColor }} px-2 py-1 rounded-full ml-2">
                                            {{ $comment->user->role }}
                                        </span>
                                    </p>
                                    <p class="text-xs text-gray-500 mt-1">{{ $comment->created_at->format('M d, Y h:i A') }}</p>
                                    <p class="text-sm text-gray-700 mt-2">{{ $comment->content }}</p>
                                </div>
                            </div>
                        @endforeach
                        <form action="{{ route('threads.comments.store', $thread) }}" method="POST" class="mt-4">
                            @csrf
                            <div class="flex flex-col sm:flex-row items-start sm:items-center space-y-2 sm:space-y-0 sm:space-x-3">
                                <img src="{{ auth()->user()->profile_photo_url }}" alt="{{ auth()->user()->name }}" class="w-8 h-8 rounded-full">
                                <input type="text" name="content" placeholder="Write a comment..." class="flex-grow border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border rounded-md">
                                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    Post
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            @endforeach

            {{ $threads->links() }}
        </div>
    </div>

    <style>
        
    </style>
    
    @push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        function updateButtonState(button, isReacted) {
            const type = button.dataset.type;
            const icon = button.querySelector(type === 'upvote' ? '.upvote-icon' : '.heart-icon');
            
            if (isReacted) {
                icon.style.color = type === 'upvote' ? "blue" : "red";
            } else {
                icon.style.color = "gray";
            }
        }

        document.querySelectorAll('.react-btn').forEach(button => {
            const type = button.dataset.type;
            const threadId = button.dataset.thread;
            const upvoteCount = document.querySelector(`.upvote-count-${threadId}`);
            const heartCount = document.querySelector(`.heart-count-${threadId}`);

            // Set initial state
            updateButtonState(button, button.dataset.reacted === 'true');

            // Add click event listener
            button.addEventListener('click', function() {
                fetch(`/threads/${threadId}/react`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({ type: type })
                })
                .then(response => response.json())
                .then(data => {
                    if (data && data.counts) {
                        upvoteCount.textContent = data.counts.upvotes;
                        heartCount.textContent = data.counts.hearts;

                        // Update button states
                        document.querySelectorAll(`.react-btn[data-thread="${threadId}"]`).forEach(btn => {
                            const btnType = btn.dataset.type;
                            updateButtonState(btn, data.userReacted[btnType]);
                        });
                    } else {
                        console.error('Unexpected response format:', data);
                    }
                })
                .catch(error => console.error('Error:', error));
            });
        });
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('[id^="options-menu-"]').forEach(button => {
            const threadId = button.id.split('-').pop();
            const dropdownMenu = document.getElementById(`dropdown-menu-${threadId}`);

            button.addEventListener('click', function(event) {
                event.stopPropagation();
                dropdownMenu.classList.toggle('hidden');
            });

            // Close the dropdown when clicking outside
            document.addEventListener('click', function() {
                dropdownMenu.classList.add('hidden');
            });
        });
    });
</script>
@endpush
</x-app-layout>

