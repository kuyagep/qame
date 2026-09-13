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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('join_code')->unique();
            $table->text('description')->nullable();
            $table->string('location');
            $table->dateTime('start_date'); // e.g. 2026-08-02 08:00:00
            $table->dateTime('end_date');   // e.g. 2026-08-03 17:00:00 (or same day for 1-day event)
            $table->integer('capacity')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
