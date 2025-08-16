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
        Schema::create('prescriptions', function (Blueprint $table) {
            $table->id();

        // This links the prescription to a single, specific appointment.
        // 'onDelete('cascade')' means if the appointment is deleted, this prescription is also deleted.
            $table->foreignId('appointment_id')->constrained()->onDelete('cascade');
        
        // The main content of the prescription.
            $table->text('details');

            $table->date('issue_date');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prescriptions');
    }
};
