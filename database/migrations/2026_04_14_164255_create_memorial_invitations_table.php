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
        Schema::create('memorial_invitations', function (Blueprint $table) {
            $table->id();

            $table->foreignId('memorial_id')->constrained()->onDelete('cascade');

            // Who sent the invite
            $table->foreignId('invited_by')->constrained('users')->onDelete('cascade');

            // Email of invited person (works even if user doesn't exist yet)
            $table->string('email');

            // Optional: link to user if already registered
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');

            // Role in the memorial
            $table->string('role')->default('viewer'); // viewer, editor, admin

            // Invitation flow
            $table->string('token')->unique();
            $table->timestamp('accepted_at')->nullable();
            $table->timestamp('expires_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('memorial_invitations');
    }
};
