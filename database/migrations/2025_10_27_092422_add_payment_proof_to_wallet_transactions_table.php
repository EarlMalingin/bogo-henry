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
        Schema::table('wallet_transactions', function (Blueprint $table) {
            $table->string('payment_proof_path')->nullable()->after('reference_number');
            $table->text('payment_proof_description')->nullable()->after('payment_proof_path');
            $table->timestamp('payment_proof_uploaded_at')->nullable()->after('payment_proof_description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('wallet_transactions', function (Blueprint $table) {
            $table->dropColumn(['payment_proof_path', 'payment_proof_description', 'payment_proof_uploaded_at']);
        });
    }
};