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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('sku')->nullable()->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('price', 15, 2)->default(0);
            $table->string('currency', 8)->default('GBP');
            $table->enum('status', [
                'active',
                'discontinued',
                'pending',
                'out_of_stock',
            ])->default('active');
            $table->unsignedInteger('quantity')->default(0);
            $table->unsignedInteger('min_stock_level')->default(0);
            $table->unsignedInteger('max_stock_level')->nullable();
            $table->unsignedInteger('reorder_point')->nullable(); // Stock level that triggers reorder
            $table->unsignedInteger('reorder_quantity')->nullable(); // Quantity to reorder
            $table->unsignedInteger('lead_time_days')->nullable(); // Days to restock from supplier
            $table->boolean('is_real')->default(true);
            $table->json('meta')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('deleted_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('restored_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('restored_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
