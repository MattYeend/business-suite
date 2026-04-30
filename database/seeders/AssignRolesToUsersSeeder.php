<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class AssignRolesToUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all users
        $users = User::all();

        foreach ($users as $user) {
            // Assign role based on their boolean flags
            if ($user->is_super_admin) {
                $user->assignRole('super-admin');
            } elseif ($user->is_admin) {
                $user->assignRole('admin');
            } elseif ($user->is_user) {
                $user->assignRole('user');
            }
        }

        $this->command->info('Roles assigned to ' . $users->count() . ' users.');
    }
}
