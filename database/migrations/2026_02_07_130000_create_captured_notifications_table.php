<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('captured_notifications', function (Blueprint $table) {
            $table->id();
            $table->string('device_id')->index();
            $table->string('device_name')->nullable();
            $table->string('package_name')->nullable()->index();
            $table->string('app_name')->nullable();
            $table->string('title')->nullable();
            $table->text('text')->nullable();
            $table->text('big_text')->nullable();
            $table->string('ticker')->nullable();
            $table->string('tag')->nullable();
            $table->string('category')->nullable();
            $table->json('extras')->nullable();
            $table->timestamp('posted_at')->nullable();
            $table->boolean('is_read')->default(false);
            $table->timestamps();
        });

        Schema::create('device_tokens', function (Blueprint $table) {
            $table->id();
            $table->string('device_id')->unique();
            $table->string('device_name')->nullable();
            $table->string('device_model')->nullable();
            $table->string('android_version')->nullable();
            $table->string('app_version')->nullable();
            $table->string('api_token', 80)->unique();
            $table->timestamp('last_seen_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('device_tokens');
        Schema::dropIfExists('captured_notifications');
    }
};
