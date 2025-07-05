<x-app-layout>
    @section('title', 'GRC - User Profile')
    <!-- EULA Modal -->
    <x-eula-modal :show="$showEula ?? false" />

    <div class="min-h-screen bg-gray-100 py-10 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <!-- Profile Header Section -->
            <div class="mt-6 bg-white shadow rounded-lg">
                <div class="bg-white rounded-lg">
                    <div class="relative">
                        <!-- Cover Photo -->
                        <div class="h-60 w-full bg-gray-300 relative rounded-lg">
                            @if($user->profile?->cover_picture)
                                <img src="{{ Storage::disk('s3')->temporaryUrl($user->profile->cover_picture, now()->addMinutes(5)) }}"
                                    alt="Cover photo" 
                                    class="w-full h-full object-cover rounded-t-lg">
                            @endif
                            <label for="cover_upload" class="absolute right-4 bottom-4 cursor-pointer">
                                <span class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    Change Cover
                                </span>
                            </label>
                            <form id="cover-form" action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="hidden">
                                @csrf
                                @method('PUT')
                                <input type="file" id="cover_upload" name="cover_picture" class="hidden" onchange="this.form.submit()">
                            </form>
                        </div>

                        <!-- Profile Picture -->
                        <div class="absolute -bottom-16 left-6">
                            <div class="relative">
                                <div class="w-32 h-32 rounded-full border-4 border-white bg-white overflow-hidden">
                                    @if($user->profile?->profile_picture)
                                        <img src="{{ Storage::disk('s3')->temporaryUrl($user->profile->profile_picture, now()->addMinutes(5)) }}"
                                            alt="{{ $user->name }}"
                                            class="w-full h-full object-cover">
                                    @else
                                        <img src="{{ $user->profile?->profile_picture_url }}"
                                            alt="{{ $user->name }}"
                                            class="w-full h-full object-cover">
                                    @endif
                                </div>
                                <label for="profile_upload" class="absolute bottom-0 right-0 cursor-pointer">
                                    <span class="flex items-center justify-center w-8 h-8 rounded-full bg-white border border-gray-300 shadow-sm hover:bg-gray-50">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                        </svg>
                                    </span>
                                </label>
                                <form id="profile-form" action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="hidden">
                                    @csrf
                                    @method('PUT')
                                    <input type="file" id="profile_upload" name="profile_picture" class="hidden" onchange="this.form.submit()">
                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- Profile Info -->
                    <div class="pt-20 px-6 pb-6">
                        <div class="flex items-start justify-between mb-4">
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
                            </div>
                            <button type="button" onclick="document.getElementById('edit-form').classList.toggle('hidden')" class="p-2 text-gray-400 hover:text-gray-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                </svg>
                            </button>
                        </div>

                        <!-- Always visible profile information -->
                        <div class="mt-4 space-y-3 bg-gray-50 p-4 rounded-lg">
                            <div class="flex items-start">
                                <div class="w-24 flex-shrink-0 text-gray-500 font-medium">Location:</div>
                                <div class="flex-grow text-gray-800">{{ $user->profile?->address ?: 'Not specified' }}</div>
                            </div>
                            <div class="flex items-start">
                                <div class="w-24 flex-shrink-0 text-gray-500 font-medium">Contact:</div>
                                <div class="flex-grow text-gray-800">{{ $user->profile?->contact_number ?: 'Not specified' }}</div>
                            </div>
                            <div class="flex items-start">
                                <div class="w-24 flex-shrink-0 text-gray-500 font-medium">Bio:</div>
                                <div class="flex-grow text-gray-800">{{ $user->profile?->bio ?: 'No bio provided' }}</div>
                            </div>
                        </div>

                        <!-- Verification Section -->
                        <div class="mt-6 bg-white shadow rounded-lg">
                            <div class="p-6">
                                <h2 class="text-xl font-bold text-gray-900 mb-4">Verification Status</h2>
                                
                                @if(!$user->profile)
                                    <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-4" role="alert">
                                        <p class="font-bold">Profile Not Set Up</p>
                                        <p>Please complete your profile setup first.</p>
                                    </div>
                                @elseif($user->profile?->is_verified)
                                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                                        <p class="font-bold">Verified</p>
                                        <p>Your account has been verified.</p>
                                    </div>
                                @else
                                    @php
                                        $pendingRequest = $user->verificationRequests()->where('status', 'pending')->first();
                                        $rejectedRequest = $user->verificationRequests()->where('status', 'rejected')->latest()->first();
                                    @endphp
                    
                                    @if($pendingRequest)
                                        <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-4" role="alert">
                                            <p class="font-bold">Pending Verification</p>
                                            <p>Your verification request is currently being reviewed.</p>
                                            <form action="{{ route('verification.cancel', $pendingRequest) }}" method="POST" class="mt-2">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-800 underline">Cancel Request</button>
                                            </form>
                                        </div>
                                    @elseif($rejectedRequest)
                                        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
                                            <p class="font-bold">Verification Rejected</p>
                                            <p>Your previous verification request was rejected. You can submit a new request.</p>
                                            @if($rejectedRequest->admin_notes)
                                                <p class="mt-2"><strong>Reason:</strong> {{ $rejectedRequest->admin_notes }}</p>
                                            @endif
                                        </div>
                                    @endif
                    
                                    @if(!$pendingRequest)
                                        @if(!$user->profile?->address)
                                            <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-4" role="alert">
                                                <p class="font-bold">Location Required</p>
                                                <p>You must add your location before requesting verification. <button type="button" onclick="document.getElementById('edit-form').classList.remove('hidden'); document.getElementById('address').focus();" class="text-blue-600 hover:underline">Add location now</button></p>
                                            </div>
                                        @else
                                            <button type="button" onclick="document.getElementById('verification-form').classList.toggle('hidden')" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                                Request Verification
                                            </button>
                                        @endif
                    
                                        <form id="verification-form" action="{{ route('verification.request') }}" method="POST" enctype="multipart/form-data" class="mt-4 p-4 bg-gray-50 rounded-lg hidden">
                                            @csrf
                                            <h3 class="text-lg font-medium text-gray-900 mb-4">Request Verification</h3>
                                            <div class="space-y-4">
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700">Upload Verification Documents</label>
                                                    <p class="text-sm text-gray-500 mb-2">Please upload government-issued ID or other relevant documentation</p>
                                                    <input type="file" name="documents[]" multiple required class="block w-full text-sm text-gray-500
                                                        file:mr-4 file:py-2 file:px-4
                                                        file:rounded-full file:border-0
                                                        file:text-sm file:font-semibold
                                                        file:bg-blue-50 file:text-blue-700
                                                        hover:file:bg-blue-100
                                                    "/>
                                                    <div id="selected-files" class="mt-2 space-y-2"></div>
                                                </div>
                                                <div class="flex justify-end">
                                                    <x-button type="submit">
                                                        Submit Verification Request
                                                    </x-button>
                                                </div>
                                            </div>
                                        </form>
                                    @endif
                                @endif
                            </div>
                        </div>
                        
                        <!-- Debug Information -->
                        @if(session('error'))
                            <div class="mt-4 p-4 bg-red-100 text-red-700 rounded-lg">
                                <h4 class="font-bold">Error:</h4>
                                <p>{{ session('error') }}</p>
                            </div>
                        @endif
                        
                        @if($errors->any())
                            <div class="mt-4 p-4 bg-red-100 text-red-700 rounded-lg">
                                <h4 class="font-bold">Validation Error:</h4>
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <!-- Edit Profile Form -->
                        <form id="edit-form" action="{{ route('profile.update') }}" method="POST" class="mt-4 space-y-4 hidden">
                            @csrf
                            @method('PUT')
                            <div>
                                <x-label for="address" value="{{ __('Location') }}" />
                                <x-input id="address" name="address" type="text" class="mt-1 block w-full" :value="old('address', $user->profile?->address)" />
                                <x-input-error for="address" class="mt-2" />
                            </div>

                            <div>
                                <x-label for="contact_number" value="{{ __('Contact Number') }}" />
                                <x-input id="contact_number" name="contact_number" type="text" class="mt-1 block w-full" :value="old('contact_number', $user->profile?->contact_number)" />
                                <x-input-error for="contact_number" class="mt-2" />
                            </div>

                            <div>
                                <x-label for="bio" value="{{ __('Bio') }}" />
                                <textarea id="bio" name="bio" rows="3" class="mt-1 block w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">{{ old('bio', $user->profile?->bio) }}</textarea>
                                <x-input-error for="bio" class="mt-2" />
                            </div>

                            <div class="flex justify-end">
                                <x-button>
                                    {{ __('Save Changes') }}
                                </x-button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Career Path Prediction Section -->
            <div class="mt-6 bg-white shadow rounded-lg">
                <div class="p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Career Path Prediction & Insights</h2>

                    <div class="flex flex-col items-center justify-center gap-4 mb-4">
                        <button id="predict-button" class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-md shadow-sm disabled:opacity-50 disabled:cursor-not-allowed transition-colors duration-200">
                            Get Prediction & Insights
                        </button>
                        <div class="w-full sm:w-1/2 md:w-1/3">
                            <label for="prediction_year_select" class="sr-only">Compare with alumni from:</label>
                            <select id="prediction_year_select" name="prediction_year" class="mt-1 block w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <option value="all" selected>All Graduates</option>
                                @foreach($alumniYears as $year)
                                    <option value="{{ $year }}">{{ $year }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div id="prediction-loading" class="mt-6 hidden flex items-center justify-center text-blue-600 font-medium text-lg">
                        <svg class="animate-spin -ml-1 mr-3 h-6 w-6 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Generating your personalized career path and insights...
                    </div>

                    <div id="prediction-results" class="mt-6 p-6 bg-gray-50 rounded-lg shadow-inner hidden">
                        <h3 class="font-semibold text-lg mb-2 text-gray-900">Your Personal Insights:</h3>
                        <p id="insights-text" class="text-gray-800 leading-relaxed mb-6 whitespace-pre-wrap"></p>

                        <h3 class="font-semibold text-xl mb-4 text-gray-900">Your Predicted Career Path:</h3>
                        <p id="prediction-text" class="text-gray-800 leading-relaxed mb-6 whitespace-pre-wrap"></p>

                        <div id="chart-container" class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                            <div class="h-64"> {{-- Adjusted height for smaller charts --}}
                                <h4 class="font-semibold text-lg mb-3 text-gray-800">Alumni Industry Distribution</h4>
                                <canvas id="pieChart"></canvas>
                            </div>
                            <div class="h-64"> {{-- Adjusted height for smaller charts --}}
                                <h4 class="font-semibold text-lg mb-3 text-gray-800">Alumni Job Title Trends</h4>
                                <canvas id="barChart"></canvas>
                            </div>
                        </div>
                    </div>
                    <div id="prediction-error" class="mt-6 p-4 bg-red-100 border-l-4 border-red-500 text-red-700 rounded-lg hidden" role="alert"></div>
                </div>
            </div>

            <!-- Experience Section -->
            <div class="mt-6 bg-white shadow rounded-lg">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-xl font-bold text-gray-900">Experience</h2>
                        <button type="button" onclick="document.getElementById('add-experience-form').classList.toggle('hidden')" class="text-blue-600 hover:text-blue-700">
                            + Add experience
                        </button>
                    </div>

                    <div id="add-experience-form" class="hidden mb-6 p-4 bg-gray-50 rounded-lg">
                        <form action="{{ route('experience.add') }}" method="POST" class="space-y-4">
                            @csrf
                            <!-- Experience form fields from previous code -->
                            <div>
                                <x-label for="title" value="{{ __('Title') }}" />
                                <x-input id="title" name="title" type="text" class="mt-1 block w-full" required />
                            </div>

                            <div>
                                <x-label for="company" value="{{ __('Company') }}" />
                                <x-input id="company" name="company" type="text" class="mt-1 block w-full" required />
                            </div>

                            <div>
                                <x-label for="employment_type" value="{{ __('Employment Type') }}" />
                                <select id="employment_type" name="employment_type" class="mt-1 block w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                    <option value="">Select Type</option>
                                    <option value="Full-time">Full-time</option>
                                    <option value="Part-time">Part-time</option>
                                    <option value="Contract">Contract</option>
                                    <option value="Internship">Internship</option>
                                </select>
                            </div>
                        
                            <div>
                                <x-label for="location" value="{{ __('Location') }}" />
                                <x-input id="location" name="location" type="text" class="mt-1 block w-full" required />
                            </div>
                        
                            <div>
                                <x-label for="location_type" value="{{ __('Location Type') }}" />
                                <select id="location_type" name="location_type" class="mt-1 block w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                    <option value="">Select Type</option>
                                    <option value="On-site">On-site</option>
                                    <option value="Hybrid">Hybrid</option>
                                    <option value="Remote">Remote</option>
                                </select>
                            </div>

                            <div class="flex items-center mt-4">
                                <input id="current_role" name="current_role" type="checkbox" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                <label for="current_role" class="ml-2 text-sm text-gray-600">I currently work here</label>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <x-label for="start_date" value="{{ __('Start Date') }}" />
                                    <x-input id="start_date" name="start_date" type="date" class="mt-1 block w-full" required />
                                </div>

                                <div>
                                    <x-label for="end_date" value="{{ __('End Date') }}" />
                                    <x-input id="end_date" name="end_date" type="date" class="mt-1 block w-full" :disabled="old('current_role')" />
                                </div>
                            </div>

                            <div>
                                <x-label for="description" value="{{ __('Description') }}" />
                                <textarea id="description" name="description" rows="3" class="mt-1 block w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"></textarea>
                            </div>

                            <div class="flex justify-end">
                                <x-button type="submit">
                                    Add Experience
                                </x-button>
                            </div>
                        </form>
                    </div>

                    <!-- Experience List -->
                    <div class="grid grid-cols-2 gap-4 ">
                        @foreach($user->experiences as $experience)
                            <div class="mb-4 p-4 bg-gray-50 hover:bg-gray-100 rounded-lg">
                                <div class="flex justify-between">
                                    <div>
                                        <h3 class="font-semibold text-lg">{{ $experience->title }}</h3>
                                        <p class="text-gray-600">{{ $experience->company }}</p>
                                        <p class="text-sm text-gray-500">
                                            {{ $experience->employment_type }} · {{ $experience->location }} ({{ $experience->location_type }})
                                        </p>
                                        <p class="text-sm text-gray-500">
                                            {{ $experience->start_date->format('M Y') }} - 
                                            {{ $experience->current_role ? 'Present' : ($experience->end_date ? $experience->end_date->format('M Y') : 'Present') }}
                                        </p>
                                        @if($experience->description)
                                            <p class="mt-2 text-gray-600">{{ $experience->description }}</p>
                                        @endif
                                    </div>
                                    <div class="flex space-x-2">
                                        <form action="{{ route('profile.destroyExperience', $experience->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this experience?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-gray-400 hover:text-gray-500">
                                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Education Section -->
            <div class="mt-6 bg-white shadow rounded-lg">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-xl font-bold text-gray-900">Education</h2>
                        <button type="button" onclick="document.getElementById('add-education-form').classList.toggle('hidden')" class="text-blue-600 hover:text-blue-700">
                            + Add education
                        </button>
                    </div>

                    <div id="add-education-form" class="hidden mb-6 p-4 bg-gray-50 rounded-lg">
                        <form action="{{ route('education.add') }}" method="POST" class="space-y-4">
                            @csrf
                            <div>
                                <x-label for="school" value="{{ __('School') }}" />
                                <x-input id="school" name="school" type="text" class="mt-1 block w-full" required />
                            </div>

                            <div>
                                <x-label for="degree" value="{{ __('Degree') }}" />
                                <x-input id="degree" name="degree" type="text" class="mt-1 block w-full" required />
                            </div>

                            <div>
                                <x-label for="field_of_study" value="{{ __('Field of Study') }}" />
                                <x-input id="field_of_study" name="field_of_study" type="text" class="mt-1 block w-full" required />
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <x-label for="start_date" value="{{ __('Start Date') }}" />
                                    <x-input id="start_date" name="start_date" type="date" class="mt-1 block w-full" required />
                                </div>

                                <div>
                                    <x-label for="end_date" value="{{ __('End Date') }}" />
                                    <x-input id="end_date" name="end_date" type="date" class="mt-1 block w-full" />
                                </div>
                            </div>

                            <div>
                                <x-label for="grade" value="{{ __('Grade') }}" />
                                <x-input id="grade" name="grade" type="text" class="mt-1 block w-full" />
                            </div>

                            <div>
                                <x-label for="activities" value="{{ __('Activities and Societies') }}" />
                                <textarea id="activities" name="activities" rows="3" class="mt-1 block w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"></textarea>
                            </div>

                            <div class="flex justify-end">
                                <x-button type="submit">
                                    Add Education
                                </x-button>
                            </div>
                        </form>
                    </div>

                    <!-- Education List -->
                    @foreach($user->education as $education)
                        <div class="mb-4 p-4 bg-gray-50 hover:bg-gray-100 rounded-lg">
                            <div class="flex justify-between">
                                <div>
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
                                <div class="flex space-x-2">
                                    <form action="{{ route('profile.destroyEducation', $education->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this education entry?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-gray-400 hover:text-gray-500">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <!-- User's Threads Section -->
            <div class="mt-6 bg-white shadow rounded-lg">
                <div class="p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Your Posts</h2>
                    
                    @php
                        $threads = \App\Models\Thread::where('user_id', $user->id)
                            ->withCount(['comments', 'upvotes', 'hearts'])
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
                                    <div class="px-4 py-3 bg-gray-100 flex justify-between items-center">
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
                                                <span class="upvote-count-{{ $thread->id }}">{{ $thread->upvotes_count }}</span>
                                            </button>
                                            
                                            <button class="react-btn flex items-center space-x-1 text-gray-500 ml-2" 
                                                    data-type="heart" 
                                                    data-thread="{{ $thread->id }}" 
                                                    data-reacted="{{ $userHearted ? 'true' : 'false' }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 heart-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                                </svg>
                                                <span class="heart-count-{{ $thread->id }}">{{ $thread->hearts_count }}</span>
                                            </button>                    
                                        </div>
                                        <button @click="isCommentsOpen = !isCommentsOpen" class="flex items-center space-x-1 text-gray-500 hover:text-blue-500 transition-colors duration-200">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                            </svg>
                                            <span>{{ $thread->comments_count }} comments</span>
                                        </button>
                                    </div>
                                    <div x-show="isCommentsOpen" class="px-4 py-3 bg-gray-100">
                                        @foreach ($thread->comments as $comment)
                                            <div class="flex items-start space-x-3 mb-4">
                                                <img src="{{ $comment->user->profile_photo_url }}" alt="{{ $comment->user->name }}" class="w-8 h-8 rounded-full">
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
                        </div>
                        
                        <div class="mt-4">
                            {{ $threads->links() }}
                        </div>
                    @else
                        <p class="text-gray-600">You haven't created any posts yet.</p>
                    @endif
                </div>
            </div>

            @push('scripts')
            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> {{-- Chart.js for graphics --}}
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
                    
                    // Toggle dropdown menus for thread options
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

                    // --- Career Prediction Script ---
                    const predictionYearSelect = document.getElementById('prediction_year_select');
                    const predictButton = document.getElementById('predict-button');
                    const predictionLoading = document.getElementById('prediction-loading');
                    const predictionResults = document.getElementById('prediction-results');
                    const predictionText = document.getElementById('prediction-text');
                    const insightsText = document.getElementById('insights-text'); // Element for insights
                    const predictionError = document.getElementById('prediction-error');

                    let pieChartInstance = null;
                    let barChartInstance = null;

                    // Check initial user data to enable/disable buttons
                    const userHasExperience = {{ $user->experiences->isNotEmpty() ? 'true' : 'false' }};
                    const userHasEducation = {{ $user->education->isNotEmpty() ? 'true' : 'false' }};

                    function updatePredictButtonState() {
                        const canPredict = userHasExperience && userHasEducation;
                        
                        predictButton.disabled = !canPredict;
                        predictButton.title = canPredict ? "Click to get your career prediction and insights" : "Please add at least one experience and one education entry to enable prediction.";
                    }

                    updatePredictButtonState(); // Set initial state on page load

                    // Handle predict button click
                    predictButton.addEventListener('click', async function() {
                        predictionResults.classList.add('hidden');
                        predictionError.classList.add('hidden');
                        predictionLoading.classList.remove('hidden');
                        predictButton.disabled = true; // Disable button during prediction

                        const selectedYearValue = predictionYearSelect.value;
                        let yearFilter = 'all';
                        let specificYear = null;

                        if (selectedYearValue !== 'all') {
                            yearFilter = 'specific';
                            specificYear = selectedYearValue;
                        }

                        try {
                            const response = await fetch('{{ route('profile.predictCareer') }}', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': csrfToken
                                },
                                body: JSON.stringify({
                                    year_filter: yearFilter,
                                    specific_year: specificYear
                                })
                            });

                            const data = await response.json();

                            if (!response.ok) {
                                displayPredictionError(data.error || 'An unexpected error occurred during prediction.');
                                return;
                            }

                            insightsText.textContent = data.insight; // Display insights
                            predictionText.textContent = data.prediction; // Display career path
                            predictionResults.classList.remove('hidden');

                            // Render charts if data is available
                            renderCharts(data.chart_data);

                        } catch (error) {
                            console.error('Prediction fetch error:', error);
                            displayPredictionError('Failed to get prediction. Please check your internet connection and try again.');
                        } finally {
                            predictionLoading.classList.add('hidden');
                            updatePredictButtonState(); // Re-enable button based on data availability
                        }
                    });

                    function displayPredictionError(message) {
                        predictionError.innerHTML = `<p class="font-bold">Prediction Error:</p><p>${message}</p>`;
                        predictionError.classList.remove('hidden');
                        predictionLoading.classList.add('hidden');
                        updatePredictButtonState();
                    }

                    function renderCharts(chartData) {
                        // Destroy existing charts if they exist to prevent duplicates
                        if (pieChartInstance) {
                            pieChartInstance.destroy();
                        }
                        if (barChartInstance) {
                            barChartInstance.destroy();
                        }

                        const pieCtx = document.getElementById('pieChart').getContext('2d');
                        const barCtx = document.getElementById('barChart').getContext('2d');

                        // Generate a consistent set of colors for charts
                        const chartColors = [
                            '#4299E1', '#667EEA', '#9F7AEA', '#D53F8C', '#ED8936',
                            '#48BB78', '#F6E05E', '#ECC94B', '#F6AD55', '#ED8936',
                            '#A0AEC0', '#CBD5E0', '#E2E8F0', '#EDF2F7', '#F7FAFC'
                        ];

                        // Pie Chart (Industry Distribution)
                        if (chartData && chartData.pie_chart && chartData.pie_chart.length > 0) {
                            pieChartInstance = new Chart(pieCtx, {
                                type: 'pie',
                                data: {
                                    labels: chartData.pie_chart.map(item => item.label),
                                    datasets: [{
                                        data: chartData.pie_chart.map(item => item.value),
                                        backgroundColor: chartColors.slice(0, chartData.pie_chart.length),
                                        hoverOffset: 4
                                    }]
                                },
                                options: {
                                    responsive: true,
                                    maintainAspectRatio: false, // Allow custom height
                                    plugins: {
                                        legend: {
                                            position: 'right', // Position legend to the right for better readability
                                            labels: {
                                                boxWidth: 15, // Smaller color boxes
                                                padding: 10 // Less padding between items
                                            }
                                        },
                                        tooltip: {
                                            callbacks: {
                                                label: function(context) {
                                                    let label = context.label || '';
                                                    if (label) {
                                                        label += ': ';
                                                    }
                                                    if (context.parsed !== null) {
                                                        label += context.parsed + '%'; // Assuming values are percentages or counts
                                                    }
                                                    return label;
                                                }
                                            }
                                        }
                                    }
                                }
                            });
                        } else {
                            pieCtx.clearRect(0, 0, pieCtx.canvas.width, pieCtx.canvas.height);
                            pieCtx.font = '16px Arial';
                            pieCtx.textAlign = 'center';
                            pieCtx.fillStyle = '#6B7280'; // gray-500
                            pieCtx.fillText('No data for industry distribution.', pieCtx.canvas.width / 2, pieCtx.canvas.height / 2);
                        }

                        // Bar Chart (Job Title Trends)
                        if (chartData && chartData.bar_chart && chartData.bar_chart.length > 0) {
                            barChartInstance = new Chart(barCtx, {
                                type: 'bar',
                                data: {
                                    labels: chartData.bar_chart.map(item => item.label),
                                    datasets: [{
                                        label: 'Number of Alumni',
                                        data: chartData.bar_chart.map(item => item.value),
                                        backgroundColor: chartColors[0], // Use a single color for bars
                                        borderColor: chartColors[0],
                                        borderWidth: 1
                                    }]
                                },
                                options: {
                                    responsive: true,
                                    maintainAspectRatio: false, // Allow custom height
                                    plugins: {
                                        legend: {
                                            display: false,
                                        },
                                        tooltip: {
                                            callbacks: {
                                                label: function(context) {
                                                    let label = context.dataset.label || '';
                                                    if (label) {
                                                        label += ': ';
                                                    }
                                                    label += context.parsed.y;
                                                    return label;
                                                }
                                            }
                                        }
                                    },
                                    scales: {
                                        y: {
                                            beginAtZero: true,
                                            title: {
                                                display: true,
                                                text: 'Count of Alumni'
                                            },
                                            ticks: {
                                                precision: 0 // Ensure integer ticks for counts
                                            }
                                        },
                                        x: {
                                            title: {
                                                display: true,
                                                text: 'Job Title'
                                            }
                                        }
                                    }
                                }
                            });
                        } else {
                            barCtx.clearRect(0, 0, barCtx.canvas.width, barCtx.canvas.height);
                            barCtx.font = '16px Arial';
                            barCtx.textAlign = 'center';
                            barCtx.fillStyle = '#6B7280'; // gray-500
                            barCtx.fillText('No data for job title trends.', barCtx.canvas.width / 2, barCtx.canvas.height / 2);
                        }
                    }
                });
            </script>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    document.getElementById('current_role').addEventListener('change', function() {
                        const endDateInput = document.getElementById('end_date');
                        if (this.checked) {
                            endDateInput.disabled = true;
                            endDateInput.classList.add('bg-gray-200', 'cursor-not-allowed');
                            endDateInput.value = '';
                            this.value = '1'; // Set to '1' when checked
                        } else {
                            endDateInput.disabled = false;
                            endDateInput.classList.remove('bg-gray-200', 'cursor-not-allowed');
                            this.value = '0'; // Set to '0' when unchecked
                        }
                    });

                    document.querySelector('input[name="documents[]"]')?.addEventListener('change', function(e) {
                        const fileList = document.getElementById('selected-files');
                        fileList.innerHTML = '';
                        
                        Array.from(this.files).forEach(file => {
                            const fileItem = document.createElement('div');
                            fileItem.className = 'flex items-center text-sm text-gray-600';
                            fileItem.innerHTML = `
                                <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                ${file.name}
                            `;
                            fileList.appendChild(fileItem);
                        });
                    });
                });
            </script>
            @endpush
        </div>
    </div>

    <!-- Include Alpine.js for the EULA modal functionality -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    
    <script>
    @if(!$user->profile?->is_verified)
        @if(!$user->profile?->address)
            // This script block seems to be duplicated or misplaced.
            // It's better to handle this logic directly in the Blade template or a single JS block.
            // Keeping it for now as it was in the original file, but noting it.
            // The button for "Add location to verify" is already handled in the HTML above.
        @else
            // The button for "Verify that you're an alumni" is already handled in the HTML above.
        @endif
    @endif
    </script>
</x-app-layout>