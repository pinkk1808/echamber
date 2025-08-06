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
    </head>
    {{-- We use a light gray background for a calming, professional feel --}}
    <body class="antialiased bg-gray-100">
        <div class="relative min-h-screen flex flex-col items-center justify-center">

            {{-- This is the main white card in the center --}}
            <div class="w-full max-w-xl p-8 bg-white rounded-lg shadow-lg text-center">
                
                {{-- Your Application Logo --}}
                <div>
                    <a href="/">
                        <x-application-logo class="w-20 h-20 fill-current text-gray-500 inline-block" />
                    </a>
                </div>
                
                {{-- Main Headline --}}
                <h1 class="mt-6 text-4xl font-bold text-gray-800">
                    Welcome to eChamber
                </h1>

                {{-- Tagline --}}
                <p class="mt-4 text-lg text-gray-600">
                    Quality healthcare, right from the comfort of your home.
                </p>

                {{-- This container holds the Login and Register buttons --}}
                <div class="mt-8 flex items-center justify-center gap-x-6">
                    
                    {{-- Register Button (Primary) --}}
                    <a href="{{ route('register') }}" class="rounded-md bg-indigo-600 px-5 py-3 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                        Get started
                    </a>

                    {{-- Login Button (Secondary) --}}
                    <a href="{{ route('login') }}" class="text-sm font-semibold leading-6 text-gray-900">
                        Log in <span aria-hidden="true">→</span>
                    </a>

                </div>
            </div>

            <footer class="mt-8 text-center text-sm text-gray-500">
                eChamber © {{ date('Y') }}. All rights reserved.
            </footer>
        </div>
    </body>
</html>
```6.  Save the `landing.blade.php` file.