<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>eChamber - Your Telemedicine Platform</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

        <!-- Scripts and Styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        {{-- Custom styles to ensure the background behaves correctly --}}
        <style>
            body {
                background-image: url('{{ asset('images/hero-bg.jpg') }}');
                background-size: cover;
                background-position: center;
                background-repeat: no-repeat;
                background-attachment: fixed;
            }
        </style>
    </head>
    <body class="antialiased">
        <div class="relative min-h-screen flex items-center justify-center bg-black bg-opacity-50">
            
            {{-- Main content card --}}
            <div class="w-full max-w-2xl p-8 text-center">
                
                {{-- Logo --}}
                <div class="flex justify-center mb-4">
                    {{-- THIS FIXES THE LOGO SIZE --}}
                    <x-application-logo class="w-16 h-16 fill-current text-white" />
                </div>
                
                {{-- Main Headline --}}
                <h1 class="text-6xl font-extrabold text-white tracking-tight drop-shadow-lg">
                    eChamber
                </h1>

                {{-- Tagline --}}
                <p class="mt-4 text-xl text-gray-200 drop-shadow-md">
                    Quality healthcare, right from the comfort of your home.
                </p>

                {{-- Container for the action buttons --}}
                <div class="mt-10 flex items-center justify-center gap-x-6">
                    
                    {{-- Register Button (Primary Action) - NOW BIGGER --}}
                    <a href="{{ route('register') }}" class="rounded-md bg-indigo-600 px-8 py-4 text-lg font-semibold text-white shadow-lg hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 transition">
                        Get Started
                    </a>

                    {{-- Login Button (Secondary Action) - NOW BIGGER --}}
                    <a href="{{ route('login') }}" class="text-lg font-semibold leading-6 text-white hover:text-gray-300 transition">
                        Log In <span aria-hidden="true">→</span>
                    </a>

                </div>
            </div>

            <footer class="absolute bottom-4 text-center text-sm text-gray-300">
                eChamber &copy; {{ date('Y') }}. All rights reserved.
            </footer>
        </div>
    </body>
</html>