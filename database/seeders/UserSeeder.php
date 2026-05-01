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
        $superAdmin = User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'is_user' => false,
            'is_admin' => false,
            'is_super_admin' => true,
            'phone' => '+44 7700 900000',
            'avatar' => null,
            'timezone' => 'Europe/London',
            'locale' => 'en',
            'team_id' => null, // Super admins don't belong to a team
            'is_real' => true,
            'meta' => json_encode([
                'department' => 'Management',
                'office_location' => 'Head Office',
            ]),
            'created_by' => null,
        ]);

        // Create Admin Users
        $admin1 = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'is_user' => false,
            'is_admin' => true,
            'is_super_admin' => false,
            'phone' => '+44 7700 900001',
            'avatar' => null,
            'timezone' => 'Europe/London',
            'locale' => 'en',
            'team_id' => 1, // Head Office
            'is_real' => true,
            'meta' => json_encode([
                'department' => 'Administration',
                'office_location' => 'Head Office',
            ]),
            'created_by' => $superAdmin->id,
        ]);

        $admin2 = User::create([
            'name' => 'John Admin',
            'email' => 'john.admin@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'is_user' => false,
            'is_admin' => true,
            'is_super_admin' => false,
            'phone' => '+44 7700 900002',
            'avatar' => null,
            'timezone' => 'Europe/London',
            'locale' => 'en',
            'team_id' => 2, // Sales Department
            'is_real' => true,
            'meta' => json_encode([
                'department' => 'Sales',
                'office_location' => 'Regional Office',
            ]),
            'created_by' => $superAdmin->id,
        ]);

        // Create Regular Users
        $user1 = User::create([
            'name' => 'Regular User',
            'email' => 'user@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'is_user' => true,
            'is_admin' => false,
            'is_super_admin' => false,
            'phone' => '+44 7700 900003',
            'avatar' => null,
            'timezone' => 'Europe/London',
            'locale' => 'en',
            'team_id' => 2, // Sales Department
            'is_real' => true,
            'meta' => json_encode([
                'department' => 'Sales',
                'office_location' => 'Regional Office',
            ]),
            'created_by' => $admin1->id,
        ]);

        $user2 = User::create([
            'name' => 'Jane Smith',
            'email' => 'jane.smith@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'is_user' => true,
            'is_admin' => false,
            'is_super_admin' => false,
            'phone' => '+44 7700 900004',
            'avatar' => null,
            'timezone' => 'Europe/London',
            'locale' => 'en',
            'team_id' => 6, // Marketing Department
            'is_real' => true,
            'meta' => json_encode([
                'department' => 'Marketing',
                'office_location' => 'Head Office',
            ]),
            'created_by' => $admin1->id,
        ]);

        $user3 = User::create([
            'name' => 'Bob Johnson',
            'email' => 'bob.johnson@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'is_user' => true,
            'is_admin' => false,
            'is_super_admin' => false,
            'phone' => '+44 7700 900005',
            'avatar' => null,
            'timezone' => 'Europe/London',
            'locale' => 'en',
            'team_id' => 1, // Head Office
            'is_real' => true,
            'meta' => json_encode([
                'department' => 'Support',
                'office_location' => 'Remote',
            ]),
            'created_by' => $admin2->id,
        ]);

        // Sales Manager
        $salesManager = User::create([
            'name' => 'Sarah Sales Manager',
            'email' => 'sarah.sales@example.com',
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
        ]);

        // HR Manager
        $hrManager = User::create([
            'name' => 'Henry HR Manager',
            'email' => 'henry.hr@example.com',
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
        ]);

        // System Administrator
        $sysAdmin = User::create([
            'name' => 'Steve SysAdmin',
            'email' => 'steve.sysadmin@example.com',
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
        ]);

        // Accountant
        $accountant = User::create([
            'name' => 'Alice Accountant',
            'email' => 'alice.accountant@example.com',
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
        ]);

        // Test User
        $testUser = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'is_user' => true,
            'is_admin' => false,
            'is_super_admin' => false,
            'phone' => '+44 7700 900099',
            'avatar' => null,
            'timezone' => 'Europe/London',
            'locale' => 'en',
            'team_id' => 1, // Head Office
            'is_real' => false,
            'meta' => json_encode([
                'department' => 'Testing',
                'office_location' => 'Test Environment',
            ]),
            'created_by' => $superAdmin->id,
        ]);

        // Generate additional random users in various teams
        $teams = [1, 2, 3, 4, 5, 6]; // Team IDs
        
        User::factory()
            ->count(10)
            ->create([
                'is_user' => true,
                'is_admin' => false,
                'is_super_admin' => false,
                'is_real' => true,
                'created_by' => $admin1->id,
            ])
            ->each(function ($user) use ($teams) {
                // Randomly assign to a team
                $user->update(['team_id' => $teams[array_rand($teams)]]);
            });

        // Generate some test users
        User::factory()
            ->count(5)
            ->create([
                'is_user' => true,
                'is_admin' => false,
                'is_super_admin' => false,
                'is_real' => false,
                'team_id' => 1, // Head Office
                'created_by' => $admin1->id,
            ]);

        $this->command->info('Users created successfully!');
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
