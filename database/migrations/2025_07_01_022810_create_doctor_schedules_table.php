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
        Schema::create('doctor_schedules', static function (Blueprint $table) {
            $table->id();
            $table->foreignId('doctor_id')->constrained('booking_doctors')->cascadeOnDelete();
            $table->tinyInteger('day');
            $table->time('start');
            $table->time('end');
            $table->time('break_start')->nullable()->default(null);
            $table->time('break_end')->nullable()->default(null);
            $table->string('clinic');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doctor_schedules');
    }
};
