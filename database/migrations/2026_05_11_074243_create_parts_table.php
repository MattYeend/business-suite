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
        Schema::create('parts', function (Blueprint $table) {
            $table->id();
            $table->string('sku')->unique();
            $table->string('part_number')->nullable()->unique(); // Manufacturer's part number
            $table->string('barcode')->nullable()->unique();
            $table->string('name');
            $table->string('description');
            $table->string('brand')->nullable();
            $table->string('manufacturer')->nullable();
            $table->enum('type', [
                'raw_material',
                'finished_good',
                'consumable',
                'spare_part',
                'sub_assembly',
            ])->default('finished_good');
            $table->enum('status', [
                'active',
                'discontinued',
                'pending',
                'out_of_stock',
            ])->default('active');
            $table->string('unit_of_measure')->default('each'); // each, kg, litre, metre, etc.
             $table->decimal('height', 8, 2)->nullable();
            $table->decimal('width', 8, 2)->nullable();
            $table->decimal('length', 8, 2)->nullable();
            $table->decimal('weight', 8, 2)->nullable();
            $table->decimal('volume', 8, 2)->nullable();
            $table->string('colour')->nullable();
            $table->string('material')->nullable();

            $table->decimal('price', 10, 2); // Sell price
            $table->decimal('cost_price', 10, 2)->nullable(); // Buy/cost price
            $table->string('currency', 3)->default('GBP');
            $table->decimal('tax_rate', 5, 2)->nullable();
            $table->string('tax_code')->nullable();
            $table->decimal('discount_percentage', 5, 2)->nullable();

            $table->unsignedInteger('quantity')->default(0);
            $table->unsignedInteger('min_stock_level')->default(0);
            $table->unsignedInteger('max_stock_level')->nullable();
            $table->unsignedInteger('reorder_point')->nullable(); // Stock level that triggers reorder
            $table->unsignedInteger('reorder_quantity')->nullable(); // Quantity to reorder
            $table->unsignedInteger('lead_time_days')->nullable(); // Days to restock from supplier
            $table->string('warehouse_location')->nullable(); // e.g. Warehouse A
            $table->string('bin_location')->nullable(); // e.g. Shelf B3

            $table->boolean('is_active')->default(true);
            $table->boolean('is_purchasable')->default(true);
            $table->boolean('is_sellable')->default(true);
            $table->boolean('is_manufactured')->default(false); // Has a bill of materials
            $table->boolean('is_serialised')->default(false); // Track by serial number
            $table->boolean('is_batch_tracked')->default(false);// Track by batch/lot
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
        Schema::dropIfExists('parts');
    }
};
