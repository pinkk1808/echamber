<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manage My Availability') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Section to Add New Availability -->
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <h3 class="text-lg font-medium text-gray-900">Add New Time Slot</h3>
                    <form method="post" action="{{ route('doctor.availability.store') }}" class="mt-6 space-y-6">
                        @csrf
                        
                        <!-- Day of the Week -->
                        <div>
                            <x-input-label for="day_of_week" :value="__('Day of the Week')" />
                            <select id="day_of_week" name="day_of_week" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option value="Monday">Monday</option>
                                <option value="Tuesday">Tuesday</option>
                                <option value="Wednesday">Wednesday</option>
                                <option value="Thursday">Thursday</option>
                                <option value="Friday">Friday</option>
                                <option value="Saturday">Saturday</option>
                                <option value="Sunday">Sunday</option>
                            </select>
                        </div>

                        <!-- Start Time -->
                        <div>
                            <x-input-label for="start_time" :value="__('Start Time')" />
                            <x-text-input id="start_time" name="start_time" type="time" class="mt-1 block w-full" required />
                        </div>

                        <!-- End Time -->
                        <div>
                            <x-input-label for="end_time" :value="__('End Time')" />
                            <x-text-input id="end_time" name="end_time" type="time" class="mt-1 block w-full" required />
                        </div>

                        <div class="flex items-center gap-4">
                            <x-primary-button>{{ __('Add Schedule') }}</x-primary-button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Section to Display Current Availability -->
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <h3 class="text-lg font-medium text-gray-900">Your Current Schedule</h3>
                <div class="mt-4 space-y-2">
                    @if($availabilities->isNotEmpty())
                        @foreach($availabilities as $availability)
                            <div class="p-2 border rounded-md flex justify-between items-center">
                                <span>
                                    <strong>{{ $availability->day_of_week }}:</strong> 
                                    {{ \Carbon\Carbon::parse($availability->start_time)->format('h:i A') }} - 
                                    {{ \Carbon\Carbon::parse($availability->end_time)->format('h:i A') }}
                                </span>
                                {{-- We will add a delete button here in a future step --}}
                            </div>
                        @endforeach
                    @else
                        <p class="text-gray-600">You have not set any availability yet.</p>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
```5.  Save the file.