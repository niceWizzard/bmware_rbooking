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
        Schema::create('patients', static function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->date('birthdate');
            $table->string('gender');
            $table->string('civil_status');
            $table->string('height')->nullable();
            $table->string('weight')->nullable();
            $table->string('mobile');
            $table->string('telephone')->nullable();
            $table->string('type_of_transaction');
            $table->string('card_number')->nullable();
            $table->text('address')->nullable();
            $table->text('notes')->nullable();
            $table->string('occupation')->nullable();
            $table->string('company_name')->nullable();
            $table->string('guardian_name')->nullable();
            $table->string('relationship')->nullable();
            $table->text('guardian_address')->nullable();
            $table->string('referring_physician')->nullable();
            $table->string('primary_care_physician')->nullable();
            $table->string('referring_institution')->nullable();
            $table->text('pastmedical')->nullable();
            $table->text('medical')->nullable();
            $table->text('vaccination')->nullable();
            $table->text('drug')->nullable();
            $table->string('dosage')->nullable();
            $table->text('obgyn')->nullable();
            $table->text('others')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};
