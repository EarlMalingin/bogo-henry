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
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('user_type'); // 'student' or 'tutor'
            $table->string('action'); // 'cash_in', 'cash_out', 'payment_success', etc.
            $table->json('details'); // Additional details about the action
            $table->string('ip_address', 45); // Support IPv6
            $table->string('user_agent')->nullable();
            $table->timestamps();
            
            $table->index(['user_id', 'user_type']);
            $table->index('action');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
