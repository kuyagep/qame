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
        Schema::create('day_evaluations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('training_id')->constrained()->onDelete('cascade');

            // Demographic Info
            $table->string('name')->nullable(); // Optional for anonymity
            $table->string('sex')->nullable();
            $table->string('school_office')->nullable();
            $table->string('day');

            // Program Management Criteria (1 to 4)
            $table->tinyInteger('pm1_time_management');
            $table->tinyInteger('pm2_clear_instructions');
            $table->tinyInteger('pm3_logical_organization');
            $table->tinyInteger('pm4_time_allotted');
            $table->tinyInteger('pm5_adequate_breaks');
            $table->tinyInteger('pm6_proper_structure');
            $table->tinyInteger('pm7_inclusive_language');
            $table->tinyInteger('pm8_efficient_management');
            $table->tinyInteger('pm9_pmt_responsiveness');

            // Training Venue Criteria (1 to 4)
            $table->tinyInteger('tv1_lighting_ventilation');
            $table->tinyInteger('tv2_space');
            $table->tinyInteger('tv3_soundproofing');
            $table->tinyInteger('tv4_cleanliness_restrooms');
            $table->tinyInteger('tv5_internet_connection');
            $table->tinyInteger('tv6_meal_quality');
            $table->tinyInteger('tv7_meal_nutrition');

            // Feedback
            $table->text('important_insights')->nullable();
            $table->text('suggestions')->nullable();

            $table->boolean('privacy_consent')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('day_evaluations');
    }
};
