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
        Schema::create('imageables', function (Blueprint $table) {
            $table->id();
            $table->foreignId('image_id')->constrained()->onDelete('cascade');
            $table->morphs('imageable');
            
            // Pivot-specific metadata
            $table->integer('sort_order')->default(0);
            $table->boolean('is_primary')->default(false);
            $table->string('usage_context')->nullable(); // 'thumbnail', 'gallery', 'technical_drawing'
            
            $table->timestamps();
            
            $table->unique(['image_id', 'imageable_id', 'imageable_type'], 'imageable_unique');
            $table->index('is_primary');
            $table->index('sort_order');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('imageables');
    }
};
