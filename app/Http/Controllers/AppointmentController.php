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
            // THE FIX: We now also load the 'prescription' relationship along with the doctor info
            $appointments = $user->patientAppointments()
                                 ->with(['doctor.doctorProfile', 'prescription'])
                                 ->orderBy('appointment_date', 'desc')
                                 ->orderBy('appointment_time', 'desc')
                                 ->get();

        } elseif ($user->role === 'doctor') {
            // THE FIX: We now also load the 'prescription' relationship along with the patient info
            $appointments = $user->doctorAppointments()
                                 ->with(['patient', 'prescription'])
                                 ->orderBy('appointment_date', 'desc')
                                 ->orderBy('appointment_time', 'desc')
                                 ->get();
        }
        
        // Return the view and pass the appointments to it
        return view('appointments.index', compact('appointments'));
    }

    /**
     * Store a newly created appointment in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'doctor_id' => 'required|exists:users,id',
            'appointment_date' => 'required|date|after_or_equal:today',
            'appointment_time' => 'required|date_format:H:i',
            'notes' => 'nullable|string|max:5000',
        ]);

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
        
        $existingAppointment = Appointment::where('doctor_id', $doctor->id)
            ->where('appointment_date', $validated['appointment_date'])
            ->where('appointment_time', $appointmentTime)
            ->exists();

        if ($existingAppointment) {
            return back()->with('error', 'This specific time slot has just been booked. Please choose another time.');
        }

        Appointment::create([
            'doctor_id' => $doctor->id,
            'patient_id' => Auth::id(),
            'appointment_date' => $validated['appointment_date'],
            'appointment_time' => $validated['appointment_time'],
            'notes' => $validated['notes'],
            'status' => 'scheduled',
        ]);

        return back()->with('success', 'Your appointment has been successfully requested!');
    }

    /**
     * Cancel the specified appointment by updating its status.
     */
    public function destroy(Appointment $appointment)
    {
        $user = Auth::user();

        if ($user->id !== $appointment->patient_id && $user->id !== $appointment->doctor_id) {
            abort(403, 'Unauthorized Action');
        }

        if ($appointment->status === 'scheduled') {
            $appointment->status = 'cancelled';
            $appointment->save();
        }

        return redirect()->route('appointments.index')->with('status', 'appointment-cancelled');
    }

    /**
     * Mark the specified appointment as complete.
     */
    public function complete(Appointment $appointment)
    {
        if (Auth::id() !== $appointment->doctor_id) {
            abort(403, 'Unauthorized Action');
        }

        if ($appointment->status === 'scheduled') {
            $appointment->status = 'completed';
            $appointment->save();
        }

        return redirect()->route('appointments.index')->with('status', 'appointment-completed');
    }
}