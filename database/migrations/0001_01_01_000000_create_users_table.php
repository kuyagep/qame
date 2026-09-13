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
        Schema::create('users', function (Blueprint $table) {
            $table->ulid('id')->primary();
            // Name Breakdown
            $table->string('prefix')->nullable();
            $table->string('first_name')->nullable();
            $table->string('middle_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('suffix')->nullable();
            $table->string('name'); // Combined string
            $table->string('position')->nullable();
            // Auth & Credentials
            $table->string('username')->unique();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');

            // Profile Details
            $table->enum('sex', ['Male', 'Female'])->nullable();
            $table->string('mobile_number')->nullable();
            $table->date('birthdate')->nullable();
            $table->string('religion')->nullable();
            $table->string('disability')->default('No');
            $table->string('ethnic_group')->nullable();

            // Yajra Laravel-Address polymorphic alignment
            $table->unsignedBigInteger('province_id')->nullable();
            $table->unsignedBigInteger('city_id')->nullable();
            $table->unsignedBigInteger('barangay_id')->nullable();
            $table->string('street')->nullable(); // Used for your $request->purok, street value

            // Administrative Info
            $table->string('employee_id')->unique()->nullable();
            $table->string('avatar')->nullable();
            $table->string('status')->default('pending'); // active, inactive, suspended

            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->ulid('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
