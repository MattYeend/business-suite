<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            // User Management
            'view users',
            'create users',
            'edit users',
            'delete users',
            'restore users',
            
            // Company Management
            'view companies',
            'create companies',
            'edit companies',
            'delete companies',
            
            // Deal Management
            'view deals',
            'create deals',
            'edit deals',
            'delete deals',
            
            // Quote Management
            'view quotes',
            'create quotes',
            'edit quotes',
            'delete quotes',
            
            // Invoice Management
            'view invoices',
            'create invoices',
            'edit invoices',
            'delete invoices',
            
            // Task Management
            'view tasks',
            'create tasks',
            'edit tasks',
            'delete tasks',
            
            // Product Management
            'view products',
            'create products',
            'edit products',
            'delete products',
            
            // Supplier Management
            'view suppliers',
            'create suppliers',
            'edit suppliers',
            'delete suppliers',
            
            // Activity Management
            'view activities',
            'create activities',
            'edit activities',
            'delete activities',
            
            // Settings & Admin
            'manage settings',
            'manage roles',
            'manage permissions',
            'view reports',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Create roles and assign permissions
        
        // User Role - Basic permissions
        $userRole = Role::create(['name' => 'user']);
        $userRole->givePermissionTo([
            'view companies',
            'view deals',
            'view quotes',
            'view invoices',
            'view tasks',
            'view products',
            'view activities',
        ]);

        // Admin Role - Most permissions
        $adminRole = Role::create(['name' => 'admin']);
        $adminRole->givePermissionTo([
            'view users',
            'create users',
            'edit users',
            'view companies',
            'create companies',
            'edit companies',
            'delete companies',
            'view deals',
            'create deals',
            'edit deals',
            'delete deals',
            'view quotes',
            'create quotes',
            'edit quotes',
            'delete quotes',
            'view invoices',
            'create invoices',
            'edit invoices',
            'delete invoices',
            'view tasks',
            'create tasks',
            'edit tasks',
            'delete tasks',
            'view products',
            'create products',
            'edit products',
            'delete products',
            'view suppliers',
            'create suppliers',
            'edit suppliers',
            'delete suppliers',
            'view activities',
            'create activities',
            'edit activities',
            'view reports',
        ]);

        // Super Admin Role - All permissions
        $superAdminRole = Role::create(['name' => 'super-admin']);
        $superAdminRole->givePermissionTo(Permission::all());
    }
}
