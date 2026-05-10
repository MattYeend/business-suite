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
        Schema::create('pipeline_stages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pipeline_id')->constrained('pipelines')->cascadeOnDelete();
            $table->string('name');
            $table->string('color')->nullable();
            $table->integer('position')->default(0);
            $table->boolean('is_terminal')->default(false); // Final stage (completed/won/lost/cancelled)
            $table->string('terminal_type')->nullable(); // won, lost, completed, cancelled, rejected
            $table->integer('probability')->nullable(); // Win probability % (0-100) for sales
            $table->integer('sla_hours')->nullable(); // Time limit for stage completion
            $table->boolean('requires_approval')->default(false);
            $table->boolean('is_real')->default(true);
            $table->json('meta')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('deleted_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('restored_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('restored_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['pipeline_id', 'position'], 'unique_position_per_pipeline');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pipeline_stages');
    }
};
