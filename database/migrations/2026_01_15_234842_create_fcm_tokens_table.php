<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fcm_tokens', function (Blueprint $table) {
            $table->id();

            // Link to the user who owns the device.
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // The device token from Firebase Cloud Messaging. Must be unique.
            $table->text('token')->unique();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fcm_tokens');
    }
};
