<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Prescription Details') }}</h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 md:p-8 text-gray-900">
                    <div class="flex justify-between items-start border-b pb-4">
                        <div>
                            <h3 class="text-2xl font-bold">Dr. {{ $prescription->appointment->doctor->name }}</h3>
                            <p>{{ $prescription->appointment->doctor->doctorProfile->specialization ?? '' }}</p>
                        </div>
                        <div class="text-right">
                            <p class="font-semibold">eChamber Digital Prescription</p>
                            <p class="text-sm text-gray-600">Issued On: {{ $prescription->issue_date->format('M d, Y') }}</p>
                        </div>
                    </div>
                    <div class="mt-6 border-b pb-4">
                        <h4 class="font-semibold">Patient Information</h4>
                        <p><strong>Name:</strong> {{ $prescription->appointment->patient->name }}</p>
                    </div>
                    <div class="mt-6">
                        <h4 class="font-bold text-lg mb-2">Rx:</h4>
                        <div class="prose max-w-none whitespace-pre-wrap bg-gray-50 p-4 rounded-md border">
                            {{ $prescription->details }}
                        </div>
                    </div>
                    <div class="mt-8 text-center">
                        <a href="{{ route('appointments.index') }}" class="text-sm text-indigo-600 hover:text-indigo-800">&larr; Back to My Appointments</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>