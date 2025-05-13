<x-app-layout>
    @section('title', 'GRC - Threads')
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Discussion Area') }}
        </h2>
    </x-slot>

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
    </style>
    @endpush

    <div class="py-12 mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 mb-6 mx-auto w-full lg:w-[60rem]">
            <!-- Search and Filter Section -->
            <div class="mb-6">
                <!-- Simple search form without AJAX -->
                <form action="{{ route('threads.index') }}" method="GET" class="mb-4">
                    <div class="flex items-center space-x-2">
                        <div class="flex-1">
                            <input type="text" name="search" placeholder="Search posts or usernames..." class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50" value="{{ request('search') }}">
                        </div>
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Search
                        </button>
                    </div>
                </form>
                
                <div class="mb-4 flex flex-wrap items-center gap-2">
                    <span class="text-sm font-medium text-gray-700">Filters:</span>
                    
                    <!-- Role filter dropdown -->
                    <div class="relative inline-block text-left" x-data="{ open: false }">
                        <button @click="open = !open" type="button" class="inline-flex justify-between w-32 rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-100 focus:ring-indigo-500">
                            <span>
                                @if(request('role') == 'admin')
                                    Admin
                                @elseif(request('role') == 'student')
                                    Student
                                @elseif(request('role') == 'alumni')
                                    Alumni
                                @elseif(request('role') == 'guest')
                                    Guest
                                @else
                                    All Post
                                @endif
                            </span>
                            <svg class="-mr-1 ml-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                        <div x-show="open" @click.away="open = false" class="origin-top-right absolute right-0 mt-2 w-32 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-10">
                            <div class="py-1" role="menu" aria-orientation="vertical">
                                <a href="{{ route('threads.index', array_merge(request()->except('role'), ['role' => 'all'])) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ !request('role') || request('role') == 'all' ? 'bg-gray-100 font-medium' : '' }}" role="menuitem">All Post</a>
                                <a href="{{ route('threads.index', array_merge(request()->except('role'), ['role' => 'admin'])) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ request('role') == 'admin' ? 'bg-gray-100 font-medium' : '' }}" role="menuitem">Admin</a>
                                <a href="{{ route('threads.index', array_merge(request()->except('role'), ['role' => 'student'])) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ request('role') == 'student' ? 'bg-gray-100 font-medium' : '' }}" role="menuitem">Student</a>
                                <a href="{{ route('threads.index', array_merge(request()->except('role'), ['role' => 'alumni'])) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ request('role') == 'alumni' ? 'bg-gray-100 font-medium' : '' }}" role="menuitem">Alumni</a>
                                <a href="{{ route('threads.index', array_merge(request()->except('role'), ['role' => 'guest'])) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ request('role') == 'guest' ? 'bg-gray-100 font-medium' : '' }}" role="menuitem">Guest</a>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Sort filter dropdown -->
                    <div class="relative inline-block text-left" x-data="{ open: false }">
                        <button @click="open = !open" type="button" class="inline-flex justify-between w-32 rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-100 focus:ring-indigo-500">
                            <span>
                                @if(request('sort') == 'most_liked')
                                    Most Liked
                                @elseif(request('sort') == 'most_heart')
                                    Most Heart
                                @elseif(request('sort') == 'most_comment')
                                    Most Comments
                                @else
                                    Latest
                                @endif
                            </span>
                            <svg class="-mr-1 ml-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                        <div x-show="open" @click.away="open = false" class="origin-top-right absolute right-0 mt-2 w-32 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-10">
                            <div class="py-1" role="menu" aria-orientation="vertical">
                                <a href="{{ route('threads.index', array_merge(request()->except('sort'), ['sort' => 'latest'])) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ !request('sort') || request('sort') == 'latest' ? 'bg-gray-100 font-medium' : '' }}" role="menuitem">Latest</a>
                                <a href="{{ route('threads.index', array_merge(request()->except('sort'), ['sort' => 'most_liked'])) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ request('sort') == 'most_liked' ? 'bg-gray-100 font-medium' : '' }}" role="menuitem">Most Liked</a>
                                <a href="{{ route('threads.index', array_merge(request()->except('sort'), ['sort' => 'most_heart'])) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ request('sort') == 'most_heart' ? 'bg-gray-100 font-medium' : '' }}" role="menuitem">Most Heart</a>
                                <a href="{{ route('threads.index', array_merge(request()->except('sort'), ['sort' => 'most_comment'])) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ request('sort') == 'most_comment' ? 'bg-gray-100 font-medium' : '' }}" role="menuitem">Most Comments</a>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Time filter dropdown -->
                    <div class="relative inline-block text-left" x-data="{ open: false }">
                        <button @click="open = !open" type="button" class="inline-flex justify-between w-32 rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-100 focus:ring-indigo-500">
                            <span>
                                @if(request('time') == 'today')
                                    Today
                                @elseif(request('time') == 'week')
                                    This Week
                                @elseif(request('time') == 'month')
                                    This Month
                                @else
                                    All Time
                                @endif
                            </span>
                            <svg class="-mr-1 ml-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                        <div x-show="open" @click.away="open = false" class="origin-top-right absolute right-0 mt-2 w-32 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-10">
                            <div class="py-1" role="menu" aria-orientation="vertical">
                                <a href="{{ route('threads.index', array_merge(request()->except('time'), ['time' => 'all'])) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ !request('time') || request('time') == 'all' ? 'bg-gray-100 font-medium' : '' }}" role="menuitem">All Time</a>
                                <a href="{{ route('threads.index', array_merge(request()->except('time'), ['time' => 'today'])) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ request('time') == 'today' ? 'bg-gray-100 font-medium' : '' }}" role="menuitem">Today</a>
                                <a href="{{ route('threads.index', array_merge(request()->except('time'), ['time' => 'week'])) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ request('time') == 'week' ? 'bg-gray-100 font-medium' : '' }}" role="menuitem">This Week</a>
                                <a href="{{ route('threads.index', array_merge(request()->except('time'), ['time' => 'month'])) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ request('time') == 'month' ? 'bg-gray-100 font-medium' : '' }}" role="menuitem">This Month</a>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Clear filters button -->
                    @if(request('role') || request('sort') || request('time') || request('search'))
                        <a href="{{ route('threads.index') }}" class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-xs leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            Clear Filters
                        </a>
                    @endif
                </div>
            </div>

            <!-- Create Post Form -->
            <h3 class="text-lg font-semibold mb-4">Create a new post</h3>
            <form action="{{ route('threads.store') }}" method="POST" id="thread-form">
                @csrf
                <div class="mb-4">
                    <label for="content" class="block text-sm font-medium text-gray-700">Content</label>
                    <textarea name="content" id="content" rows="3" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"></textarea>
                    <div class="text-red-500 text-sm mt-1 hidden"></div>
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
                                <img src="{{ $thread->user->profile?->profile_picture_url ?? $thread->user->profile_photo_url }}" alt="{{ $thread->user->name }}" class="w-10 h-10 rounded-full object-cover border-2 border-gray-300">
                                <div>
                                    <h4 class="text-lg font-semibold text-gray-900">
                                        <a href="{{ route('alumni.profile.show', $thread->user) }}" class="hover:underline">
                                            {{ $thread->user->name }}
                                        </a>
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
                            <img src="{{ $comment->user->profile?->profile_picture_url ?? $comment->user->profile_photo_url }}" alt="{{ $comment->user->name }}" class="w-8 h-8 rounded-full object-cover border-2 border-gray-300">
                            <div class="flex-grow">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <p class="font-semibold text-gray-900">
                                            <a href="{{ route('alumni.profile.show', $comment->user) }}" class="hover:underline">
                                                {{ $comment->user->name }}
                                            </a>
                                            @php
                                                $bgColor = match($comment->user->role) {
                                                    'alumni' => 'bg-green-200 text-green-800',
                                                    'admin' => 'bg-red-200 text-red-800',
                                                    'student' => 'bg-blue-200 text-blue-800',
                                                    default => 'bg-gray-200 text-gray-700',
                                                };
                                            @endphp

                                            <span class="text-xs {{ $bgColor }} px-2 py-1 rounded-full ml-2">
                                                {{ $comment->user->role }}
                                            </span>
                                        </p>
                                        <p class="text-xs text-gray-500 mt-1">
                                            {{ $comment->created_at->format('M d, Y h:i A') }}
                                            
                                            @if($comment->isEdited())
                                                <span class="ml-2 italic">
                                                    • edited by {{ $comment->editor->id === $comment->user_id ? $comment->user->name : 'admin: ' . $comment->editor->name }} 
                                                    at {{ $comment->edited_at->format('M d, Y h:i A') }}
                                                </span>
                                            @endif
                                        </p>
                                    </div>
                                    
                                    <!-- Moved dropdown to top right -->
                                    @if((auth()->user()->id === $comment->user_id || auth()->user()->role === 'admin') && !$comment->isDeleted())
                                        <div class="relative">
                                            <button type="button" class="inline-flex justify-center w-6 h-6 rounded-full border border-gray-300 shadow-sm bg-white text-xs font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-1 focus:ring-offset-1 focus:ring-offset-gray-100 focus:ring-indigo-500" id="comment-options-menu-index-{{ $comment->id }}">
                                                <svg class="w-4 h-4 m-auto" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                    <path d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z" />
                                                </svg>
                                            </button>
                                            <!-- Dropdown positioned to open below and aligned to the right -->
                                            <div class="origin-top-right absolute right-0 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 hidden" id="comment-dropdown-menu-index-{{ $comment->id }}" style="z-index: 9999;">
                                                <div class="py-1" role="menu" aria-orientation="vertical" aria-labelledby="comment-options-menu-index-{{ $comment->id }}">
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
                                
                                <div class="text-sm text-gray-700 mt-2">
                                    @if($comment->isDeleted())
                                        <p class="italic text-gray-500">
                                            Comment deleted by {{ $comment->deleter->id === $comment->user_id ? $comment->user->name : 'admin: ' . $comment->deleter->name }}
                                            at {{ $comment->deleted_at->format('M d, Y h:i A') }}
                                        </p>
                                    @else
                                        <p>{{ $comment->content }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                        <form action="{{ route('threads.comments.store', $thread) }}" method="POST" class="mt-4">
                            @csrf
                            <div class="flex flex-col w-full">
                                <div class="flex items-start space-x-3">
                                    <img src="{{ auth()->user()->profile?->profile_picture_url ?? auth()->user()->profile_photo_url }}" alt="{{ auth()->user()->name }}" class="w-8 h-8 rounded-full object-cover border-2 border-gray-300 flex-shrink-0">
                                    <div class="flex-grow">
                                        <textarea name="content" placeholder="Write a comment..." class="w-full border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 block shadow-sm sm:text-sm border rounded-md"></textarea>
                                    </div>
                                </div>
                                <div class="text-red-500 text-sm mt-2 ml-11 hidden"></div>
                                <div class="flex justify-end mt-2">
                                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        Comment
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            @endforeach

            {{ $threads->links() }}
        </div>
    </div>
  
  @push('scripts')
      <script src="{{ asset('js/word-counter.js') }}"></script>
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
          document.addEventListener('DOMContentLoaded', function() {
          // Handle all dropdown menus
          const dropdownButtons = document.querySelectorAll('[id^="comment-options-menu-"]');
          
          dropdownButtons.forEach(button => {
              const id = button.id;
              let dropdownId;
              
              // Determine the correct dropdown ID based on the button ID
              if (id.startsWith('comment-options-menu-index-')) {
                  dropdownId = id.replace('comment-options-menu-index-', 'comment-dropdown-menu-index-');
              } else if (id.startsWith('comment-options-menu-')) {
                  dropdownId = id.replace('comment-options-menu-', 'comment-dropdown-menu-');
              } else if (id.startsWith('options-menu-')) {
                  dropdownId = id.replace('options-menu-', 'dropdown-menu-');
              }
              
              const dropdownMenu = document.getElementById(dropdownId);
              
              if (button && dropdownMenu) {
                  button.addEventListener('click', function(event) {
                      event.stopPropagation();
                      
                      // Close all other dropdowns first
                      document.querySelectorAll('.comment-dropdown-menu, [id^="dropdown-menu-"]').forEach(menu => {
                          if (menu.id !== dropdownId) {
                              menu.classList.add('hidden');
                          }
                      });
                      
                      // Toggle the current dropdown
                      dropdownMenu.classList.toggle('hidden');
                      
                      // Check if dropdown would go off-screen to the right
                      if (!dropdownMenu.classList.contains('hidden')) {
                          const rect = dropdownMenu.getBoundingClientRect();
                          const parentRect = button.parentElement.getBoundingClientRect();
                          
                          // If dropdown would go off right edge of screen
                          if (rect.right > window.innerWidth) {
                              dropdownMenu.classList.add('dropdown-right');
                          } else {
                              dropdownMenu.classList.remove('dropdown-right');
                          }
                          
                          // If dropdown would go off bottom of screen, position it above the button
                          if (rect.bottom > window.innerHeight) {
                              dropdownMenu.style.bottom = parentRect.height + 'px';
                              dropdownMenu.style.top = 'auto';
                          } else {
                              dropdownMenu.style.top = '';
                              dropdownMenu.style.bottom = '';
                          }
                      }
                  });
              }
              
              // Close dropdowns when clicking outside
              document.addEventListener('click', function(event) {
                  if (!button.contains(event.target)) {
                      dropdownMenu.classList.add('hidden');
                  }
              });
          });
      });
      </script>
  @endpush
</x-app-layout>
