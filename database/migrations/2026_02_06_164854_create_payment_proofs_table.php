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
        Schema::create('payment_proofs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payment_id')->constrained('payments')->onDelete('cascade');
            $table->string('file_path', 255);
            $table->timestamp('uploaded_at')->useCurrent();
            $table->foreignId('verified_by')->nullable()->constrained('admins')->onDelete('set null');
            $table->timestamp('verified_at')->nullable();
            $table->enum('status', ['pending', 'valid', 'invalid'])->default('pending');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_proofs');
    }
};
