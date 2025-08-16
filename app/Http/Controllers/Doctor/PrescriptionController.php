<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Prescription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PrescriptionController extends Controller
{
    /**
     * Show the form for creating a new prescription for a specific appointment.
     * This is called when a doctor clicks the "Issue Prescription" button.
     */
    public function create(Appointment $appointment)
    {
        // Authorization Check: Ensure the logged-in user is the actual doctor for this appointment.
        if (Auth::id() !== $appointment->doctor_id) {
            abort(403, 'Unauthorized Action');
        }

        // Authorization Check: Ensure a prescription doesn't already exist for this appointment.
        if ($appointment->prescription) {
            return redirect()->route('appointments.index')->with('error', 'A prescription already exists for this appointment.');
        }

        // Load the view file and pass the appointment data to it.
        return view('doctor.prescriptions.create', compact('appointment'));
    }

    /**
     * Store a newly created prescription in storage.
     * This is called when the doctor submits the prescription form.
     */
    public function store(Request $request, Appointment $appointment)
    {
        // Authorization Check: Double-check the doctor is the correct one.
        if (Auth::id() !== $appointment->doctor_id) {
            abort(403, 'Unauthorized Action');
        }

        // Validation: Ensure the 'details' text area is not empty.
        $validated = $request->validate([
            'details' => 'required|string|max:10000',
        ]);

        // Create the new prescription and link it to the appointment using the relationship.
        $appointment->prescription()->create([
            'details' => $validated['details'],
            'issue_date' => Carbon::now(), // Set the issue date to the current time.
        ]);

        // Redirect the doctor back to their main appointment list with a success message.
        return redirect()->route('appointments.index')->with('status', 'prescription-added');
    }

    /**
     * Display the specified, existing prescription.
     * This is called when a doctor or patient clicks the "View Prescription" button.
     */
    public function show(Prescription $prescription)
    {
        $user = Auth::user();
        $appointment = $prescription->appointment;

        // Authorization Check: Only the specific patient or doctor for this appointment can view it.
        if ($user->id !== $appointment->patient_id && $user->id !== $appointment->doctor_id) {
            abort(403, 'Unauthorized Action');
        }

        // Load the view file and pass the prescription data to it.
        return view('doctor.prescriptions.show', compact('prescription'));
    }
}