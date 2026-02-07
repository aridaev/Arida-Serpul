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
        Schema::create('transaksis', function (Blueprint $table) {
            $table->id();
            $table->string('trx_id', 50)->unique();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('guest_id')->nullable()->constrained('guests')->onDelete('set null');
            $table->foreignId('produk_id')->constrained('produks');
            $table->foreignId('provider_id')->constrained('providers');
            $table->unsignedBigInteger('payment_id')->nullable(); // FK added after payments table created
            $table->decimal('referral_komisi', 18, 2)->default(0);
            $table->string('tujuan', 50); // nomor tujuan
            $table->decimal('harga', 18, 2);
            $table->decimal('modal', 18, 2);
            $table->decimal('laba', 18, 2);
            $table->foreignId('currency_id')->constrained('currencies');
            $table->string('sn', 100)->nullable(); // serial number dari provider
            $table->string('provider_ref', 100)->nullable();
            $table->text('response_provider')->nullable();
            $table->enum('status', ['pending', 'success', 'failed'])->default('pending');
            $table->timestamps();
            
            $table->index('trx_id');
            $table->index('user_id');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksis');
    }
};
