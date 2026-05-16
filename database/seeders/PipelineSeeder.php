<?php

namespace Database\Seeders;

use App\Models\Pipeline;
use App\Models\User;
use Illuminate\Database\Seeder;

class PipelineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (Pipeline::exists()) {
            $this->command->info('Pipelines already seeded, skipping...');
            return;
        }

        $users = User::all();
        
        if ($users->isEmpty()) {
            $this->command->warn('No users found. Please run UserSeeder first.');
            return;
        }

        $pipelines = [
            // Sales Deal Pipeline
            [
                'name' => 'Sales Pipeline',
                'description' => 'Standard sales process for deals',
                'entity_type' => 'deal',
                'is_default' => true,
                'is_active' => true,
                'position' => 0,
                'is_real' => true,
                'created_by' => User::inRandomOrder()->first()?->id,
            ],

            // Order Fulfillment Pipeline
            [
                'name' => 'Order Fulfillment',
                'description' => 'Track orders from placement to delivery',
                'entity_type' => 'order',
                'is_default' => true,
                'is_active' => true,
                'position' => 0,
                'is_real' => true,
                'created_by' => User::inRandomOrder()->first()?->id,
            ],

            // Support Ticket Pipeline
            [
                'name' => 'Support Workflow',
                'description' => 'Customer support ticket lifecycle',
                'entity_type' => 'ticket',
                'is_default' => true,
                'is_active' => true,
                'position' => 0,
                'is_real' => true,
                'created_by' => User::inRandomOrder()->first()?->id,
            ],

            // Project Pipeline
            [
                'name' => 'Project Delivery',
                'description' => 'Standard project lifecycle',
                'entity_type' => 'project',
                'is_default' => true,
                'is_active' => true,
                'position' => 0,
                'is_real' => true,
                'created_by' => User::inRandomOrder()->first()?->id,
            ],

            // Task Pipeline
            [
                'name' => 'Task Workflow',
                'description' => 'Simple task management',
                'entity_type' => 'task',
                'is_default' => true,
                'is_active' => true,
                'position' => 0,
                'is_real' => true,
                'created_by' => User::inRandomOrder()->first()?->id,
            ],

            // Recruitment Pipeline
            [
                'name' => 'Recruitment Process',
                'description' => 'Candidate hiring workflow',
                'entity_type' => 'candidate',
                'is_default' => true,
                'is_active' => true,
                'position' => 0,
                'is_real' => true,
                'created_by' => User::inRandomOrder()->first()?->id,
            ],

            // Quote Pipeline
            [
                'name' => 'Quote Process',
                'description' => 'Quote to order conversion',
                'entity_type' => 'quote',
                'is_default' => true,
                'is_active' => true,
                'position' => 0,
                'is_real' => true,
                'created_by' => User::inRandomOrder()->first()?->id,
            ],
        ];

        $created = 0;

        foreach ($pipelines as $pipelineData) {
            $pipeline = Pipeline::firstOrCreate(
                [
                    'entity_type' => $pipelineData['entity_type'],
                    'name' => $pipelineData['name'],
                ],
                $pipelineData
            );

            if ($pipeline->wasRecentlyCreated) {
                $created++;
            }
        }

        $this->command->info("Created {$created} pipelines.");
    }
}
