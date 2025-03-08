<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title', config('app.name', 'Laravel'))</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        

        <!-- Styles -->
        @livewireStyles

    </head>
    <body class="font-sans antialiased">
        <x-banner />

        <div class="min-h-screen bg-gray-100">
            @livewire('navigation-menu')

            <!-- Page Heading -->

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>

        @stack('modals')
        @stack('scripts')

        @livewireScripts
        
        <x-floating-chatbot />
        <!-- Footer -->
        <footer class="bg-white-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                <div class="grid md:grid-cols-3 gap-8">
                    <div>
                        <h4 class="text-lg font-semibold mb-4">OFFICIAL LOGO</h4>
                        <img src="{{ asset('images/grc.png') }}" alt="GRC Logo" class="h-32">
                    </div>
                    <div>
                        <h4 class="text-lg font-semibold mb-4">QUICK LINKS</h4>
                        <ul class="space-y-2">
                            <li><a href="about-us" class="text-blue-600 hover:text-blue-800">About Us</a></li>
                            <li><a href="contact-directory" class="text-blue-600 hover:text-blue-800">Contact Us</a></li>
                            <li><a href="#" class="text-blue-600 hover:text-blue-800">Privacy Policy</a></li>
                            <li><a href="#" class="text-blue-600 hover:text-blue-800">Library Website</a></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="text-lg font-semibold mb-4">LOCATION MAP</h4>
                        <div class="aspect-w-16 aspect-h-9">
                            <iframe 
                                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1930.9!2d121.0!3d14.7!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMTTCsDQyJzAwLjAiTiAxMjHCsDAwJzAwLjAiRQ!5e0!3m2!1sen!2sph!4v1234567890!5m2!1sen!2sph"
                                width="100%" 
                                height="200" 
                                style="border:0;" 
                                allowfullscreen="" 
                                loading="lazy">
                            </iframe>
                        </div>
                    </div>
                </div>
                <div class="mt-8 pt-8 border-t border-gray-200">
                    <p class="text-center text-gray-500 text-sm">
                        © {{ date('Y') }} Global Reciprocal Colleges. All rights reserved.
                    </p>
                </div>
            </div>
        </footer>
    </body>
</html>
