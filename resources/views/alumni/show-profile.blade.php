<x-app-layout>
    @section('title', 'GRC - Alumni')
    <div class="min-h-screen bg-gray-100">
        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
            <!-- Profile Header Section -->
            <div class="bg-white shadow rounded-lg mb-6">
                <div class="relative">
                    <!-- Cover Photo -->
                    <div class="h-60 w-full bg-gray-300 rounded-t-lg">
                        @if($user->profile?->cover_picture)
                        <img src="{{ Storage::disk('s3')->temporaryUrl($user->profile->cover_picture, now()->addMinutes(5)) }}" 
                            alt="Cover photo" 
                            class="w-full h-full object-cover rounded-t-lg">
                        @endif
                    </div>

                    <!-- Profile Picture -->
                    <div class="absolute -bottom-16 left-6">
                        <div class="w-32 h-32 rounded-full border-4 border-white bg-white overflow-hidden">
                                    @if($user->profile?->profile_picture)
                                        <img src="{{ Storage::disk('s3')->temporaryUrl($user->profile->profile_picture, now()->addMinutes(5)) }}"
                                            alt="{{ $user->name }}"
                                            class="w-full h-full object-cover">
                                    @else
                                        <img src="{{ asset('storage/profile-photos/default.png') }}"
                                            alt="{{ $user->name }}"
                                            class="w-full h-full object-cover">
                                    @endif
                        </div>
                    </div>
                </div>

                <!-- Profile Info -->
                <div class="pt-20 px-6 pb-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="flex items-center gap-2">
                            <h1 class="text-2xl font-bold text-gray-900">{{ $user->name }}</h1>
                            @if($user->profile?->is_verified)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                    <svg class="w-4 h-4 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                    Verified Alumni
                                </span>
                            @endif
                            </div>
                            <p class="text-gray-600 mt-1">{{ $user->profile?->address ?: 'Location not specified' }}</p>
                            <p class="text-gray-600 mt-1">{{ $user->email }}</p>
                            @if($user->profile?->contact_number)
                                <p class="text-gray-600 mt-1">{{ $user->profile->contact_number }}</p>
                            @endif
                        </div>
                        <div class="flex items-center space-x-3">
                            @if(auth()->id() === $user->id)
                                <a href="{{ route('profile.edit') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring focus:ring-gray-300 disabled:opacity-25 transition">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                    Edit Profile
                                </a>
                            @endif
                        </div>
                    </div>
                    @if($user->profile?->bio)
                        <div class="mt-6">
                            <h2 class="text-lg font-semibold text-gray-900">About</h2>
                            <p class="mt-2 text-gray-600">{{ $user->profile->bio }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Verification Status Section (visible only to profile owner) -->
            @if(auth()->id() === $user->id)
                <div class="bg-white shadow rounded-lg mb-6">
                    <div class="p-6">
                        <h2 class="text-xl font-bold text-gray-900 mb-4">Verification Status</h2>
                        @if($user->profile?->is_verified)
                            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4" role="alert">
                                <p class="font-bold">Verified</p>
                                <p>Your account has been verified as an alumni.</p>
                            </div>
                        @else
                            @php
                                $pendingRequest = $user->verificationRequests->where('status', 'pending')->first();
                                $rejectedRequest = $user->verificationRequests->where('status', 'rejected')->first();
                            @endphp

                            @if($pendingRequest)
                                <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4" role="alert">
                                    <p class="font-bold">Pending Verification</p>
                                    <p>Your verification request is currently being reviewed.</p>
                                </div>
                            @elseif($rejectedRequest)
                                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4" role="alert">
                                    <p class="font-bold">Verification Rejected</p>
                                    <p>Your previous verification request was rejected. You can submit a new request on your profile edit page.</p>
                                </div>
                            @else
                                <div class="bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4" role="alert">
                                    <p class="font-bold">Not Verified</p>
                                    <p>You haven't submitted a verification request yet. You can do so on your profile edit page.</p>
                                </div>
                            @endif
                        @endif
                    </div>
                </div>
            @endif    

            <!-- Experience Section -->
            <div class="bg-white shadow rounded-lg mb-6">
                <div class="p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Experience</h2>
                    @forelse($user->experiences as $experience)
                        <div class="mb-4 p-4 bg-gray-50 rounded-lg">
                            <h3 class="font-semibold text-lg">{{ $experience->title }}</h3>
                            <p class="text-gray-600">{{ $experience->company }}</p>
                            <p class="text-sm text-gray-500">
                                {{ $experience->employment_type }} · {{ $experience->location }} ({{ $experience->location_type }})
                            </p>
                            <p class="text-sm text-gray-500">
                                {{ $experience->start_date->format('M Y') }} - 
                                {{ $experience->current_role ? 'Present' : $experience->end_date->format('M Y') }}
                            </p>
                            @if($experience->description)
                                <p class="mt-2 text-gray-600">{{ $experience->description }}</p>
                            @endif
                        </div>
                    @empty
                        <p class="text-gray-600">No experience information available.</p>
                    @endforelse
                </div>
            </div>

            <!-- Education Section -->
            <div class="bg-white shadow rounded-lg mb-6">
                <div class="p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Education</h2>
                    @forelse($user->education as $education)
                        <div class="mb-4 p-4 bg-gray-50 rounded-lg">
                            <h3 class="font-semibold text-lg">{{ $education->school }}</h3>
                            <p class="text-gray-600">{{ $education->degree }} · {{ $education->field_of_study }}</p>
                            <p class="text-sm text-gray-500">
                                {{ $education->start_date->format('M Y') }} - 
                                {{ $education->end_date ? $education->end_date->format('M Y') : 'Present' }}
                            </p>
                            @if($education->grade)
                                <p class="text-sm text-gray-600">Grade: {{ $education->grade }}</p>
                            @endif
                            @if($education->activities)
                                <p class="mt-2 text-gray-600">{{ $education->activities }}</p>
                            @endif
                        </div>
                    @empty
                        <p class="text-gray-600">No education information available.</p>
                    @endforelse
                </div>
            </div>

            <!-- User's Threads Section -->
            <div class="bg-white shadow rounded-lg mb-6 mt-6">
                <div class="p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Posts</h2>
                    
                    @php
                        $threads = \App\Models\Thread::where('user_id', $user->id)
                            ->withCount([
                                'comments',
                                'reactions as upvotes_count' => function ($query) {
                                    $query->where('type', 'upvote');
                                },
                                'reactions as hearts_count' => function ($query) {
                                    $query->where('type', 'heart');
                                },
                            ])
                            ->with('user')
                            ->latest()
                            ->paginate(5);
                    @endphp
                    
                    @if($threads->count() > 0)
                        <div class="space-y-6">
                            @foreach($threads as $thread)
                                <div class="bg-gray-50 overflow-hidden rounded-lg" x-data="{ isCommentsOpen: false }">
                                    <div class="p-4">
                                        <div class="flex justify-between items-start mb-4">
                                            <div class="flex items-center space-x-3">
                                                <img src="{{ $thread->display_profile_picture_url }}" alt="{{ $thread->user_display_name }}" class="w-10 h-10 rounded-full">
                                                <div>
                                                    <h4 class="text-lg font-semibold text-gray-900">
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
                                                            'guest' => 'inline-block px-2 py-1 bg-gray-500 text-gray-800 rounded',
                                                            default => 'bg-gray-200 text-gray-700',
                                                        };
                                                        @endphp

                                                        <span class="text-sm font-normal {{ $bgColor }} px-2 py-1 rounded-full ml-2">
                                                            {{ $thread->display_role }}
                                                        </span>
                                                    </h4>
                                                    <p class="text-sm text-gray-500">
                                                        {{ $thread->created_at->format('M d, Y h:i A') }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mt-3 text-gray-700">
                                            <a href="{{ route('threads.show', $thread) }}" class="block">{{ $thread->content }}</a>
                                        </div>
                                    </div>
                                    <div class="px-4 py-3 bg-gray-100 flex justify-between items-center">
                                        <div class="flex space-x-4">
                                            @php
                                                $user = auth()->user();
                                                $userUpvoted = $user ? $thread->reactions()->where('user_id', $user->id)->where('type', 'upvote')->exists() : false;
                                                $userHearted = $user ? $thread->reactions()->where('user_id', $user->id)->where('type', 'heart')->exists() : false;
                                            @endphp

                                            <button class="react-btn flex items-center space-x-1 text-gray-500" 
                                                    data-type="upvote" 
                                                    data-thread="{{ $thread->id }}" 
                                                    data-reacted="{{ $userUpvoted ? 'true' : 'false' }}"
                                                    @guest disabled @endguest>
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 upvote-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5" />
                                                </svg>
                                                <span class="upvote-count-{{ $thread->id }}">{{ $thread->upvotes }}</span>
                                            </button>
                                            
                                            <button class="react-btn flex items-center space-x-1 text-gray-500 ml-2" 
                                                    data-type="heart" 
                                                    data-thread="{{ $thread->id }}" 
                                                    data-reacted="{{ $userHearted ? 'true' : 'false' }}"
                                                    @guest disabled @endguest>
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
                                    <div x-show="isCommentsOpen" class="px-4 py-3 bg-gray-100">
                                        @foreach ($thread->comments as $comment)
                                            <div class="flex items-start space-x-3 mb-4">
                                                <img src="{{ $comment->display_profile_picture_url }}" alt="{{ $comment->user_display_name }}" class="w-8 h-8 rounded-full">
                                                <div>
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
                                                                'alumni' => 'bg-green-200 text-green-800',
                                                                'admin' => 'bg-red-200 text-red-800',
                                                                'student' => 'bg-blue-200 text-blue-800',
                                                                'guest' => 'bg-gray-200 text-gray-700',
                                                                default => 'bg-gray-200 text-gray-700',
                                                            };
                                                        @endphp

                                                        <span class="text-xs {{ $bgColor }} px-2 py-1 rounded-full ml-2">
                                                            {{ $comment->display_role }}
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
                                                <img src="{{ auth()->user()->profile?->profile_picture_url ?? auth()->user()->profile_photo_url }}" alt="{{ auth()->user()->name }}" class="w-8 h-8 rounded-full">
                                                <input type="text" name="content" placeholder="Write a comment..." class="flex-grow border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border rounded-md">
                                                @auth {{-- Only show anonymous option if user is logged in --}}
                                                <div class="mt-2 flex items-center">
                                                    <input type="hidden" name="is_anonymous" value="0"> {{-- Hidden field for unchecked state --}}
                                                    <input type="checkbox" name="is_anonymous" id="is_anonymous_comment_profile_{{ $thread->id }}" value="1" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                                    <label for="is_anonymous_comment_profile_{{ $thread->id }}" class="ml-2 block text-sm text-gray-900">
                                                        Post anonymously
                                                    </label>
                                                </div>
                                                @endauth
                                                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                                    Post
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <div class="mt-4">
                            {{ $threads->links() }}
                        </div>
                    @else
                        <p class="text-gray-600">No posts available.</p>
                    @endif
                </div>
            </div>

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
            @endpush
        </div>
    </div>
</x-app-layout>
