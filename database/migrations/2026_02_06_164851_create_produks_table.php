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
        Schema::create('produks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kategori_id')->constrained('kategori_produks');
            $table->string('kode_provider', 50);
            $table->string('nama_produk', 255);
            $table->foreignId('provider_id')->constrained('providers');
            $table->decimal('harga_beli_idr', 18, 2);
            $table->decimal('harga_jual_idr', 18, 2);
            $table->boolean('status')->default(true); // active/nonactive
            $table->timestamps();
            
            $table->index('kode_provider');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produks');
    }
};
