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
        Schema::table('pipelines', function (Blueprint $table) {
            $table->index('entity_type');
            $table->index('is_default');
            $table->index('is_active');
            $table->index(['entity_type', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pipelines', function (Blueprint $table) {
            $table->dropIndex(['pipelines_entity_type_index']);
            $table->dropIndex(['pipelines_is_default_index']);
            $table->dropIndex(['pipelines_is_active_index']);
            $table->dropIndex(['pipelines_entity_type_is_active_index']);
        });
    }
};
