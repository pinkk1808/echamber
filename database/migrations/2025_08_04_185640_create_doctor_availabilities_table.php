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
        Schema::create('doctor_availabilities', function (Blueprint $table) {
            $table->id();
        // Link to the doctor in the 'users' table
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

        // The day of the week (e.g., 'Monday', 'Tuesday')
            $table->string('day_of_week'); 

        // The time slots
            $table->time('start_time');
            $table->time('end_time');
        
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doctor_availabilities');
    }
};
