<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title', config('app.name', 'Laravel'))</title>

        <!-- Fonts -->
        <link rel="icon" type="image/x-icon" href="{{ asset('images/favicon.ico') }}">
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <!-- Lumnix Chatbot Styles -->
        <link href="{{ asset('css/lumnix-chatbot.css') }}" rel="stylesheet">

        <!-- Styles -->
        @livewireStyles
        <style>
            #dropdown-portal {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 0;
                overflow: visible;
                pointer-events: none;
            }
            
            #dropdown-portal .dropdown-content {
                position: absolute;
                background: white;
                border-radius: 0.375rem;
                box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
                border: 1px solid rgba(0, 0, 0, 0.05);
                pointer-events: auto;
            }
        </style>
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
                            <li><a href="tracer-study" class="text-blue-600 hover:text-blue-800">Alumni Tracer</a></li>
                            <li><a href="scholarships" class="text-blue-600 hover:text-blue-800">Scholarship</a></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="text-lg font-semibold mb-4">LOCATION MAP</h4>
                        <div class="aspect-w-16 aspect-h-9">
                            <iframe 
                                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3860.107538254645!2d120.98392120000001!3d14.649836499999997!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3397b5d4fab883bb%3A0x96f1adb22bed4d5e!2sGlobal%20Reciprocal%20Colleges%20-%20GRC!5e0!3m2!1sen!2sph!4v1741855202624!5m2!1sen!2sph"
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
        <!-- Lumnix Chatbot Script -->
        <script src="{{ asset('js/lumnix-chatbot.js') }}"></script>

        @if(session('clear_chat_history'))
        <script>
            // Clear Lumnix chat history from localStorage
            localStorage.removeItem('lumnix_chat_history');
            localStorage.removeItem('lumnix_session_id');
        </script>
        @endif
        <div id="dropdown-portal" class="relative z-[9999]"></div>
    </body>
</html>
