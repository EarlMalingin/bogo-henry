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
        Schema::create('activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tutor_id')->constrained('tutors')->onDelete('cascade');
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->foreignId('session_id')->nullable()->constrained('tutoring_sessions')->onDelete('set null');
            $table->string('title');
            $table->text('description');
            $table->enum('type', ['activity', 'exam', 'assignment', 'quiz']);
            $table->enum('status', ['draft', 'sent', 'in_progress', 'completed', 'graded'])->default('draft');
            $table->text('instructions')->nullable();
            $table->json('questions')->nullable(); // Store questions as JSON
            $table->json('attachments')->nullable(); // Store file paths as JSON
            $table->datetime('due_date')->nullable();
            $table->integer('total_points')->default(0);
            $table->integer('time_limit')->nullable(); // in minutes
            $table->text('feedback')->nullable();
            $table->integer('score')->nullable();
            $table->datetime('submitted_at')->nullable();
            $table->datetime('graded_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activities');
    }
};
