<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Global Reciprocal Colleges</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        .wave-shape {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 150px;
            overflow: hidden;
        }

        .wave-shape svg {
            position: absolute;
            bottom: 0;
            width: 100%;
            height: 50px;
            transform: scale(10);
        }

        .hero-gradient {
            background: linear-gradient(180deg, #790202 0%, #cf0303 100%);
            position: relative;
            min-height: 500px;
        }

        .nav-gradient {
            background: linear-gradient(90deg, #AB0A0A 0%, #8B0000 100%);
        }

        @keyframes gradient {
            0% {
                background-position: 0% 50%;
            }
            50% {
                background-position: 100% 50%;
            }
            100% {
                background-position: 0% 50%;
            }
        }

        /* Mobile menu styles */
        .mobile-menu {
            display: none;
        }

        @media (max-width: 768px) {
            .desktop-menu {
                display: none;
            }
            .mobile-menu {
                display: block;
            }
            .mobile-menu-items {
                display: none;
                position: absolute;
                top: 100%;
                left: 0;
                right: 0;
                background: linear-gradient(90deg, #AB0A0A 0%, #8B0000 100%);
                padding: 1rem;
                z-index: 50;
            }
            .mobile-menu-items.active {
                display: block;
            }
        }
    </style>
</head>
<body class="antialiased">
    <!-- Navigation -->
    <nav class="shadow-lg nav-gradient">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex-shrink-0 flex items-center">
                    <img src="{{ asset('images/logo-white.png') }}" alt="GRC Logo" class="h-10">
                </div>
                
                <!-- Desktop Navigation Menu -->
                <div class="hidden md:flex items-center space-x-4">
                    <a href="dashboard" class="text-white hover:text-gray-200 px-3 py-2 text-sm font-medium">Dashboard</a>
                    <a href="tracer-study" class="text-white hover:text-gray-200 px-3 py-2 text-sm font-medium">Alumni Tracer</a>
                    <a href="scholarships" class="text-white hover:text-gray-200 px-3 py-2 text-sm font-medium">Scholarship</a>
                    <a href="about-us" class="text-white hover:text-gray-200 px-3 py-2 text-sm font-medium">About Us</a>
                    <a href="contact-directory" class="text-white hover:text-gray-200 px-3 py-2 text-sm font-medium">Contact Us</a>
                </div>
                
                @if (Route::has('login'))
                    <div class="flex items-center space-x-4">
                        @auth
                            <a href="{{ url('/dashboard') }}" class="text-white hover:text-black font-semibold">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="bg-gray-800 text-white px-4 py-1.5 rounded-md hover:bg-red-700 transition duration-300 text-sm">Login</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="bg-gray-800 text-white px-4 py-1.5 rounded-md hover:bg-gray-700 transition duration-300 text-sm">Register</a>
                            @endif
                        @endauth
                    </div>
                @endif
    </nav>

    <!-- Hero Section -->
    <div class="relative overflow-hidden hero-gradient">
        <div class="max-w-7xl mx-auto">
            <div class="relative z-10 pb-8 sm:pb-16 md:pb-20 lg:pb-28 xl:pb-32">
                <main class="mt-20 mx-auto max-w-7xl px-4 sm:mt-24 sm:px-6 md:mt-32 lg:mt-40 lg:px-8">
                    <div class="text-center">
                        <h1 class="text-4xl tracking-tight font-extrabold text-white sm:text-5xl md:text-6xl">
                            GLOBAL RECIPROCAL COLLEGES
                        </h1>
                        <p class="mt-3 text-base text-white sm:mt-5 sm:text-lg sm:max-w-xl sm:mx-auto md:mt-5 md:text-xl">
                            <span id="typewriter"></span>
                        </p>
                    </div>
                </main>
            </div>
        </div>
        <div class="wave-shape">
            <svg viewBox="0 0 1440 320" xmlns="http://www.w3.org/2000/svg">
                <path fill="#ffffff" fill-opacity="1" d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,122.7C672,117,768,139,864,154.7C960,171,1056,181,1152,165.3C1248,149,1344,107,1392,85.3L1440,64L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path>
            </svg>
        </div>
    </div>

    <!-- Typewriting and Deleting Effect -->
    <style>
    #typewriter {
        font-family: inherit;
        white-space: nowrap;
        border-right: 2px solid white;
        display: inline-block;
        overflow: hidden;
    }

    @keyframes blink {
        from, to {
        border-color: transparent;
        }
        50% {
        border-color: white;
        }
    }

    #typewriter {
        animation: blink 0.6s step-end infinite;
    }
    </style>

    <script>
    const phrases = ["TOUCHING HEARTS, RENEWING MINDS, TRANSFORMING LIVES."];
    const typewriter = document.getElementById("typewriter");
    let currentPhraseIndex = 0;
    let currentCharIndex = 0;
    let isDeleting = false;

    function typeEffect() {
        const currentPhrase = phrases[currentPhraseIndex];
        
        if (isDeleting) {
        typewriter.textContent = currentPhrase.substring(0, currentCharIndex--);
        } else {
        typewriter.textContent = currentPhrase.substring(0, currentCharIndex++);
        }

        if (!isDeleting && currentCharIndex === currentPhrase.length) {
        // Pause before deleting
        setTimeout(() => isDeleting = true, 1000);
        } else if (isDeleting && currentCharIndex === 0) {
        // Move to the next phrase and reset
        isDeleting = false;
        currentPhraseIndex = (currentPhraseIndex + 1) % phrases.length;
        }

        const typingSpeed = isDeleting ? 50 : 100;
        setTimeout(typeEffect, typingSpeed);
    }

    typeEffect();

    // Mobile menu toggle
    document.addEventListener('DOMContentLoaded', function() {
        const mobileMenuButton = document.getElementById('mobile-menu-button');
        const mobileMenuItems = document.getElementById('mobile-menu-items');
        
        if (mobileMenuButton && mobileMenuItems) {
            mobileMenuButton.addEventListener('click', function() {
                mobileMenuItems.classList.toggle('active');
            });
        }
    });
    </script>


    <!-- Mission & Vision -->
    <div class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-2 gap-8">
                <div class="rounded-lg p-8 text-center" style="background-color: #bd0303">
                    <h2 class="text-3xl font-bold text-white mb-4">MISSION</h2>
                    <p class="text-white">GRC is creating a culture for successful, socially responsible, morally upright skilled workers and highly competent professionals through values-based quality education.</p>
                </div>
                <div class="rounded-lg p-8 text-center" style="background-color: #bd0303">
                    <h2 class="text-3xl font-bold text-white mb-4">VISION</h2>
                    <p class="text-white">A global community of excellent individuals with values.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Services -->
    <div class="py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <!-- Admissions -->
                <div class="text-center bg-white shadow-md rounded-lg p-6 group hover:shadow-lg hover:bg-gray-100 transition duration-300" >
                    <div class="bg-red-800 rounded-full p-4 mx-auto w-20 h-20 flex items-center justify-center mb-4 transform transition duration-300 group-hover:-translate-y-2" style="background-color: #bd0303">
                        <i class="fas fa-list text-white text-2xl"></i>
                    </div>
                    <h3 class="font-semibold text-lg">ADMISSIONS</h3>
                    <p class="text-gray-600 mt-2">Enroll today and kickstart your academic journey with us.</p>
                </div>
                <!-- Apply Scholarship -->
                <div class="text-center bg-white shadow-md rounded-lg p-6 group hover:shadow-lg hover:bg-gray-100 transition duration-300">
                    <div class="bg-red-800 rounded-full p-4 mx-auto w-20 h-20 flex items-center justify-center mb-4 transform transition duration-300 group-hover:-translate-y-2" style="background-color: #bd0303">
                        <i class="fas fa-medal text-white text-2xl"></i>
                    </div>
                    <h3 class="font-semibold text-lg">APPLY SCHOLARSHIP</h3>
                    <p class="text-gray-600 mt-2">Explore and apply for scholarships to support your education.</p>
                </div>
                <!-- GRC Library -->
                <div class="text-center bg-white shadow-md rounded-lg p-6 group hover:shadow-lg hover:bg-gray-100 transition duration-300">
                    <div class="bg-red-800 rounded-full p-4 mx-auto w-20 h-20 flex items-center justify-center mb-4 transform transition duration-300 group-hover:-translate-y-2" style="background-color: #bd0303">
                        <i class="fas fa-book text-white text-2xl"></i>
                    </div>
                    <h3 class="font-semibold text-lg">GRC LIBRARY</h3>
                    <p class="text-gray-600 mt-2">Access a vast collection of resources and materials in our library.</p>
                </div>
                <!-- Alumni -->
                <div class="text-center bg-white shadow-md rounded-lg p-6 group hover:shadow-lg hover:bg-gray-100 transition duration-300">
                    <div class="bg-red-800 rounded-full p-4 mx-auto w-20 h-20 flex items-center justify-center mb-4 transform transition duration-300 group-hover:-translate-y-2" style="background-color: #bd0303">
                        <i class="fas fa-user-graduate text-white text-2xl"></i>
                    </div>
                    <h3 class="font-semibold text-lg">ALUMNI</h3>
                    <p class="text-gray-600 mt-2">Stay connected with our alumni network for lifelong opportunities.</p>
                </div>
            </div>
        </div>
    </div>


    <!-- Footer -->
    <footer class="bg-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid md:grid-cols-3 gap-8">
                <div>
                    <h4 class="text-lg font-semibold mb-4">OFFICIAL LOGO</h4>
                    <img src="{{ asset('images/grc.png') }}" alt="GRC Logo" class="h-32">
                </div>
                <div>
                    <h4 class="text-lg font-semibold mb-4">QUICK LINKS</h4>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-blue-600 hover:text-blue-800">About Us</a></li>
                        <li><a href="#" class="text-blue-600 hover:text-blue-800">Contact Us</a></li>
                        <li><a href="#" class="text-blue-600 hover:text-blue-800">Privacy Policy</a></li>
                        <li><a href="#" class="text-blue-600 hover:text-blue-800">Alumni Tracer</a></li>
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