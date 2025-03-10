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
                                        <img src="{{ asset('storage/profile-photos/default.png') }}"
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
                        <div class="flex items-start justify-between">
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
                                    @else
                                        <button type="button" onclick="document.getElementById('verification-form').classList.toggle('hidden')" class="text-blue-600 text-sm hover:underline">
                                            Verify that youre an alumni
                                        </button>
                                    @endif
                                </div>
                                <p class="text-gray-600 mt-1">{{ $user->profile?->address ?: 'Add your location' }}</p>
                                <p class="text-blue-600 hover:underline cursor-pointer mt-1">Contact info</p>
                            </div>
                            <button type="button" onclick="document.getElementById('edit-form').classList.toggle('hidden')" class="p-2 text-gray-400 hover:text-gray-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                </svg>
                            </button>
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
                                        <button type="button" onclick="document.getElementById('verification-form').classList.toggle('hidden')" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                            Request Verification
                                        </button>
                    
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
                                            {{ $experience->current_role ? 'Present' : $experience->end_date->format('M Y') }}
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
        </div>
    </div>

    <!-- Include Alpine.js for the EULA modal functionality -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    
    <script>
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
    </script>
</x-app-layout>

