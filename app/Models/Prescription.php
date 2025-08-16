<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prescription extends Model
{
    use HasFactory;

    protected $fillable = [
        'appointment_id',
        'details',
        'issue_date',
    ];

    /**
     * THE FIX IS HERE:
     * This tells Laravel to automatically convert the 'issue_date'
     * from a simple string into a powerful Carbon date object.
     */
    protected $casts = [
        'issue_date' => 'datetime',
    ];

    /**
     * Get the appointment that this prescription belongs to.
     */
    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }
}