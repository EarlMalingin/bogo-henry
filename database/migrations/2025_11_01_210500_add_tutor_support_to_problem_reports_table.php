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
        Schema::table('problem_reports', function (Blueprint $table) {
            // Make student_id nullable
            $table->foreignId('student_id')->nullable()->change();
            
            // Add tutor_id column
            $table->foreignId('tutor_id')->nullable()->after('student_id')->constrained('tutors')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('problem_reports', function (Blueprint $table) {
            $table->dropForeign(['tutor_id']);
            $table->dropColumn('tutor_id');
            
            // Revert student_id to non-nullable
            $table->foreignId('student_id')->nullable(false)->change();
        });
    }
};
