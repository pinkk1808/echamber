<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Leave a Review') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    {{-- Display information about the appointment and doctor being reviewed --}}
                    <div class="mb-6 p-4 border rounded-md bg-gray-50">
                        <h3 class="font-semibold text-lg">Reviewing Your Appointment</h3>
                        <p><strong>Doctor:</strong> Dr. {{ $appointment->doctor->name }}</p>
                        <p><strong>Date:</strong> {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('D, M d, Y') }}</p>
                    </div>

                    <form method="POST" action="{{ route('reviews.store', $appointment) }}">
                        @csrf

                        <!-- Star Rating -->
                        <div>
                            <x-input-label for="rating" :value="__('Overall Rating')" />
                            <select id="rating" name="rating" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                <option value="5">★★★★★ (Excellent)</option>
                                <option value="4">★★★★☆ (Very Good)</option>
                                <option value="3">★★★☆☆ (Good)</option>
                                <option value="2">★★☆☆☆ (Fair)</option>
                                <option value="1">★☆☆☆☆ (Poor)</option>
                            </select>
                            <x-input-error :messages="$errors->get('rating')" class="mt-2" />
                        </div>

                        <!-- Comment -->
                        <div class="mt-4">
                            <x-input-label for="comment" :value="__('Comment (optional)')" />
                            <textarea 
                                id="comment" 
                                name="comment" 
                                rows="5" 
                                class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                            >{{ old('comment') }}</textarea>
                            <x-input-error :messages="$errors->get('comment')" class="mt-2" />
                        </div>
                        
                        <div class="flex items-center gap-4 mt-6">
                            <x-primary-button>
                                {{ __('Submit Review') }}
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
```4.  **Save the file** (Ctrl + S).