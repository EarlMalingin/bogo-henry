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
        Schema::create('activity_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('activity_id')->constrained('activities')->onDelete('cascade');
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->json('answers')->nullable(); // Store student answers as JSON
            $table->json('attachments')->nullable(); // Store submitted files as JSON
            $table->text('notes')->nullable(); // Student notes or comments
            $table->enum('status', ['draft', 'submitted', 'graded'])->default('draft');
            $table->integer('score')->nullable();
            $table->text('feedback')->nullable();
            $table->datetime('submitted_at')->nullable();
            $table->datetime('graded_at')->nullable();
            $table->integer('time_spent')->nullable(); // Time spent in minutes
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_submissions');
    }
};
