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
        Schema::create('user_achievements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('achievement_id')->constrained()->onDelete('cascade');
            $table->morphs('user'); // user_type and user_id (for students and tutors)
            $table->timestamp('unlocked_at');
            $table->integer('progress')->default(0); // Progress towards achievement (0-100)
            $table->boolean('is_unlocked')->default(false);
            $table->timestamps();
            
            $table->unique(['achievement_id', 'user_type', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_achievements');
    }
};
