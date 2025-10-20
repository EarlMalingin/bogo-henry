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
        Schema::create('unified_users', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('phone')->nullable();
            $table->string('profile_picture')->nullable();
            $table->enum('user_type', ['student', 'tutor', 'both'])->default('student');
            
            // Student-specific fields
            $table->string('student_id')->nullable()->unique();
            $table->string('year_level')->nullable();
            $table->string('course')->nullable();
            $table->text('subjects_interest')->nullable();
            
            // Tutor-specific fields
            $table->string('tutor_id')->nullable()->unique();
            $table->string('specialization')->nullable();
            $table->text('bio')->nullable();
            $table->decimal('session_rate', 8, 2)->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('unified_users');
    }
};
