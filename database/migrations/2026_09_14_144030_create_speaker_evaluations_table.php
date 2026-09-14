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
        Schema::create('speaker_evaluations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('training_id')->constrained()->onDelete('cascade');
            $table->foreignId('training_session_id')->constrained()->onDelete('cascade');
            $table->string('speaker_name')->nullable();

            // Demographic Profile
            $table->string('name');
            $table->string('sex');
            $table->string('district');
            $table->string('school_office');
            $table->string('day')->nullable();

            // Rating Criteria (1 to 4)
            $table->tinyInteger('q1_started_on_time');
            $table->tinyInteger('q2_objectives_explained');
            $table->tinyInteger('q3_topics_understandable');
            $table->tinyInteger('q4_time_pace_sufficient');
            $table->tinyInteger('q5_establishes_rapport');
            $table->tinyInteger('q6_positive_environment');
            $table->tinyInteger('q7_communication_skills');
            $table->tinyInteger('q8_appropriate_technology');
            $table->tinyInteger('q9_synthesized_responses');
            $table->tinyInteger('q10_flexibility_adaptability');
            $table->tinyInteger('q11_professional_manner');
            $table->tinyInteger('q12_ended_on_time');

            // Feedback
            $table->text('key_insights')->nullable();
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
        Schema::dropIfExists('speaker_evaluations');
    }
};
