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
        Schema::create('providers', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 100);
            $table->string('api_url', 255);
            $table->string('api_key', 255);
            $table->enum('tipe', ['pulsa', 'pln', 'game', 'ewallet', 'paket_data']);
            $table->decimal('saldo', 18, 2)->default(0);
            $table->boolean('status')->default(true); // active/off
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('providers');
    }
};
