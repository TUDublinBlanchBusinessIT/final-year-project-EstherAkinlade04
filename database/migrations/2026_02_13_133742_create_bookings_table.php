<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('fitness_class_id')->constrained()->cascadeOnDelete();

            // Payment system foundation
            $table->string('payment_status')->default('unpaid'); 
            // unpaid | paid | refunded

            // Attendance tracking
            $table->boolean('attended')->default(false);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
