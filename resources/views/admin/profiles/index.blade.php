<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manage Profiles') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($users as $user)
                            <div class="bg-white rounded-lg shadow p-6">
                                <div class="flex items-center space-x-4">
                                    <img src="{{ $user->profile?->profile_picture ? Storage::url($user->profile->profile_picture) : $user->profile_photo_url }}"
                                         class="w-16 h-16 rounded-full"
                                         alt="{{ $user->name }}">
                                    <div>
                                        <h3 class="text-lg font-medium">
                                            {{ $user->name }}
                                            @if($user->profile?->is_verified)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    Verified
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                    Unverified
                                                </span>
                                            @endif
                                        </h3>
                                        <p class="text-sm text-gray-500">{{ $user->email }}</p>
                                    </div>
                                </div>
                                <div class="mt-4">
                                    <a href="{{ route('admin.profiles.show', $user) }}" class="text-indigo-600 hover:text-indigo-900">View Profile</a>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-6">
                        {{ $users->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>