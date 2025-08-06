<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Doctor Profile: {{ $doctor->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-3 gap-6">

            <!-- Left Column: Doctor Info -->
            <div class="md:col-span-1 space-y-6">
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <div class="flex flex-col items-center text-center">
                        @if ($doctor->doctorProfile && $doctor->doctorProfile->profile_picture)
                            <img src="{{ asset('storage/' . $doctor->doctorProfile->profile_picture) }}" alt="Dr. {{ $doctor->name }}" class="rounded-full h-32 w-32 object-cover border-4 border-indigo-200">
                        @else
                            <div class="rounded-full h-32 w-32 bg-gray-200 flex items-center justify-center border-4 border-indigo-200">
                                <svg class="w-20 h-20 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            </div>
                        @endif
                        <h3 class="text-2xl font-bold mt-4">{{ $doctor->name }}</h3>
                        <p class="text-md text-indigo-600 font-semibold">{{ $doctor->doctorProfile->specialization ?? 'No Specialization' }}</p>
                        <p class="text-sm text-gray-500">{{ $doctor->doctorProfile->qualifications ?? '' }}</p>
                        <p class="mt-4 text-sm text-gray-600">{{ $doctor->doctorProfile->bio ?? 'No biography available.' }}</p>
                    </div>
                </div>
            </div>

            <!-- Right Column: Availability & Booking -->
            <div class="md:col-span-2 space-y-6">
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <h3 class="text-lg font-medium text-gray-900">Weekly Availability</h3>
                    <div class="mt-4 space-y-2">
                        @if($availabilities->isNotEmpty())
                            @foreach($availabilities as $item)
                                <div class="p-3 border rounded-md bg-gray-50">
                                    <strong>{{ $item->day_of_week }}:</strong> 
                                    <span class="font-mono text-green-700">{{ \Carbon\Carbon::parse($item->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($item->end_time)->format('h:i A') }}</span>
                                </div>
                            @endforeach
                        @else
                            <p class="text-gray-600">This doctor has not set their schedule yet.</p>
                        @endif
                    </div>
                    
                    {{-- This is the new Appointment Booking Form --}}
                    <div class="mt-8 border-t pt-6">
                        <h3 class="text-lg font-medium text-gray-900">Book an Appointment</h3>
                        
                        {{-- Display Success or Error Messages after form submission --}}
                        @if(session('success'))
                            <div class="mt-2 text-sm text-green-600 bg-green-100 p-3 rounded-md">{{ session('success') }}</div>
                        @endif
                        @if(session('error'))
                            <div class="mt-2 text-sm text-red-600 bg-red-100 p-3 rounded-md">{{ session('error') }}</div>
                        @endif

                        <form method="POST" action="{{ route('appointments.store') }}" class="mt-4 space-y-4">
                            @csrf
                            
                            {{-- This hidden field sends the doctor's ID with the form --}}
                            <input type="hidden" name="doctor_id" value="{{ $doctor->id }}">

                            <!-- Appointment Date -->
                            <div>
                                <x-input-label for="appointment_date" :value="__('Date')" />
                                <x-text-input id="appointment_date" name="appointment_date" type="date" class="mt-1 block w-full" :min="now()->toDateString()" required />
                                <x-input-error :messages="$errors->get('appointment_date')" class="mt-2" />
                            </div>

                            <!-- Appointment Time -->
                            <div>
                                <x-input-label for="appointment_time" :value="__('Time')" />
                                <x-text-input id="appointment_time" name="appointment_time" type="time" class="mt-1 block w-full" required />
                                <x-input-error :messages="$errors->get('appointment_time')" class="mt-2" />
                            </div>

                            <!-- Optional Notes -->
                            <div>
                                <x-input-label for="notes" :value="__('Reason for visit (optional)')" />
                                <textarea id="notes" name="notes" rows="3" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('notes') }}</textarea>
                            </div>

                            <div>
                                <x-primary-button>
                                    {{ __('Request Appointment') }}
                                </x-primary-button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>

        </div>
    </div>
</x-app-layout>