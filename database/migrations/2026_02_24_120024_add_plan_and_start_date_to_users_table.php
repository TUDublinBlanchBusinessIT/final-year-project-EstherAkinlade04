<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {

            $table->string('plan_duration')
                  ->nullable()
                  ->after('membership_type');
            // monthly, quarterly, annual, 1day, 2day, 3day

            $table->date('start_date')
                  ->nullable()
                  ->after('plan_duration');

            $table->date('end_date')
                  ->nullable()
                  ->after('start_date');

            $table->decimal('price_paid', 8, 2)
                  ->nullable()
                  ->after('end_date');

        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'plan_duration',
                'start_date',
                'end_date',
                'price_paid'
            ]);
        });
    }
};