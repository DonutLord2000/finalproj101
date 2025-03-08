<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('welcome') }}">
                        <img src="{{ asset('images/grc.png') }}" alt="Custom Logo" style="width: 100px; height: 50px;">
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>
                </div>

                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link href="{{ route('scholarships.index') }}" :active="request()->routeIs('scholarships.index')">
                        {{ __('Scholarship') }}
                    </x-nav-link>
                </div>

                <!-- Admin Navigation Links - Only show if authenticated and admin -->
                @auth
                    @if (auth()->user()->role == 'admin')
                        <div class="relative hidden sm:flex sm:items-center sm:ms-10 group">
                            <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                {{ __('Admin Panel') }}
                                <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            
                            <div class="absolute left-0 hidden mt-40 w-48 bg-white border border-gray-200 rounded-md shadow-lg group-hover:block">
                                <x-dropdown-link href="{{ route('admin.users.index') }}" :active="request()->routeIs('admin.users.index')">
                                    {{ __('User Management') }}
                                </x-dropdown-link>
                                <x-dropdown-link href="{{ route('news.index') }}" :active="request()->routeIs('news.index')">
                                    {{ __('News Management') }}
                                </x-dropdown-link>
                                <x-dropdown-link href="{{ route('admin.pending-responses') }}" :active="request()->routeIs('admin.pending-responses')">
                                    {{ __('Tracer Responses') }}
                                </x-dropdown-link>
                                <x-dropdown-link href="{{ route('alumni.index') }}" :active="request()->routeIs('alumni.index')">
                                    {{ __('Alumni Index') }}
                                </x-dropdown-link>
                                <x-dropdown-link href="{{ route('verification.index') }}" :active="request()->routeIs('verification.index')">
                                    {{ __('Verification Request Management') }}
                                </x-dropdown-link>
                                <x-dropdown-link href="{{ route('admin.scholarships.index') }}" :active="request()->routeIs('admin.scholarships.index')">
                                    {{ __('Scholarship Request') }}
                                </x-dropdown-link>
                                <x-dropdown-link href="{{ route('activity-logs.index') }}" :active="request()->routeIs('activity-logs.index')">
                                    {{ __('Activity Logs') }}
                                </x-dropdown-link>
                            </div>
                        </div>
                    @endif
                @endauth

                <!-- Public Navigation Links - Available to all users including guests -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link href="{{ route('threads.index') }}" :active="request()->routeIs('threads.index')">
                        {{ __('Discussion') }}
                    </x-nav-link>
                </div>

                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link href="{{ route('alumni.all-profiles.index') }}" :active="request()->routeIs('alumni.all-profiles.index')">
                        {{ __('Alumni Profiles') }}
                    </x-nav-link>
                </div>

                <!-- Authenticated-only Navigation Links -->
                @auth
                    <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                        <x-nav-link href="{{ route('tracer-study.form') }}" :active="request()->routeIs('tracer-study.form')">
                            {{ __('Tracer Study') }}
                        </x-nav-link>
                    </div>

                    <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                        <x-nav-link href="{{ route('profile.edit') }}" :active="request()->routeIs('profile.edit')">
                            {{ __('My Profile') }}
                        </x-nav-link>
                    </div>
                @endauth
            </div>

            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <!-- Settings Dropdown - Only for authenticated users -->
                @auth
                    <div class="ms-3 relative">
                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                                    <button class="flex text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-gray-300 transition">
                                        <img class="h-8 w-8 rounded-full object-cover" src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" />
                                    </button>
                                @else
                                    <span class="inline-flex rounded-md">
                                        <button type="button" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none focus:bg-gray-50 active:bg-gray-50 transition ease-in-out duration-150">
                                            {{ Auth::user()->name }}

                                            <svg class="ms-2 -me-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                            </svg>
                                        </button>
                                    </span>
                                @endif
                            </x-slot>

                            <x-slot name="content">
                                <!-- Account Management -->
                                <div class="block px-4 py-2 text-xs text-gray-400">
                                    {{ __('Manage Account') }}
                                </div>

                                <x-dropdown-link href="{{ route('profile.show') }}">
                                    {{ __('Profile') }}
                                </x-dropdown-link>

                                <x-dropdown-link href="{{ route('about-us') }}">
                                    {{ __('About Us') }}
                                </x-dropdown-link>

                                <x-dropdown-link href="{{ route('contact-directory') }}">
                                    {{ __('Contact Us') }}
                                </x-dropdown-link>

                                @if (Laravel\Jetstream\Jetstream::hasApiFeatures())
                                    <x-dropdown-link href="{{ route('api-tokens.index') }}">
                                        {{ __('API Tokens') }}
                                    </x-dropdown-link>
                                @endif

                                <div class="border-t border-gray-200"></div>

                                <!-- Authentication -->
                                <form method="POST" action="{{ route('logout') }}" x-data>
                                    @csrf

                                    <x-dropdown-link href="{{ route('logout') }}"
                                            @click.prevent="$root.submit();">
                                        {{ __('Log Out') }}
                                    </x-dropdown-link>
                                </form>
                            </x-slot>
                        </x-dropdown>
                    </div>
                @else
                    <!-- Login/Register Links for Guests -->
                    <div class="ms-3 relative flex space-x-4">
                        <a href="{{ route('login') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                            {{ __('Login') }}
                        </a>
                        <a href="{{ route('register') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                            {{ __('Register') }}
                        </a>
                    </div>
                @endauth
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>

            <!-- Public Navigation Links - Available to all users including guests -->
            <x-responsive-nav-link href="{{ route('threads.index') }}" :active="request()->routeIs('threads.index')">
                {{ __('Discussion') }}
            </x-responsive-nav-link>

            <x-responsive-nav-link href="{{ route('alumni.all-profiles.index') }}" :active="request()->routeIs('alumni.all-profiles.index')">
                {{ __('All Profiles') }}
            </x-responsive-nav-link>

            <!-- Authenticated-only Navigation Links -->
            @auth
                <x-responsive-nav-link href="{{ route('tracer-study.form') }}" :active="request()->routeIs('tracer-study.form')">
                    {{ __('Tracer Study') }}
                </x-responsive-nav-link>

                <x-responsive-nav-link href="{{ route('profile.edit') }}" :active="request()->routeIs('profile.edit')">
                    {{ __('Alumni Profile') }}
                </x-responsive-nav-link>

                <!-- Admin Links -->
                @if (auth()->user()->role == 'admin')
                    <div class="border-t border-gray-200 pt-2">
                        <div class="text-gray-600 text-sm font-semibold px-4">
                            {{ __('Admin Panel') }}
                        </div>
                        <x-responsive-nav-link href="{{ route('admin.users.index') }}" :active="request()->routeIs('admin.users.index')">
                            {{ __('User Management') }}
                        </x-responsive-nav-link>
                        <x-responsive-nav-link href="{{ route('news.index') }}" :active="request()->routeIs('news.index')">
                            {{ __('News Management') }}
                        </x-responsive-nav-link>
                        <x-responsive-nav-link href="{{ route('admin.pending-responses') }}" :active="request()->routeIs('admin.pending-responses')">
                            {{ __('Tracer Responses') }}
                        </x-responsive-nav-link>
                        <x-responsive-nav-link href="{{ route('alumni.index') }}" :active="request()->routeIs('alumni.index')">
                            {{ __('Alumni Index') }}
                        </x-responsive-nav-link>
                    </div>
                @endif

                <!-- Student Links -->
                @if (auth()->user()->role == 'student')
                    <x-responsive-nav-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')">
                        {{ __('Lessons') }}
                    </x-responsive-nav-link>
                @endif
            @endauth
        </div>

        <!-- Responsive Settings Options -->
        @auth
            <div class="pt-4 pb-1 border-t border-gray-200">
                <div class="flex items-center px-4">
                    @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                        <div class="shrink-0 me-3">
                            <img class="h-10 w-10 rounded-full object-cover" src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" />
                        </div>
                    @endif

                    <div>
                        <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                        <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                    </div>
                </div>

                <div class="mt-3 space-y-1">
                    <!-- Account Management -->
                    <x-responsive-nav-link href="{{ route('profile.show') }}" :active="request()->routeIs('profile.show')">
                        {{ __('Profile') }}
                    </x-responsive-nav-link>

                    @if (Laravel\Jetstream\Jetstream::hasApiFeatures())
                        <x-responsive-nav-link href="{{ route('api-tokens.index') }}" :active="request()->routeIs('api-tokens.index')">
                            {{ __('API Tokens') }}
                        </x-responsive-nav-link>
                    @endif

                    <!-- Authentication -->
                    <form method="POST" action="{{ route('logout') }}" x-data>
                        @csrf
                        <x-responsive-nav-link href="{{ route('logout') }}" @click.prevent="$root.submit();">
                            {{ __('Log Out') }}
                        </x-responsive-nav-link>
                    </form>
                </div>
            </div>
        @else
            <!-- Login/Register Links for Guests (Mobile) -->
            <div class="pt-4 pb-1 border-t border-gray-200">
                <div class="mt-3 space-y-1 px-4">
                    <x-responsive-nav-link href="{{ route('login') }}">
                        {{ __('Login') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link href="{{ route('register') }}">
                        {{ __('Register') }}
                    </x-responsive-nav-link>
                </div>
            </div>
        @endauth
    </div>
</nav>

