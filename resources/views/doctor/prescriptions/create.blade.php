<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Issue New Prescription') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    {{-- Display information about the appointment this prescription is for --}}
                    <div class="mb-6 p-4 border rounded-md bg-gray-50">
                        <h3 class="font-semibold text-lg">Appointment Details</h3>
                        <p><strong>Patient:</strong> {{ $appointment->patient->name }}</p>
                        <p><strong>Date:</strong> {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('D, M d, Y') }}</p>
                        <p><strong>Reason for visit:</strong> {{ $appointment->notes ?? 'N/A' }}</p>
                    </div>

                    <form method="POST" action="{{ route('prescriptions.store', $appointment) }}">
                        @csrf

                        <!-- Prescription Details -->
                        <div>
                            <x-input-label for="details" :value="__('Prescription Details (Medication, Dosage, Instructions)')" />
                            <textarea 
                                id="details" 
                                name="details" 
                                rows="10" 
                                class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                required
                                autofocus
                            >{{ old('details') }}</textarea>
                            <x-input-error :messages="$errors->get('details')" class="mt-2" />
                        </div>
                        
                        <div class="flex items-center gap-4 mt-6">
                            <x-primary-button>
                                {{ __('Save Prescription') }}
                            </x-primary-button>
                            <a href="{{ route('appointments.index') }}" class="text-sm text-gray-600 hover:text-gray-900">
                                Cancel
                            </a>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
