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
        Schema::table('company_addresses', function (Blueprint $table) {
            $table->index('type');
            $table->index('city');
            $table->index('country');
            $table->index('is_primary');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('company_addresses', function (Blueprint $table) {
            $table->dropIndex(['company_addresses_type_index']);
            $table->dropIndex(['company_addresses_city_index']);
            $table->dropIndex(['company_addresses_country_index']);
            $table->dropIndex(['company_addresses_is_primary_index']);
        });
    }
};
