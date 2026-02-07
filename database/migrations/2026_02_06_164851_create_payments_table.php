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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('guest_id')->nullable()->constrained('guests')->onDelete('set null');
            $table->string('invoice_id', 50)->unique();
            $table->string('metode', 50); // bank_transfer, ewallet, qris, etc
            $table->enum('tipe_pembayaran', ['auto', 'manual'])->default('manual');
            $table->decimal('jumlah', 18, 2);
            $table->foreignId('currency_id')->constrained('currencies');
            $table->decimal('fee', 18, 2)->default(0);
            $table->decimal('total_bayar', 18, 2);
            $table->enum('status', ['pending', 'waiting_approval', 'paid', 'rejected', 'expired'])->default('pending');
            $table->string('provider_payment', 100)->nullable();
            $table->text('response_payment')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
            
            $table->index('invoice_id');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
