<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('memorial_paragraphs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('memorial_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->text('content');
            $table->unsignedInteger('position')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('memorial_paragraphs');
    }
};
