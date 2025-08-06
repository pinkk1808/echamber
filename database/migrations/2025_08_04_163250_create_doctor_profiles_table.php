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
        Schema::create('doctor_profiles', function (Blueprint $table) {
            $table->id(); // A unique ID for the profile itself
        
        // This links the profile to a user in the 'users' table.
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
        
            $table->string('specialization')->nullable(); // e.g., "Cardiology", "Dermatology"
            $table->text('bio')->nullable(); // A short biography for the doctor
            $table->string('qualifications')->nullable(); // e.g., "MD, PhD"
            $table->string('profile_picture')->nullable(); // We'll store the path to the image file

            $table->timestamps(); // 'created_at' and 'updated_at' columns
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doctor_profiles');
    }
};
