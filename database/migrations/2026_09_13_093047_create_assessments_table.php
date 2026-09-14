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
        Schema::create('assessments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('training_id')->constrained()->cascadeOnDelete();
            $table->enum('type', ['pretest', 'posttest']);
            $table->string('title')->nullable();
            $table->integer('passing_score')->default(75);
            $table->timestamps();

            // Prevents creating duplicate pretests/posttests for the same session or training
            $table->unique(['training_id',  'type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assessments');
    }
};
