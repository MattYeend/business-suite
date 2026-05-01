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
        $users = User::all();

        foreach ($users as $user) {
            // Set team context if user has a team
            if ($user->team_id) {
                setPermissionsTeamId($user->team_id);
            }

            // Assign base role based on their boolean flags
            if ($user->is_super_admin) {
                $user->assignRole('super-admin');
            } elseif ($user->is_admin) {
                $user->assignRole('admin');
            } elseif ($user->is_user) {
                $user->assignRole('user');
            }

            // Assign specialized roles based on email/name patterns
            if (str_contains($user->email, 'sales') && str_contains($user->name, 'Manager')) {
                $user->assignRole('sales-manager');
            } elseif (str_contains($user->email, 'hr')) {
                $user->assignRole('hr-manager');
            } elseif (str_contains($user->email, 'sysadmin')) {
                $user->assignRole('system-administrator');
            } elseif (str_contains($user->email, 'accountant')) {
                $user->assignRole('accountant');
            }

            // Clear team context
            if ($user->team_id) {
                setPermissionsTeamId(null);
            }
        }

        $this->command->info('Roles assigned to ' . $users->count() . ' users within their team contexts.');
    }
}
