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
        Schema::table('logs', function (Blueprint $table) {
            $table->index('action_id');
            $table->index('logged_in_user_id');
            $table->index('related_to_user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('logs', function (Blueprint $table) {
            $table->dropIndex(['logs_action_id_index']);
            $table->dropIndex(['logs_logged_in_user_id_index']);
            $table->dropIndex(['logs_related_to_user_id_index']);
        });
    }
};
