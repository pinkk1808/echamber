<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    /**
     * Show the form for creating a new review for a specific appointment.
     */
    public function create(Appointment $appointment)
    {
        // Authorization Check: Ensure the logged-in user is the patient for this appointment.
        if (Auth::id() !== $appointment->patient_id) {
            abort(403, 'Unauthorized Action');
        }

        // Authorization Check: Ensure a review doesn't already exist for this appointment.
        if ($appointment->review) {
            return redirect()->route('appointments.index')->with('error', 'A review has already been submitted for this appointment.');
        }

        // Load the view file and pass the appointment object to it.
        return view('patient.reviews.create', compact('appointment'));
    }

    /**
     * Store a newly created review in storage.
     */
    public function store(Request $request, Appointment $appointment)
    {
        // Authorization Check: Double-check the patient is the correct one.
        if (Auth::id() !== $appointment->patient_id) {
            abort(403, 'Unauthorized Action');
        }

        // Validation: Ensure the rating is between 1 and 5, and the comment is not too long.
        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:5000',
        ]);

        // Create the new review and link it to the appointment, doctor, and patient.
        $appointment->review()->create([
            'doctor_id' => $appointment->doctor_id,
            'patient_id' => $appointment->patient_id,
            'rating' => $validated['rating'],
            'comment' => $validated['comment'],
        ]);

        // Redirect the patient back to their main appointment list with a success message.
        return redirect()->route('appointments.index')->with('status', 'review-added');
    }
}