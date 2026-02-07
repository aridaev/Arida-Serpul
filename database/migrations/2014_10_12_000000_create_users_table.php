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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('username', 50)->unique();
            $table->string('password');
            $table->string('nama', 100);
            $table->string('no_hp', 20);
            $table->string('email', 100)->unique();
            $table->decimal('saldo', 18, 2)->default(0);
            $table->foreignId('currency_id')->constrained('currencies');
            $table->enum('level', ['admin', 'reseller', 'agen', 'member'])->default('member');
            $table->string('referral_code', 20)->unique();
            $table->foreignId('referred_by')->nullable()->constrained('users')->onDelete('set null');
            $table->decimal('komisi_referral', 5, 2)->default(0); // percentage
            $table->boolean('status')->default(true);
            $table->rememberToken();
            $table->timestamps();
            
            $table->index('username');
            $table->index('referral_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
