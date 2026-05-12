<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RolesAndPermissionsSeeder::class,
            UserSeeder::class,
            AssignRolesToUsersSeeder::class,
            CompanyIndustrySeeder::class,
            CompanySeeder::class,
            CompanyContactSeeder::class,
            CompanyPhoneSeeder::class,
            CompanyAddressSeeder::class,
            PipelineSeeder::class,
            PipelineStageSeeder::class,
            PartSeeder::class,
            ProductSeeder::class,
        ]);
    }
}
