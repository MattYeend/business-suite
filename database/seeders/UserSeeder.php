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
        // Create Super Admin User
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
            'is_real' => true,
            'meta' => json_encode([
                'department' => 'Management',
                'office_location' => 'Head Office',
            ]),
            'created_by' => null,
            'updated_by' => null,
        ]);
        $superAdmin->assignRole('super-admin');

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
            'is_real' => true,
            'meta' => json_encode([
                'department' => 'Administration',
                'office_location' => 'Head Office',
            ]),
            'created_by' => $superAdmin->id,
            'updated_by' => null,
        ]);
        $admin1->assignRole('admin');

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
            'is_real' => true,
            'meta' => json_encode([
                'department' => 'Sales',
                'office_location' => 'Regional Office',
            ]),
            'created_by' => $superAdmin->id,
            'updated_by' => null,
        ]);
        $admin2->assignRole('admin');

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
            'is_real' => true,
            'meta' => json_encode([
                'department' => 'Sales',
                'office_location' => 'Regional Office',
            ]),
            'created_by' => $admin1->id,
            'updated_by' => null,
        ]);
        $user1->assignRole('user');

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
            'is_real' => true,
            'meta' => json_encode([
                'department' => 'Marketing',
                'office_location' => 'Head Office',
            ]),
            'created_by' => $admin1->id,
            'updated_by' => null,
        ]);
        $user2->assignRole('user');

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
            'is_real' => true,
            'meta' => json_encode([
                'department' => 'Support',
                'office_location' => 'Remote',
            ]),
            'created_by' => $admin2->id,
            'updated_by' => null,
        ]);
        $user3->assignRole('user');

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
            'is_real' => false,
            'meta' => json_encode([
                'department' => 'Testing',
                'office_location' => 'Test Environment',
            ]),
            'created_by' => $superAdmin->id,
            'updated_by' => null,
        ]);
        $testUser->assignRole('user');

        // Generate additional random users using factory
        User::factory()
            ->count(10)
            ->create([
                'is_user' => true,
                'is_admin' => false,
                'is_super_admin' => false,
                'is_real' => true,
                'created_by' => $admin1->id,
            ])
            ->each(function ($user) {
                $user->assignRole('user');
            });

        // Generate some test users using factory
        User::factory()
            ->count(5)
            ->create([
                'is_user' => true,
                'is_admin' => false,
                'is_super_admin' => false,
                'is_real' => false, // Test users
                'created_by' => $admin1->id,
            ])
            ->each(function ($user) {
                $user->assignRole('user');
            });

        $this->command->info('Users created successfully!');
        $this->command->info('Super Admin: superadmin@example.com / password');
        $this->command->info('Admin: admin@example.com / password');
        $this->command->info('User: user@example.com / password');
    }
}
