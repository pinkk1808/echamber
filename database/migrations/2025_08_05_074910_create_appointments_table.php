<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();

        // Link to the Doctor (who is a User)
            $table->foreignId('doctor_id')->constrained('users')->onDelete('cascade');
        
        // Link to the Patient (who is also a User)
            $table->foreignId('patient_id')->constrained('users')->onDelete('cascade');
        
            $table->date('appointment_date');
            $table->time('appointment_time');
        
        // We'll use this to track if the appointment is confirmed, completed, or canceled.
            $table->string('status')->default('scheduled'); 

            $table->text('notes')->nullable(); // Optional notes from the patient during booking.

            $table->timestamps();
        });
    }
     
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
