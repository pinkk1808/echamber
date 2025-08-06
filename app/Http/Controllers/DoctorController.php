<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class DoctorController extends Controller
{
    /**
     * Display a listing of the doctors.
     */
    public function index()
    {
        $doctors = User::where('role', 'doctor')
                        ->with('doctorProfile')
                        ->get();

        return view('patient.doctors.index', ['doctors' => $doctors]);
    }

    /**
     * Display the specified doctor's profile and availability.
     */
    public function show(User $doctor)
    {
        // Security check: ensure the user being viewed is a doctor.
        if ($doctor->role !== 'doctor') {
            abort(404, 'User not found or is not a doctor.');
        }

        // Load the necessary related data.
        $doctor->load('doctorProfile', 'availabilities');

        // Sort the availability for display.
        $sortedAvailabilities = $doctor->availabilities->sortBy(function($model) {
            return array_search($model->day_of_week, ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday']);
        });

        // This line tells Laravel to look for the file at:
        // resources/views/patient/doctors/show.blade.php
        return view('patient.doctors.show', [
            'doctor' => $doctor,
            'availabilities' => $sortedAvailabilities
        ]);
    }
}