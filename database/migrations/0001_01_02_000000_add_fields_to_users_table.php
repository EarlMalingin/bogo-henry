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
        Schema::table('users', function (Blueprint $table) {
            $table->string('first_name')->after('id');
            $table->string('last_name')->after('first_name');
            $table->string('student_id')->unique()->after('email');
            $table->string('year_level')->after('student_id');
            $table->string('course')->after('year_level');
            $table->text('subjects_interest')->nullable()->after('course');
            $table->string('phone')->nullable()->after('subjects_interest');
            $table->dropColumn('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'first_name',
                'last_name',
                'student_id',
                'year_level',
                'course',
                'subjects_interest',
                'phone',
            ]);
            $table->string('name')->after('id');
        });
    }
};
