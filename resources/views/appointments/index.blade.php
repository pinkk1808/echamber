<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Appointments') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{-- Display a success message after cancelling an appointment --}}
                    @if(session('status') === 'appointment-cancelled')
                        <div class="mb-4 text-sm text-green-600 bg-green-100 p-3 rounded-md">
                            The appointment has been successfully cancelled.
                        </div>
                    @endif

                    @if($appointments->isNotEmpty())
                        <div class="space-y-4">
                            @foreach($appointments as $appointment)
                                <div class="p-4 border rounded-lg shadow-sm">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <p class="font-bold text-lg">
                                                {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('D, M d, Y') }}
                                                at
                                                {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}
                                            </p>
                                            
                                            @if(Auth::user()->role === 'patient')
                                                <p class="text-md text-gray-700">With: <strong>Dr. {{ $appointment->doctor->name }}</strong></p>
                                                <p class="text-sm text-gray-600">{{ $appointment->doctor->doctorProfile->specialization ?? '' }}</p>
                                            @elseif(Auth::user()->role === 'doctor')
                                                <p class="text-md text-gray-700">With: <strong>{{ $appointment->patient->name }}</strong></p>
                                                <p class="text-sm text-gray-600">Patient Email: {{ $appointment->patient->email }}</p>
                                            @endif
                                        </div>
                                        <div>
                                            {{-- Change the color of the status badge based on its value --}}
                                            <span @class([
                                                'inline-flex items-center px-3 py-1 rounded-full text-sm font-medium',
                                                'bg-blue-100 text-blue-800' => $appointment->status === 'scheduled',
                                                'bg-red-100 text-red-800' => $appointment->status === 'cancelled',
                                                'bg-green-100 text-green-800' => $appointment->status === 'completed',
                                            ])>
                                                {{ ucfirst($appointment->status) }}
                                            </span>
                                        </div>
                                    </div>
                                    @if($appointment->notes)
                                        <div class="mt-3 border-t pt-3">
                                            <p class="text-sm font-semibold">Reason for visit:</p>
                                            <p class="text-sm text-gray-600">{{ $appointment->notes }}</p>
                                        </div>
                                    @endif

                                    {{-- NEW: Cancel Button Form --}}
                                    {{-- This button only shows if the appointment is still scheduled --}}
                                    @if($appointment->status === 'scheduled')
                                        <div class="mt-4 border-t pt-4 text-right">
                                            <form method="POST" action="{{ route('appointments.destroy', $appointment) }}">
                                                @csrf
                                                @method('DELETE')
                                                <x-danger-button onclick="return confirm('Are you sure you want to cancel this appointment?')">
                                                    {{ __('Cancel Appointment') }}
                                                </x-danger-button>
                                            </form>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p>You have no appointments scheduled.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>