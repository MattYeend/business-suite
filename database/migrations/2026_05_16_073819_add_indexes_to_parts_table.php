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
        Schema::table('parts', function (Blueprint $table) {
            $table->index('name');
            $table->index('type');
            $table->index('status');
            $table->index('brand');
            $table->index('manufacturer');
            $table->index('is_active');
            $table->index('warehouse_location');
            $table->index(['status', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('parts', function (Blueprint $table) {
            $table->dropIndex(['parts_name_index']);
            $table->dropIndex(['parts_type_index']);
            $table->dropIndex(['parts_status_index']);
            $table->dropIndex(['parts_brand_index']);
            $table->dropIndex(['parts_manufacturer_index']);
            $table->dropIndex(['parts_is_active_index']);
            $table->dropIndex(['parts_warehouse_location_index']);
            $table->dropIndex(['parts_status_is_active_index']);
        });
    }
};
