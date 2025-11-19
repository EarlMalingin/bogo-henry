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
        Schema::create('user_streaks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('user_type'); // App\Models\Student or App\Models\Tutor
            $table->string('streak_type'); // daily_login, activity_submission, perfect_score, on_time_submission
            $table->integer('current_count')->default(0);
            $table->integer('longest_count')->default(0);
            $table->date('last_activity_date')->nullable();
            $table->timestamps();
            
            $table->unique(['user_id', 'user_type', 'streak_type']);
            $table->index(['user_id', 'user_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_streaks');
    }
};

