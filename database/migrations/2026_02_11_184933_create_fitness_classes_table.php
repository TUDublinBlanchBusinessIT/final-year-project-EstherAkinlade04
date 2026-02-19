<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fitness_classes', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->text('description');

            $table->dateTime('class_time');
            $table->integer('capacity');

            // 💰 Stripe-ready foundation
            $table->decimal('price', 8, 2)->default(20.00);

            // 📝 Admin-only notes for next session reminders
            $table->text('admin_notes')->nullable();

            // 🚫 Cancellation system
            $table->boolean('is_cancelled')->default(false);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fitness_classes');
    }
};
