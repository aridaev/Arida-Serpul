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
        Schema::create('referral_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // upline
            $table->foreignId('dari_user_id')->constrained('users')->onDelete('cascade'); // downline
            $table->string('trx_id', 50);
            $table->decimal('komisi', 18, 2);
            $table->foreignId('currency_id')->constrained('currencies');
            $table->timestamp('created_at')->useCurrent();
            
            $table->index('user_id');
            $table->index('dari_user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('referral_logs');
    }
};
