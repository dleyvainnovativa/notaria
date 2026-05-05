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
        Schema::create('memorials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('slug')->unique();
            $table->string('deceased_name');
            $table->date('birth_date')->nullable();
            $table->date('death_date')->nullable();
            $table->text('biography')->nullable();
            $table->string('profile_image_url')->nullable();
            $table->string('cover_image_url')->nullable();
            $table->boolean('is_public')->default(true);
            $table->string('access_password')->nullable();
            $table->string('status')->default('draft'); // draft, active, suspended
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('memorials');
    }
};
