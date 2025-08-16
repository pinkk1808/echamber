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
                    {{-- All success messages --}}
                    @if(session('status') === 'appointment-cancelled')
                        <div class="mb-4 text-sm text-green-600 bg-green-100 p-3 rounded-md">The appointment has been successfully cancelled.</div>
                    @endif
                    @if(session('status') === 'appointment-completed')
                        <div class="mb-4 text-sm text-green-600 bg-green-100 p-3 rounded-md">The appointment has been marked as completed.</div>
                    @endif
                    @if(session('status') === 'prescription-added')
                        <div class="mb-4 text-sm text-green-600 bg-green-100 p-3 rounded-md">The prescription has been successfully issued.</div>
                    @endif
                    {{-- NEW: Success message for adding a review --}}
                    @if(session('status') === 'review-added')
                        <div class="mb-4 text-sm text-green-600 bg-green-100 p-3 rounded-md">
                            Thank you for your feedback! Your review has been submitted.
                        </div>
                    @endif

                    @if($appointments->isNotEmpty())
                        <div class="space-y-4">
                            @foreach($appointments as $appointment)
                                <div class="p-4 border rounded-lg shadow-sm">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <p class="font-bold text-lg">
                                                {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('D, M d, Y') }} at {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}
                                            </p>
                                            
                                            @if(Auth::user()->role === 'patient')
                                                <p class="text-md text-gray-700">With: <strong>Dr. {{ $appointment->doctor->name }}</strong></p>
                                            @elseif(Auth::user()->role === 'doctor')
                                                <p class="text-md text-gray-700">With: <strong>{{ $appointment->patient->name }}</strong></p>
                                            @endif
                                        </div>
                                        <div>
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

                                    <div class="mt-4 border-t pt-4 flex justify-end items-center gap-x-4">
                                        {{-- Cancel Button --}}
                                        @if($appointment->status === 'scheduled')
                                            <form method="POST" action="{{ route('appointments.destroy', $appointment) }}">
                                                @csrf @method('DELETE')
                                                <x-danger-button onclick="return confirm('Are you sure?')">{{ __('Cancel') }}</x-danger-button>
                                            </form>
                                        @endif

                                        {{-- Complete Button --}}
                                        @if(Auth::user()->role === 'doctor' && $appointment->status === 'scheduled')
                                            <form method="POST" action="{{ route('appointments.complete', $appointment) }}">
                                                @csrf @method('PATCH')
                                                <x-primary-button>{{ __('Mark as Completed') }}</x-primary-button>
                                            </form>
                                        @endif
                                        
                                        {{-- Prescription Buttons --}}
                                        @if($appointment->status === 'completed')
                                            @if($appointment->prescription)
                                                <a href="{{ route('prescriptions.show', $appointment->prescription) }}" class="inline-flex items-center px-4 py-2 bg-gray-600 ...">
                                                    {{ __('View Prescription') }}
                                                </a>
                                            @elseif(Auth::user()->role === 'doctor')
                                                <a href="{{ route('prescriptions.create', $appointment) }}" class="inline-flex items-center px-4 py-2 bg-green-600 ...">
                                                    {{ __('Issue Prescription') }}
                                                </a>
                                            @endif
                                        @endif

                                        {{-- NEW: "Leave a Review" button for Patients --}}
                                        @if(Auth::user()->role === 'patient' && $appointment->status === 'completed' && !$appointment->review)
                                            <a href="{{ route('reviews.create', $appointment) }}" class="inline-flex items-center px-4 py-2 bg-yellow-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-400 active:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                                {{ __('Leave a Review') }}
                                            </a>
                                        @endif
                                    </div>
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