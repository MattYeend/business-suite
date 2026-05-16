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
        Schema::table('company_contacts', function (Blueprint $table) {
            $table->index(['first_name', 'last_name']);
            $table->index('email');
            $table->index('is_primary');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('company_contacts', function (Blueprint $table) {
            $table->dropIndex(['company_contacts_first_name_last_name_index']);
            $table->dropIndex(['company_contacts_email_index']);
            $table->dropIndex(['company_contacts_is_primary_index']);
        });
    }
};
