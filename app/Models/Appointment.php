<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'doctor_id',
        'patient_id',
        'appointment_date',
        'appointment_time',
        'status',
        'notes',
    ];

    /**
     * Get the doctor for this appointment.
     */
    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    /**
     * Get the patient for this appointment.
     */
    public function patient()
    {
        return $this->belongsTo(User::class, 'patient_id');
    }
    public function prescription()
    {
        return $this->hasOne(Prescription::class);
    }
    public function review()
    {
        return $this->hasOne(Review::class);
    }
}