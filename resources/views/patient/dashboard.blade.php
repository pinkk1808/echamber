<x-app-layout>
    {{-- This section creates the header at the top of the page. --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Patient Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 space-y-4">
                    {{-- A simple welcome message. --}}
                    <p>{{ __("Welcome to your dashboard! From here you can manage your appointments and find doctors.") }}</p>

                    {{-- This is the new button.
                         - href="{{ route('patient.doctors.index') }}" tells Laravel to generate the URL for our 'Find a Doctor' page.
                         - The 'class' attributes are for styling, making it a nice blue button. --}}
                    <a href="{{ route('patient.doctors.index') }}" 
                       class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 active:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        Find a Doctor
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>