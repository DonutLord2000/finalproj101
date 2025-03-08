<x-app-layout>
    @section('title', 'GRC - Alumni Profiles')
    <div class="py-12" x-data="{ search: '', showVerified: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white shadow-xl rounded-lg overflow-hidden">
                <div class="p-6 sm:px-20 bg-white border-b border-gray-200">
                    <div class="mt-8 text-3xl font-bold">
                        Alumni Profiles
                    </div>

                    <div class="mt-4 text-gray-500">
                        Browse through our alumni profiles.
                    </div>
                </div>

                <div class="bg-gray-200 p-8">
                    <div class="flex flex-wrap items-center gap-4 mb-8">
                        <label class="flex items-center">
                            <div class="relative inline-block w-16 mr-2 align-middle select-none transition duration-200 ease-in">
                                <input type="checkbox" x-model="showVerified" @change="fetchProfiles" 
                                       class="toggle-checkbox absolute block w-8 h-8 rounded-full bg-white border-4 appearance-none cursor-pointer"/>
                                <label for="toggle" class="toggle-label block overflow-hidden h-8 rounded-full bg-gray-300 cursor-pointer"></label>
                            </div>
                            <span class="text-gray-700">Show only verified</span>
                        </label>
                        <div class="flex-grow flex items-center">
                            <input x-model="search" type="text" name="search" placeholder="Search profiles..."
                                   class="flex-grow block w-full rounded-l-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                   @input="fetchProfiles">
                            <button type="button"
                                    class="px-6 py-2 bg-indigo-600 text-white rounded-r-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                    @click="fetchProfiles">
                                Search
                            </button>
                        </div>                        
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-4 gap-9" id="profiles-container">
                        @foreach($profiles as $profile)
                            <a href="{{ route('alumni.profile.show', $profile->id) }}"
                                class="block bg-gradient-to-b from-gray-300 to-gray-100 rounded-lg shadow-lg hover:shadow-xl transition-shadow duration-300 p-6">
                                <img src="{{ $profile->profile && $profile->profile->profile_picture ? Storage::url($profile->profile->profile_picture) : asset('storage/profile-photos/default.png') }}"
                                     alt="{{ $profile->name }}"
                                     class="w-32 h-32 mx-auto rounded-full mb-4 border-4 border-gray-300 object-cover">
                                <h3 class="font-semibold text-lg text-center">{{ $profile->name }}</h3>
                                <p class="text-gray-600 text-sm text-center">{{ $profile->profile && $profile->profile->address ? $profile->profile->address : 'Location not specified' }}</p>
                                <div class="mt-2 text-center">
                                    @if($profile->profile && $profile->profile->is_verified)
                                        <span class="inline-block bg-green-100 text-green-800 text-xs px-3 py-1 rounded-full uppercase font-semibold tracking-wide">Verified</span>
                                    @else
                                        <span class="inline-block bg-red-100 text-red-800 text-xs px-3 py-1 rounded-full uppercase font-semibold tracking-wide">Unverified</span>
                                    @endif
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>

                <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                    {{ $profiles->links() }}
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function fetchProfiles() {
            const search = this.search;
            const showVerified = this.showVerified;

            fetch(`{{ route('alumni.all-profiles.index') }}?search=${search}&show_verified=${showVerified}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.text())
            .then(html => {
                document.getElementById('profiles-container').innerHTML = html;
            });
        }
    </script>
    @endpush

    <style>
        .toggle-checkbox:checked {
        @apply: right-0 border-green-400;
        right: 0;
        border-color: #68D391;
        }
        .toggle-checkbox:checked + .toggle-label {
        @apply: bg-green-400;
        background-color: #68D391;
        }
    </style>
</x-app-layout>

