<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Doctor\DoctorProfileController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\Doctor\DoctorAvailabilityController;
use App\Http\Controllers\AppointmentController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Public landing page
// After
Route::get('/', function () {
    return view('landing');
});
// "Smart" dashboard redirector
Route::get('/dashboard', function () {
    $role = Auth::user()->role;
    switch ($role) {
        case 'admin': return redirect()->route('admin.dashboard'); break;
        case 'doctor': return redirect()->route('doctor.dashboard'); break;
        case 'patient': return redirect()->route('patient.dashboard'); break;
        default: Auth::logout(); return redirect('/login')->with('error', 'Invalid user role.'); break;
    }
})->middleware(['auth', 'verified'])->name('dashboard');


// --- ROLE-PROTECTED ROUTE GROUPS ---

// Admin Routes
Route::prefix('admin')->middleware(['auth', 'verified', 'role:admin'])->group(function () {
    Route::get('/dashboard', function () { return view('admin.dashboard'); })->name('admin.dashboard');
});

// Doctor Routes
Route::prefix('doctor')->middleware(['auth', 'verified', 'role:doctor'])->group(function () {
    Route::get('/dashboard', function () { return view('doctor.dashboard'); })->name('doctor.dashboard');
    Route::get('/profile', [DoctorProfileController::class, 'edit'])->name('doctor.profile.edit');
    Route::patch('/profile', [DoctorProfileController::class, 'update'])->name('doctor.profile.update');
    Route::get('/availability', [DoctorAvailabilityController::class, 'index'])->name('doctor.availability.index');
    Route::post('/availability', [DoctorAvailabilityController::class, 'store'])->name('doctor.availability.store');
});

// Patient Routes
Route::prefix('patient')->middleware(['auth', 'verified', 'role:patient'])->group(function () {
    Route::get('/dashboard', function () { return view('patient.dashboard'); })->name('patient.dashboard');
    Route::get('/doctors', [DoctorController::class, 'index'])->name('patient.doctors.index');
    Route::get('/doctors/{doctor}', [DoctorController::class, 'show'])->name('patient.doctors.show');
    Route::post('/appointments', [AppointmentController::class, 'store'])->name('appointments.store');
});


// --- SHARED AUTHENTICATED ROUTES ---
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Appointment List
    Route::get('/my-appointments', [AppointmentController::class, 'index'])->name('appointments.index');

    // NEW: Cancel Appointment Route
    Route::delete('/my-appointments/{appointment}', [AppointmentController::class, 'destroy'])->name('appointments.destroy');
});

// Authentication routes
require __DIR__.'/auth.php';