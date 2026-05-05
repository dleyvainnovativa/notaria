<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g., "Memorial Digital"
            $table->text('description')->nullable();
            $table->decimal('price', 8, 2);
            $table->string('currency', 3)->default('mxn');
            $table->string('stripe_product_id')->nullable(); // Optional: For syncing with Stripe Products
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
