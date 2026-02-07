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
        Schema::create('mutasi_saldos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->enum('tipe', ['deposit', 'transaksi', 'refund', 'bonus', 'referral']);
            $table->decimal('jumlah', 18, 2);
            $table->foreignId('currency_id')->constrained('currencies');
            $table->decimal('saldo_sebelum', 18, 2);
            $table->decimal('saldo_sesudah', 18, 2);
            $table->string('keterangan', 255)->nullable();
            $table->timestamps();
            
            $table->index('user_id');
            $table->index('tipe');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mutasi_saldos');
    }
};
