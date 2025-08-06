<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AppointmentController extends Controller
{
    /**
     * Display a listing of appointments for the logged-in user.
     * This method checks the user's role and fetches the appropriate appointments.
     */
    public function index()
    {
        $user = Auth::user();
        $appointments = collect(); // Create an empty collection by default

        if ($user->role === 'patient') {
            // If the user is a patient, get their appointments.
            // Eager load the 'doctor' and the doctor's 'doctorProfile' for efficient display.
            $appointments = $user->patientAppointments()
                                 ->with('doctor.doctorProfile')
                                 ->orderBy('appointment_date', 'desc')
                                 ->orderBy('appointment_time', 'desc')
                                 ->get();

        } elseif ($user->role === 'doctor') {
            // If the user is a doctor, get their appointments.
            // Eager load the 'patient' information.
            $appointments = $user->doctorAppointments()
                                 ->with('patient')
                                 ->orderBy('appointment_date', 'desc')
                                 ->orderBy('appointment_time', 'desc')
                                 ->get();
        }
        
        // Return the view and pass the collected appointments to it.
        return view('appointments.index', compact('appointments'));
    }

    /**
     * Store a newly created appointment in storage.
     * This method handles the form submission from the doctor detail page.
     */
    public function store(Request $request)
    {
        // 1. Validate the incoming data from the form.
        $validated = $request->validate([
            'doctor_id' => 'required|exists:users,id',
            'appointment_date' => 'required|date|after_or_equal:today',
            'appointment_time' => 'required|date_format:H:i',
            'notes' => 'nullable|string|max:5000',
        ]);

        // 2. Check the doctor's availability.
        $doctor = User::findOrFail($validated['doctor_id']);
        $appointmentDay = Carbon::parse($validated['appointment_date'])->format('l');
        $appointmentTime = $validated['appointment_time'];

        $availability = $doctor->availabilities()
            ->where('day_of_week', $appointmentDay)
            ->where('start_time', '<=', $appointmentTime)
            ->where('end_time', '>=', $appointmentTime)
            ->first();

        if (!$availability) {
            return back()->with('error', 'The doctor is not available at the selected date or time.');
        }
        
        // 3. Prevent double-booking.
        $existingAppointment = Appointment::where('doctor_id', $doctor->id)
            ->where('appointment_date', $validated['appointment_date'])
            ->where('appointment_time', $appointmentTime)
            ->exists();

        if ($existingAppointment) {
            return back()->with('error', 'This specific time slot has just been booked. Please choose another time.');
        }

        // 4. Create the appointment.
        Appointment::create([
            'doctor_id' => $doctor->id,
            'patient_id' => Auth::id(),
            'appointment_date' => $validated['appointment_date'],
            'appointment_time' => $validated['appointment_time'],
            'notes' => $validated['notes'],
            'status' => 'scheduled',
        ]);

        // 5. Redirect back with a success message.
        return back()->with('success', 'Your appointment has been successfully requested!');
    }

    /**
     * NEW: Cancel the specified appointment by updating its status.
     *
     * @param  \App\Models\Appointment  $appointment
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Appointment $appointment)
    {
        $user = Auth::user();

        // AUTHORIZATION: Check if the logged-in user is either the patient or the doctor for this appointment.
        // This is a critical security check to prevent users from cancelling others' appointments.
        if ($user->id !== $appointment->patient_id && $user->id !== $appointment->doctor_id) {
            // If they are not authorized, block the action.
            abort(403, 'Unauthorized Action');
        }

        // Only allow cancellation if the appointment is still 'scheduled'.
        // This prevents cancelling an already completed or cancelled appointment.
        if ($appointment->status === 'scheduled') {
            $appointment->status = 'cancelled';
            $appointment->save();
        }

        // Redirect back to the appointments list with a success status message.
        return redirect()->route('appointments.index')->with('status', 'appointment-cancelled');
    }
}