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
        Schema::table('kategori_produks', function (Blueprint $table) {
            $table->string('slug', 100)->unique()->nullable()->after('nama');
        });

        Schema::table('produks', function (Blueprint $table) {
            $table->string('slug', 255)->unique()->nullable()->after('nama_produk');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('produks', function (Blueprint $table) {
            $table->dropColumn('slug');
        });

        Schema::table('kategori_produks', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
};
