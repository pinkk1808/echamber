<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Doctor\DoctorProfileController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\Doctor\DoctorAvailabilityController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\Doctor\PrescriptionController;
use App\Http\Controllers\Patient\ReviewController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// 1. PUBLIC ROUTES
Route::get('/', function () {
    return view('landing');
});

// 2. SHARED AUTHENTICATED ROUTES
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        $role = Auth::user()->role;
        switch ($role) {
            case 'admin': return redirect()->route('admin.dashboard');
            case 'doctor': return redirect()->route('doctor.dashboard');
            case 'patient': return redirect()->route('patient.dashboard');
            default: Auth::logout(); return redirect('/login');
        }
    })->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/my-appointments', [AppointmentController::class, 'index'])->name('appointments.index');
    Route::delete('/my-appointments/{appointment}', [AppointmentController::class, 'destroy'])->name('appointments.destroy');
    Route::get('/prescriptions/{prescription}', [PrescriptionController::class, 'show'])->name('prescriptions.show');
});

// 3. ROLE-SPECIFIC ROUTE GROUPS
Route::middleware(['auth', 'verified'])->group(function () {
    // Admin Routes
    Route::prefix('admin')->middleware('role:admin')->group(function () {
        Route::get('/dashboard', function () { return view('admin.dashboard'); })->name('admin.dashboard');
    });

    // Doctor Routes
    Route::prefix('doctor')->middleware('role:doctor')->group(function () {
        Route::get('/dashboard', function () { return view('doctor.dashboard'); })->name('doctor.dashboard');
        Route::get('/profile', [DoctorProfileController::class, 'edit'])->name('doctor.profile.edit');
        Route::patch('/profile', [DoctorProfileController::class, 'update'])->name('doctor.profile.update');
        Route::get('/availability', [DoctorAvailabilityController::class, 'index'])->name('doctor.availability.index');
        Route::post('/availability', [DoctorAvailabilityController::class, 'store'])->name('doctor.availability.store');
        Route::patch('/appointments/{appointment}/complete', [AppointmentController::class, 'complete'])->name('appointments.complete');
        
        // THE FIX IS HERE: The names are corrected from 'doctor.prescriptions.create' to 'prescriptions.create'
        Route::get('/appointments/{appointment}/prescriptions/create', [PrescriptionController::class, 'create'])->name('prescriptions.create');
        Route::post('/appointments/{appointment}/prescriptions', [PrescriptionController::class, 'store'])->name('prescriptions.store');
    });

    // Patient Routes
        Route::prefix('patient')->middleware(['auth', 'verified', 'role:patient'])->group(function () {
        Route::get('/dashboard', function () { return view('patient.dashboard'); })->name('patient.dashboard');
        Route::get('/doctors', [DoctorController::class, 'index'])->name('patient.doctors.index');
        Route::get('/doctors/{doctor}', [DoctorController::class, 'show'])->name('patient.doctors.show');
        Route::post('/appointments', [AppointmentController::class, 'store'])->name('appointments.store');
    
    // NEW: Routes for reviews, linked to an appointment
        Route::get('/appointments/{appointment}/reviews/create', [ReviewController::class, 'create'])->name('reviews.create');
        Route::post('/appointments/{appointment}/reviews', [ReviewController::class, 'store'])->name('reviews.store');
    });
});

// 4. AUTHENTICATION ROUTES
require __DIR__.'/auth.php';
