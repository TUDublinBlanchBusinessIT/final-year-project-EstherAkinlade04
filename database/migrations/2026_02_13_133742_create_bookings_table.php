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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();

            // Link to users table
            $table->foreignId('user_id')
                  ->constrained()
                  ->onDelete('cascade');

            // Link to fitness_classes table
            $table->foreignId('fitness_class_id')
                  ->constrained()
                  ->onDelete('cascade');

            $table->timestamps();

            // Prevent duplicate bookings
            $table->unique(['user_id', 'fitness_class_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
