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
                        
                        {{-- NEW: Display Average Rating --}}
                        @if($doctor->doctorReviews->isNotEmpty())
                            <div class="mt-2 flex items-center">
                                <div class="flex text-yellow-400">
                                    @for ($i = 1; $i <= 5; $i++)
                                        @if ($i <= round($doctor->average_rating))
                                            <svg class="w-5 h-5 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/></svg>
                                        @else
                                            <svg class="w-5 h-5 fill-current text-gray-300" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/></svg>
                                        @endif
                                    @endfor
                                </div>
                                <span class="ml-2 text-sm text-gray-600">({{ number_format($doctor->average_rating, 1) }}/5.0 based on {{ $doctor->doctorReviews->count() }} reviews)</span>
                            </div>
                        @endif

                        <p class="mt-4 text-sm text-gray-600">{{ $doctor->doctorProfile->bio ?? 'No biography available.' }}</p>
                    </div>
                </div>
            </div>

            <!-- Right Column: Availability, Booking, and Reviews -->
            <div class="md:col-span-2 space-y-6">
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <h3 class="text-lg font-medium text-gray-900">Weekly Availability</h3>
                    <div class="mt-4 space-y-2">
                        @forelse($availabilities as $item)
                            <div class="p-3 border rounded-md bg-gray-50">
                                <strong>{{ $item->day_of_week }}:</strong> 
                                <span class="font-mono text-green-700">{{ \Carbon\Carbon::parse($item->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($item->end_time)->format('h:i A') }}</span>
                            </div>
                        @empty
                            <p class="text-gray-600">This doctor has not set their schedule yet.</p>
                        @endforelse
                    </div>
                    
                    <div class="mt-8 border-t pt-6">
                        <h3 class="text-lg font-medium text-gray-900">Book an Appointment</h3>
                        
                        @if(session('success'))
                            <div class="mt-2 text-sm text-green-600 bg-green-100 p-3 rounded-md">{{ session('success') }}</div>
                        @endif
                        @if(session('error'))
                            <div class="mt-2 text-sm text-red-600 bg-red-100 p-3 rounded-md">{{ session('error') }}</div>
                        @endif

                        <form method="POST" action="{{ route('appointments.store') }}" class="mt-4 space-y-4">
                            @csrf
                            <input type="hidden" name="doctor_id" value="{{ $doctor->id }}">
                            <div>
                                <x-input-label for="appointment_date" :value="__('Date')" />
                                <x-text-input id="appointment_date" name="appointment_date" type="date" class="mt-1 block w-full" :min="now()->toDateString()" required />
                            </div>
                            <div>
                                <x-input-label for="appointment_time" :value="__('Time')" />
                                <x-text-input id="appointment_time" name="appointment_time" type="time" class="mt-1 block w-full" required />
                            </div>
                            <div>
                                <x-input-label for="notes" :value="__('Reason for visit (optional)')" />
                                <textarea id="notes" name="notes" rows="3" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('notes') }}</textarea>
                            </div>
                            <div>
                                <x-primary-button>{{ __('Request Appointment') }}</x-primary-button>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- NEW: Patient Feedback Section --}}
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <h3 class="text-lg font-medium text-gray-900">Patient Feedback</h3>
                    <div class="mt-4 space-y-4">
                        @forelse($doctor->doctorReviews as $review)
                            <div class="border-t pt-4">
                                <div class="flex justify-between items-center">
                                    <p class="font-semibold">{{ $review->patient->name }}</p>
                                    <div class="flex text-yellow-400">
                                        @for ($i = 0; $i < $review->rating; $i++)
                                            <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/></svg>
                                        @endfor
                                        @for ($i = $review->rating; $i < 5; $i++)
                                            <svg class="w-4 h-4 fill-current text-gray-300" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/></svg>
                                        @endfor
                                    </div>
                                </div>
                                <p class="text-sm text-gray-600 mt-1 italic">"{{ $review->comment ?? 'No comment left.' }}"</p>
                            </div>
                        @empty
                            <p class="text-gray-600">This doctor has not received any reviews yet.</p>
                        @endforelse
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>