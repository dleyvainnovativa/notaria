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
        Schema::table('memorial_invitations', function (Blueprint $table) {

            $table->dropColumn('role');

            $table->boolean('can_edit_info')->default(false);
            $table->boolean('can_edit_timeline')->default(false);
            $table->boolean('can_edit_life')->default(false);
            $table->boolean('can_edit_gallery')->default(false);
            $table->boolean('can_edit_messages')->default(false);

            // ⚡ Optional: prevent duplicate invites
            $table->unique(['memorial_id', 'email']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('memorial_invitations', function (Blueprint $table) {

            $table->string('role')->default('viewer');

            $table->dropColumn([
                'can_edit_info',
                'can_edit_timeline',
                'can_edit_life',
                'can_edit_gallery',
                'can_edit_messages',
            ]);

            $table->dropUnique(['memorial_id', 'email']);
        });
    }
};
