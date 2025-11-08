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
        Schema::create('achievements', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');
            $table->string('icon')->nullable(); // Font Awesome icon class
            $table->string('badge_color')->default('#4a90e2'); // Color for badge
            $table->enum('type', ['student', 'tutor', 'both'])->default('both');
            $table->string('category')->nullable(); // e.g., 'sessions', 'activities', 'social', 'milestone'
            $table->integer('points')->default(0); // Points awarded
            $table->integer('requirement_value')->nullable(); // e.g., 10 sessions, 5 activities
            $table->string('requirement_type')->nullable(); // e.g., 'sessions_completed', 'activities_submitted'
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('achievements');
    }
};
