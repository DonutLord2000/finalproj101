<x-app-layout>
    @section('title', 'GRC - Threads')
    @push('styles')
    <style>
        /* Ensure word counter is always visible */
        .word-counter {
            display: flex !important;
            justify-content: space-between !important;
            margin-top: 0.25rem !important;
            font-size: 0.875rem !important;
            color: #6b7280 !important;
        }

        /* Style for the loading spinner */
        .loading-spinner {
            display: inline-block;
            width: 1rem;
            height: 1rem;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top-color: white;
            animation: spin 1s ease-in-out infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* Improved warning message styling */
        .text-red-500.text-sm.mt-1:not(.hidden) {
            padding: 0.5rem;
            border-radius: 0.25rem;
            background-color: #FEF2F2;
            border: 1px solid #FECACA;
            margin-top: 0.5rem;
        }
    </style>
    @endpush
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg mb-6 mx-auto w-full lg:w-[70rem]">
                <div class="p-6">
                    <div class="flex justify-between items-start mb-4">
                        <div class="flex items-start space-x-3">
                            <img src="{{ $thread->display_profile_picture_url }}" alt="{{ $thread->user_display_name }}" class="w-12 h-12 rounded-full object-cover border-2 border-gray-300">
                            <div>
                                <h3 class="text-xl font-semibold text-gray-900">
                                    @if(!$thread->is_anonymous)
                                        <a href="{{ route('alumni.profile.show', $thread->user) }}" class="hover:underline">
                                            {{ $thread->user_display_name }}
                                        </a>
                                    @else
                                        {{ $thread->user_display_name }}
                                    @endif
                                    @php
                                    $bgColor = match($thread->display_role) {
                                        'alumni' => 'inline-block px-2 py-1 bg-green-500 text-green-800 rounded',
                                                    'admin' => 'text-white inline-block px-2 py-1 bg-red-500 text-red-800 rounded',
                                                    'student' => 'inline-block px-2 py-1 bg-blue-500 text-blue-800 rounded',
                                                    'guest' => 'inline-block px-2 py-1 bg-gray-500 text-gray-800 rounded', // Added guest role color
                                        default => 'bg-gray-200 text-gray-700',
                                    };
                                    @endphp

                                    <span class="text-sm font-normal {{ $bgColor }} px-2 py-1 rounded-full ml-2">
                                        {{ $thread->display_role }}
                                    </span>
                                </h3>
                                <p class="text-sm text-gray-500">
                                    {{ $thread->created_at->format('M d, Y h:i A') }}
                                </p>
                            </div>
                        </div>
                        @if(auth()->check() && (auth()->user()->id === $thread->user_id || auth()->user()->role === 'admin'))
                            <div class="relative inline-block text-left">
                                <div>
                                    <button type="button" class="inline-flex justify-center w-8 h-8 rounded-full border border-gray-300 shadow-sm bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-100 focus:ring-indigo-500" id="options-menu" aria-haspopup="true" aria-expanded="true">
                                        <svg class="w-5 h-5 m-auto" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                            <path d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z" />
                                        </svg>
                                    </button>
                                </div>
                                <div class="origin-top-right absolute right-0 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 hidden" id="dropdown-menu">
                                    <div class="py-1" role="menu" aria-orientation="vertical" aria-labelledby="options-menu">
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
                    <div class="flex-grow">
                        <p class="mt-4 text-gray-700 text-lg">{{ $thread->content }}</p>
                    </div>
                </div>
                <div class="px-6 py-4 bg-gray-50 flex justify-between items-center">
                    <div class="flex space-x-4">
                        <button class="react-btn flex items-center space-x-1 text-gray-500 hover:text-blue-500 transition-colors duration-200" data-type="upvote" data-thread="{{ $thread->id }}" @guest disabled @endguest>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5" />
                            </svg>
                            <span class="upvote-count">{{ $thread->upvotes }}</span>
                        </button>
                        <button class="react-btn flex items-center space-x-1 text-gray-500 hover:text-pink-500 transition-colors duration-200" data-type="heart" data-thread="{{ $thread->id }}" @guest disabled @endguest>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                            </svg>
                            <span class="heart-count">{{ $thread->hearts }}</span>
                        </button>
                    </div>
                    <button class="flex items-center space-x-1 text-gray-500 hover:text-blue-500 transition-colors duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                        </svg>
                        <span>{{ $thread->comments_count }} comments</span>
                    </button>
                </div>
            </div>
            <h4 class="text-center mx-auto text-2xl font-bold mb-4 text-gray-900">Comments</h4>
            <div class="space-y-4 mb-6">
                @foreach ($thread->comments as $comment)
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg shadow-xl mx-auto mb-2 w-full lg:w-[70rem]">
                        <div class="p-6">
                            <div class="flex items-start space-x-3">
                                <img src="{{ $comment->display_profile_picture_url }}" alt="{{ $comment->user_display_name }}" class="w-10 h-10 rounded-full object-cover border-2 border-gray-300">
                                <div class="flex-grow">
                                    <div class="flex justify-between items-start">
                                        <p class="font-semibold text-gray-900">
                                            @if(!$comment->is_anonymous)
                                                <a href="{{ route('alumni.profile.show', $comment->user) }}" class="hover:underline">
                                                    {{ $comment->user_display_name }}
                                                </a>
                                            @else
                                                {{ $comment->user_display_name }}
                                            @endif
                                            @php
                                                $bgColor = match($comment->display_role) {
                                                    'alumni' => 'inline-block px-2 py-1 bg-green-500 text-green-800 rounded',
                                                    'admin' => 'text-white inline-block px-2 py-1 bg-red-500 text-red-800 rounded',
                                                    'student' => 'inline-block px-2 py-1 bg-blue-500 text-blue-800 rounded',
                                                    'guest' => 'inline-block px-2 py-1 bg-gray-500 text-gray-800 rounded', // Added guest role color
                                                    default => 'bg-gray-200 text-gray-700',
                                                };
                                            @endphp

                                            <span class="text-xs {{ $bgColor }} px-2 py-1 rounded-full ml-2">
                                                {{ $comment->display_role }}
                                            </span>
                                        </p>

                                        @if(auth()->check() && (auth()->user()->id === $comment->user_id || auth()->user()->role === 'admin') && !$comment->isDeleted())
                                            <div class="relative inline-block text-left">
                                                <div>
                                                    <button type="button" class="inline-flex justify-center w-8 h-8 rounded-full border border-gray-300 shadow-sm bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-100 focus:ring-indigo-500" id="comment-options-menu-{{ $comment->id }}" aria-haspopup="true" aria-expanded="true">
                                                        <svg class="w-5 h-5 m-auto" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                            <path d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z" />
                                                        </svg>
                                                    </button>
                                                </div>
                                                <div class="origin-top-right absolute right-0 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 hidden z-50" id="comment-dropdown-menu-{{ $comment->id }}">
                                                    <div class="py-1" role="menu" aria-orientation="vertical" aria-labelledby="comment-options-menu-{{ $comment->id }}">
                                                        <a href="{{ route('comments.edit', $comment) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900" role="menuitem">Edit</a>
                                                        <form action="{{ route('comments.destroy', $comment) }}" method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900" role="menuitem">Delete</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="flex items-center text-xs text-gray-500 mt-1">
                                        <span>{{ $comment->created_at->format('M d, Y h:i A') }}</span>

                                        @if($comment->isEdited())
                                            <span class="ml-2 italic">
                                                • edited by {{ $comment->editor->id === $comment->user_id ? $comment->user_display_name : 'admin: ' . $comment->editor->name }}
                                                at {{ $comment->edited_at->format('M d, Y h:i A') }}
                                            </span>
                                        @endif
                                    </div>

                                    <div class="text-sm text-gray-700 mt-2">
                                        @if($comment->isDeleted())
                                            <p class="italic text-gray-500">
                                                Comment deleted by {{ $comment->deleter->id === $comment->user_id ? $comment->user_display_name : 'admin: ' . $comment->deleter->name }}
                                                at {{ $comment->deleted_at->format('M d, Y h:i A') }}
                                            </p>
                                        @else
                                            <p>{{ $comment->content }}</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg shadow-xl mx-auto w-full lg:w-[70rem]">
    <div class="p-6">
        <h3 class="text-lg font-semibold mb-4">Add a comment</h3>
        <form action="{{ route('threads.comments.store', $thread) }}" method="POST" id="comment-form">
            @csrf
            <div class="flex flex-col w-full">
                <div class="flex items-start space-x-3">
                    <img src="{{ auth()->user()->profile?->profile_picture_url ?? auth()->user()->profile_photo_url }}" alt="{{ auth()->user()->name }}" class="w-8 h-8 rounded-full object-cover border-2 border-gray-300 flex-shrink-0">
                    <div class="flex-grow">
                        <textarea name="content" placeholder="Write a comment..." class="w-full border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 block shadow-sm sm:text-sm border rounded-md" rows="3"></textarea>
                    </div>
                </div>
                <div class="text-red-500 text-sm mt-2 ml-11 hidden"></div>
                @auth {{-- Only show anonymous option if user is logged in --}}
                <div class="mt-2 flex items-center ml-11">
                    <input type="hidden" name="is_anonymous" value="0"> {{-- Hidden field for unchecked state --}}
                    <input type="checkbox" name="is_anonymous" id="is_anonymous_comment" value="1" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                    <label for="is_anonymous_comment" class="ml-2 block text-sm text-gray-900">
                        Post anonymously
                    </label>
                </div>
                @endauth
                <div class="flex justify-end mt-2">
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Post Comment
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
        </div>
    </div>

    @push('scripts')
        <script src="{{ asset('js/word-counter.js') }}"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                function updateButtonState(button, isReacted) {
                    const type = button.dataset.type;
                    const icon = button.querySelector('svg');

                    if (isReacted) {
                        icon.style.color = type === 'upvote' ? "blue" : "red";
                    } else {
                        icon.style.color = "gray";
                    }
                }

                document.querySelectorAll('.react-btn').forEach(button => {
                    const type = button.dataset.type;
                    const threadId = button.dataset.thread;
                    const countSpan = button.querySelector(`.${type}-count`);

                    // Set initial state
                    // Only fetch reaction status if user is authenticated
                    @auth
                    fetch(`/threads/${threadId}/reaction-status`, {
                        method: 'GET',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        updateButtonState(button, data[type]);
                    })
                    .catch(error => console.error('Error:', error));
                    @endauth

                    // Add click event listener
                    button.addEventListener('click', function() {
                        // Only allow reaction if not disabled (i.e., user is authenticated)
                        if (this.disabled) {
                            return;
                        }

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
                                document.querySelector('.upvote-count').textContent = data.counts.upvotes;
                                document.querySelector('.heart-count').textContent = data.counts.hearts;

                                // Update button states
                                document.querySelectorAll('.react-btn').forEach(btn => {
                                    const btnType = btn.dataset.type;
                                    updateButtonState(btn, data.userReacted[btnType]);
                                });

                                // Add a subtle animation
                                countSpan.classList.add('scale-125');
                                setTimeout(() => countSpan.classList.remove('scale-125'), 200);
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
                const optionsMenu = document.getElementById('options-menu');
                const dropdownMenu = document.getElementById('dropdown-menu');

                if (optionsMenu && dropdownMenu) {
                    optionsMenu.addEventListener('click', function() {
                        dropdownMenu.classList.toggle('hidden');
                    });

                    // Close the dropdown when clicking outside
                    document.addEventListener('click', function(event) {
                        if (!optionsMenu.contains(event.target) && !dropdownMenu.contains(event.target)) {
                            dropdownMenu.classList.add('hidden');
                        }
                    });
                }
            });
            document.addEventListener('DOMContentLoaded', function() {
            // Handle all dropdown menus (both thread and comment)
            const dropdownButtons = document.querySelectorAll('[id^="options-menu-"], [id^="comment-options-menu-"]');

            dropdownButtons.forEach(button => {
                // Extract the ID suffix (could be a thread ID or comment ID)
                const idParts = button.id.split('-');
                const idSuffix = idParts.pop();
                const isComment = button.id.includes('comment');

                // Determine the correct dropdown ID
                const dropdownId = isComment
                    ? `comment-dropdown-menu-${idParts.includes('index') ? 'index-' : ''}${idSuffix}`
                    : `dropdown-menu-${idSuffix}`;

                const dropdownMenu = document.getElementById(dropdownId);

                if (button && dropdownMenu) {
                    button.addEventListener('click', function(event) {
                        event.stopPropagation();

                        // Close all other dropdowns first
                        document.querySelectorAll('[id^="dropdown-menu-"], [id^="comment-dropdown-menu-"]').forEach(menu => {
                            if (menu.id !== dropdownId) {
                                menu.classList.add('hidden');
                            }
                        });

                        // Toggle the current dropdown
                        dropdownMenu.classList.toggle('hidden');

                        // Ensure proper positioning and z-index
                        if (!dropdownMenu.classList.contains('hidden')) {
                            // Add these classes when showing the dropdown
                            dropdownMenu.classList.add('z-50');

                            // Check if dropdown would go off-screen to the right
                            const rect = dropdownMenu.getBoundingClientRect();
                            if (rect.right > window.innerWidth) {
                                dropdownMenu.style.right = '0';
                                dropdownMenu.style.left = 'auto';
                            }
                        }
                    });

                    // Close the dropdown when clicking outside
                    document.addEventListener('click', function() {
                        dropdownMenu.classList.add('hidden');
                    });
                }
            });
        });

        // --- New JavaScript for enabling/disabling submit buttons ---

        // For the single comment form on show.blade.php
        document.addEventListener('DOMContentLoaded', function() {
            const commentTextarea = document.querySelector('#comment-form textarea[name="content"]');
            const commentSubmitButton = document.querySelector('#comment-form button[type="submit"]');

            function updateCommentButtonState() {
                if (commentTextarea && commentSubmitButton) {
                    if (commentTextarea.value.trim().length > 0) {
                        commentSubmitButton.removeAttribute('disabled');
                        commentSubmitButton.classList.remove('opacity-50', 'cursor-not-allowed');
                    } else {
                        commentSubmitButton.setAttribute('disabled', 'disabled');
                        commentSubmitButton.classList.add('opacity-50', 'cursor-not-allowed');
                    }
                }
            }

            if (commentTextarea) {
                commentTextarea.addEventListener('input', updateCommentButtonState);
                updateCommentButtonState(); // Initial check on page load
            }
        });
        </script>
    @endpush
</x-app-layout>
