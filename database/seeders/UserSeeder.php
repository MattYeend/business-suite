<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Team IDs
        // 1 = Head Office
        // 2 = Sales Department
        // 3 = IT Department
        // 4 = HR Department
        // 5 = Finance Department
        // 6 = Marketing Department

        // Create Super Admin User (No team - global access)
        $superAdmin = User::firstOrCreate(
            ['email' => 'superadmin@example.com'],
            [
                'name' => 'Super Admin',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'is_user' => false,
                'is_admin' => false,
                'is_super_admin' => true,
                'phone' => '+44 7700 900000',
                'timezone' => 'Europe/London',
                'locale' => 'en',
                'team_id' => null, // Super admins don't belong to a team
                'is_real' => true,
                'meta' => json_encode([
                    'department' => 'Management',
                    'office_location' => 'Head Office',
                ]),
                'created_by' => null,
            ]
        );

        // Create Admin Users
        $admin1 = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'is_user' => false,
                'is_admin' => true,
                'is_super_admin' => false,
                'phone' => '+44 7700 900001',
                'timezone' => 'Europe/London',
                'locale' => 'en',
                'team_id' => 1, // Head Office
                'is_real' => true,
                'meta' => json_encode([
                    'department' => 'Administration',
                    'office_location' => 'Head Office',
                ]),
                'created_by' => $superAdmin->id,
            ]
        );

        $admin2 = User::firstOrCreate(
            ['email' => 'john.admin@example.com'],
            [
                'name' => 'John Admin',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'is_user' => false,
                'is_admin' => true,
                'is_super_admin' => false,
                'phone' => '+44 7700 900002',
                'timezone' => 'Europe/London',
                'locale' => 'en',
                'team_id' => 2, // Sales Department
                'is_real' => true,
                'meta' => json_encode([
                    'department' => 'Sales',
                    'office_location' => 'Regional Office',
                ]),
                'created_by' => $superAdmin->id,
            ]
        );

        // Create Regular Users
        $user1 = User::firstOrCreate(
            ['email' => 'user@example.com'],
            [
                'name' => 'Regular User',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'is_user' => true,
                'is_admin' => false,
                'is_super_admin' => false,
                'phone' => '+44 7700 900003',
                'timezone' => 'Europe/London',
                'locale' => 'en',
                'team_id' => 2, // Sales Department
                'is_real' => true,
                'meta' => json_encode([
                    'department' => 'Sales',
                    'office_location' => 'Regional Office',
                ]),
                'created_by' => $admin1->id,
            ]
        );

        $user2 = User::firstOrCreate(
            ['email' => 'jane.smith@example.com'],
            [
                'name' => 'Jane Smith',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'is_user' => true,
                'is_admin' => false,
                'is_super_admin' => false,
                'phone' => '+44 7700 900004',
                'timezone' => 'Europe/London',
                'locale' => 'en',
                'team_id' => 6, // Marketing Department
                'is_real' => true,
                'meta' => json_encode([
                    'department' => 'Marketing',
                    'office_location' => 'Head Office',
                ]),
                'created_by' => $admin1->id,
            ]
        );

        $user3 = User::firstOrCreate(
            ['email' => 'bob.johnson@example.com'],
            [
                'name' => 'Bob Johnson',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'is_user' => true,
                'is_admin' => false,
                'is_super_admin' => false,
                'phone' => '+44 7700 900005',
                'timezone' => 'Europe/London',
                'locale' => 'en',
                'team_id' => 1, // Head Office
                'is_real' => true,
                'meta' => json_encode([
                    'department' => 'Support',
                    'office_location' => 'Remote',
                ]),
                'created_by' => $admin2->id,
            ]
        );

        // Sales Manager
        $salesManager = User::firstOrCreate(
            ['email' => 'sarah.sales@example.com'],
            [
                'name' => 'Sarah Sales Manager',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'is_user' => true,
                'is_admin' => false,
                'is_super_admin' => false,
                'phone' => '+44 7700 900010',
                'timezone' => 'Europe/London',
                'locale' => 'en',
                'team_id' => 2, // Sales Department
                'is_real' => true,
                'meta' => json_encode([
                    'department' => 'Sales',
                    'office_location' => 'Head Office',
                ]),
                'created_by' => $superAdmin->id,
            ]
        );

        // HR Manager
        $hrManager = User::firstOrCreate(
            ['email' => 'henry.hr@example.com'],
            [
                'name' => 'Henry HR Manager',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'is_user' => true,
                'is_admin' => false,
                'is_super_admin' => false,
                'phone' => '+44 7700 900011',
                'timezone' => 'Europe/London',
                'locale' => 'en',
                'team_id' => 4, // HR Department
                'is_real' => true,
                'meta' => json_encode([
                    'department' => 'Human Resources',
                    'office_location' => 'Head Office',
                ]),
                'created_by' => $superAdmin->id,
            ]
        );

        // System Administrator
        $sysAdmin = User::firstOrCreate(
            ['email' => 'steve.sysadmin@example.com'],
            [
                'name' => 'Steve SysAdmin',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'is_user' => false,
                'is_admin' => true,
                'is_super_admin' => false,
                'phone' => '+44 7700 900012',
                'timezone' => 'Europe/London',
                'locale' => 'en',
                'team_id' => 3, // IT Department
                'is_real' => true,
                'meta' => json_encode([
                    'department' => 'IT',
                    'office_location' => 'Head Office',
                ]),
                'created_by' => $superAdmin->id,
            ]
        );

        // Accountant
        $accountant = User::firstOrCreate(
            ['email' => 'alice.accountant@example.com'],
            [
                'name' => 'Alice Accountant',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'is_user' => true,
                'is_admin' => false,
                'is_super_admin' => false,
                'phone' => '+44 7700 900013',
                'timezone' => 'Europe/London',
                'locale' => 'en',
                'team_id' => 5, // Finance Department
                'is_real' => true,
                'meta' => json_encode([
                    'department' => 'Finance',
                    'office_location' => 'Head Office',
                ]),
                'created_by' => $superAdmin->id,
            ]
        );

        // Test User
        $testUser = User::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'is_user' => true,
                'is_admin' => false,
                'is_super_admin' => false,
                'phone' => '+44 7700 900099',
                'timezone' => 'Europe/London',
                'locale' => 'en',
                'team_id' => 1, // Head Office
                'is_real' => false,
                'meta' => json_encode([
                    'department' => 'Testing',
                    'office_location' => 'Test Environment',
                ]),
                'created_by' => $superAdmin->id,
            ]
        );

        $this->command->info('Users created/verified successfully!');
        $this->command->info('Super Admin: superadmin@example.com / password');
        $this->command->info('Admin: admin@example.com / password');
        $this->command->info('User: user@example.com / password');
        $this->command->info('');
        $this->command->info('Team IDs:');
        $this->command->info('1 = Head Office');
        $this->command->info('2 = Sales Department');
        $this->command->info('3 = IT Department');
        $this->command->info('4 = HR Department');
        $this->command->info('5 = Finance Department');
        $this->command->info('6 = Marketing Department');
    }
}
