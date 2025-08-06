<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DoctorAvailabilityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Get the currently logged-in doctor
        $doctor = Auth::user();

        // Get all of their availability slots, ordered by day
        $availabilities = $doctor->availabilities()->get()->sortBy(function($model) {
            return array_search($model->day_of_week, ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday']);
        });

        return view('doctor.availability.index', compact('availabilities'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'day_of_week' => 'required|string|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ]);

        Auth::user()->availabilities()->create($request->all());

        return redirect()->route('doctor.availability.index')->with('status', 'availability-added');
    }
}