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
        Schema::create('assessment_responses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assessment_id')->constrained()->cascadeOnDelete();

            // Fixed ULID foreign key constraint
            $table->foreignUlid('user_id')->constrained('users')->cascadeOnDelete();

            $table->integer('total_score')->default(0);
            $table->integer('max_score')->default(0);
            $table->decimal('percentage', 5, 2)->default(0.00);
            $table->boolean('is_passed')->default(false);
            $table->integer('total_questions')->nullable();
            $table->timestamp('submitted_at')->useCurrent();
            $table->timestamps();

            // Prevent duplicate submissions if a user can only attempt a test once
            $table->unique(['assessment_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assessment_responses');
    }
};
