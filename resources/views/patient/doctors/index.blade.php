<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Find a Doctor') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    @if($doctors->isNotEmpty())
                        <div class="space-y-6">
                            {{-- The @foreach loop iterates through each doctor passed from the controller --}}
                            @foreach($doctors as $doctor)
                                {{-- The entire block is now a clickable link to the doctor's detail page --}}
                                <a href="{{ route('patient.doctors.show', $doctor) }}" class="block p-4 border rounded-lg shadow-sm hover:bg-gray-50 hover:shadow-md transition duration-150 ease-in-out">
                                    <div class="flex items-start space-x-4">
                                        
                                        {{-- Profile Picture Column --}}
                                        <div class="flex-shrink-0">
                                            @if ($doctor->doctorProfile && $doctor->doctorProfile->profile_picture)
                                                <img src="{{ asset('storage/' . $doctor->doctorProfile->profile_picture) }}" alt="Dr. {{ $doctor->name }}" class="rounded-full h-20 w-20 object-cover border">
                                            @else
                                                {{-- A placeholder icon if no image is available --}}
                                                <div class="rounded-full h-20 w-20 bg-gray-200 flex items-center justify-center border">
                                                    <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                                </div>
                                            @endif
                                        </div>

                                        {{-- Details Column --}}
                                        <div class="flex-grow">
                                            <h3 class="text-xl font-bold text-gray-800">{{ $doctor->name }}</h3>
                                            
                                            <p class="text-md text-indigo-600 font-semibold">{{ $doctor->doctorProfile->specialization ?? 'No specialization listed.' }}</p>
                                            
                                            <p class="text-sm text-gray-500">{{ $doctor->doctorProfile->qualifications ?? '' }}</p>
                                            
                                            <p class="mt-2 text-sm text-gray-600">{{ $doctor->doctorProfile->bio ?? '' }}</p>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @else
                        {{-- This message is shown if no doctors are found in the database --}}
                        <p>There are currently no doctors available.</p>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>