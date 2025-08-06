<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Professional Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <section>
                        <header>
                            <h2 class="text-lg font-medium text-gray-900">
                                {{ __('Profile Information') }}
                            </h2>

                            <p class="mt-1 text-sm text-gray-600">
                                {{ __("Update your professional profile information.") }}
                            </p>
                        </header>

                        {{-- The form tag now includes enctype="multipart/form-data" which is essential for file uploads. --}}
                        <form method="post" action="{{ route('doctor.profile.update') }}" enctype="multipart/form-data" class="mt-6 space-y-6">
                            @csrf
                            @method('patch')

                            <!-- Specialization -->
                            <div>
                                <x-input-label for="specialization" :value="__('Specialization')" />
                                <x-text-input id="specialization" name="specialization" type="text" class="mt-1 block w-full" :value="old('specialization', $profile->specialization)" required autofocus />
                                <x-input-error class="mt-2" :messages="$errors->get('specialization')" />
                            </div>

                            <!-- Qualifications -->
                            <div>
                                <x-input-label for="qualifications" :value="__('Qualifications (e.g., MD, PhD)')" />
                                <x-text-input id="qualifications" name="qualifications" type="text" class="mt-1 block w-full" :value="old('qualifications', $profile->qualifications)" />
                                <x-input-error class="mt-2" :messages="$errors->get('qualifications')" />
                            </div>

                            <!-- Bio -->
                            <div>
                                <x-input-label for="bio" :value="__('Short Biography')" />
                                <textarea id="bio" name="bio" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('bio', $profile->bio) }}</textarea>
                                <x-input-error class="mt-2" :messages="$errors->get('bio')" />
                            </div>

                            <!-- NEW: Profile Picture Upload Section -->
                            <div class="mt-4">
                                <x-input-label for="profile_picture" :value="__('Profile Picture')" />
                                
                                <!-- Show current picture if it exists -->
                                @if ($profile->profile_picture)
                                    <img src="{{ asset('storage/' . $profile->profile_picture) }}" alt="Profile Picture" class="rounded-md h-20 w-20 object-cover my-2">
                                    <div class="mt-2">
                                        <input type="checkbox" name="remove_profile_picture" id="remove_profile_picture">
                                        <label for="remove_profile_picture" class="ml-2 text-sm text-gray-600">Remove current profile picture</label>
                                    </div>
                                @endif

                                <x-text-input id="profile_picture" name="profile_picture" type="file" class="mt-1 block w-full" />
                                <p class="text-sm text-gray-500 mt-1">Leave blank to keep the current picture. Max file size: 2MB.</p>
                                <x-input-error class="mt-2" :messages="$errors->get('profile_picture')" />
                            </div>
                            
                            <!-- Save Button and Status Message -->
                            <div class="flex items-center gap-4">
                                <x-primary-button>{{ __('Save') }}</x-primary-button>

                                @if (session('status') === 'profile-updated')
                                    <p
                                        x-data="{ show: true }"
                                        x-show="show"
                                        x-transition
                                        x-init="setTimeout(() => show = false, 2000)"
                                        class="text-sm text-gray-600"
                                    >{{ __('Saved.') }}</p>
                                @endif
                            </div>
                        </form>
                    </section>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>