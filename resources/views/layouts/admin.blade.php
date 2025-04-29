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
    </head>
    <body class="font-sans antialiased">
        <x-banner />
        <div class="min-h-screen bg-gray-100">
            @livewire('navigation-menu')

            <!-- Page Content -->
            <main>
                
                @yield('content')
            
            </main>
        </div>

        @stack('modals')

        @livewireScripts
        <!-- Lumnix Chatbot Script -->
        <script src="{{ asset('js/lumnix-chatbot.js') }}"></script>

        @if(session('clear_chat_history'))
        <script>
            // Clear Lumnix chat history from localStorage
            localStorage.removeItem('lumnix_chat_history');
            localStorage.removeItem('lumnix_session_id');
        </script>
        @endif
    </body>
</html>
