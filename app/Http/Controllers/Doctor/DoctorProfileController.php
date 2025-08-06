<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage; // Ensure this is imported

class DoctorProfileController extends Controller
{
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request)
    {
        $user = $request->user();
        $profile = $user->doctorProfile()->firstOrCreate([]);
        return view('doctor.profile', ['user' => $user, 'profile' => $profile]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        // Get the currently authenticated user's profile
        $profile = $request->user()->doctorProfile;

        // Validate the incoming form data
        $validatedData = $request->validate([
            'specialization' => 'required|string|max:255',
            'qualifications' => 'nullable|string|max:255',
            'bio' => 'nullable|string|max:5000',
            'profile_picture' => 'nullable|image|max:2048', // Max 2MB
        ]);

        // First, update the text-based fields
        $profile->fill($validatedData);

        // Handle the "Remove Picture" checkbox
        if ($request->filled('remove_profile_picture')) {
            if ($profile->profile_picture) {
                Storage::disk('public')->delete($profile->profile_picture);
            }
            $profile->profile_picture = null;
        }

        // Handle the NEW file upload
        if ($request->hasFile('profile_picture')) {
            // Delete the old file if it exists
            if ($profile->profile_picture) {
                Storage::disk('public')->delete($profile->profile_picture);
            }
            // Store the new file in 'storage/app/public/profile_pictures' and get the correct path
            $path = $request->file('profile_picture')->store('profile_pictures', 'public');
            // Save the correct public path to the profile
            $profile->profile_picture = $path;
        }

        // Save all changes to the database
        $profile->save();

        // Redirect back with a success message
        return redirect()->route('doctor.profile.edit')->with('status', 'profile-updated');
    }
}