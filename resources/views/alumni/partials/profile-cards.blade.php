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

