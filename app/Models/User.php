<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    /**
 * Get the doctor profile associated with the user.
 */
    public function doctorProfile()
    {
        return $this->hasOne(DoctorProfile::class);
    }
    /**
 * Get the availabilities for the doctor.
 */
    public function availabilities()
    {
        return $this->hasMany(DoctorAvailability::class);
    }
    /**
 * Get the appointments for the user acting as a doctor.
 */
    public function doctorAppointments()
    {
        return $this->hasMany(Appointment::class, 'doctor_id');
    }

/**
 * Get the appointments for the user acting as a patient.
 */
    public function patientAppointments()
    {
        return $this->hasMany(Appointment::class, 'patient_id');
    }
}
