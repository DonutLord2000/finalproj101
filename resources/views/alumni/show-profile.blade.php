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
                        <img src="{{ $user->profile?->cover_picture_url }}" 
                            alt="Cover photo" 
                            class="w-full h-full object-cover rounded-t-lg">
                        @endif
                    </div>

                    <!-- Profile Picture -->
                    <div class="absolute -bottom-16 left-6">
                        <div class="w-32 h-32 rounded-full border-4 border-white bg-white overflow-hidden">
                            @if($user->profile?->profile_picture)
                                <img src="{{ Storage::disk('s3')->url($user->profile->profile_picture) }}"
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

            <!-- Verification Status Section (visible only to profile owner) -->
            @if(auth()->id() === $user->id)
                <div class="bg-white shadow rounded-lg">
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
        </div>
    </div>
</x-app-layout>

