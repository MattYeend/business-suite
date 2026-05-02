<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Define permission groups with CRUD pattern
        $modules = [
            'users',
            'companies',
            'contacts',
            'deals',
            'leads',
            'opportunities',
            'quotes',
            'invoices',
            'payments',
            'products',
            'suppliers',
            'tasks',
            'projects',
            'activities',
            'calls',
            'meetings',
            'emails',
            'documents',
            'reports',
            'analytics',
            // HR specific
            'employees',
            'departments',
            'positions',
            'attendance',
            'payroll',
            'leaves',
            'performance',
            'recruitment',
            // LMS specific
            'courses',
            'lessons',
            'assessments',
            'certifications',
            'learning_paths',
            // IT specific
            'tickets',
            'assets',
            'licenses',
            'backups',
            'servers',
            'networks',
            'security_logs',
            'system_settings',
            // Finance/ERP specific
            'budgets',
            'expenses',
            'purchase_orders',
            'inventory',
            'vendors',
            'fiscal_years',
            'accounts',
            'journals',
            'tax_records',
            // Marketing specific
            'campaigns',
            'newsletters',
            'events',
            'social_media',
            'content',
            // Operations
            'warehouses',
            'shipments',
            'returns',
            'quality_control',
            // Procurement
            'requisitions',
            'bids',
            'contracts',
        ];

        $actions = ['view', 'create', 'edit', 'delete', 'export'];
        
        // Create CRUD permissions for each module
        foreach ($modules as $module) {
            foreach ($actions as $action) {
                Permission::firstOrCreate(['name' => "{$action} {$module}"]);
            }
        }

        // Additional special permissions
        $specialPermissions = [
            'manage settings',
            'manage roles',
            'manage permissions',
            'view all data', // See all records regardless of assignment
            'manage own data only', // Only see assigned records
            'approve quotes',
            'approve invoices',
            'approve payments',
            'approve leaves',
            'approve expenses',
            'approve purchase_orders',
            'approve budgets',
            'approve requisitions',
            'access reports',
            'access analytics',
            'export data',
            'import data',
            'delete permanently', // Bypass soft delete
            'restore deleted',
            'view audit logs',
            'manage integrations',
            'manage api keys',
            'impersonate users',
            'manage system backups',
            'access server console',
            'manage database',
            'view system health',
            'manage email templates',
            'manage workflows',
            'manage automations',
            'bulk operations',
            'override validations',
            'execute sql queries',
            'view error logs',
            'clear cache',
            'run migrations',
            'deploy updates',
        ];

        foreach ($specialPermissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create roles with strategic permission sets
        $this->createRoles();
    }

    private function createRoles(): void
    {
        // 1. SUPER ADMIN - Full system access
        $superAdmin = Role::firstOrCreate(['name' => 'super-admin']);
        if (!$superAdmin->permissions->count()) {
            $superAdmin->givePermissionTo(Permission::all());
        }

        // 2. ADMIN - Most permissions, no dangerous ones
        $admin = Role::firstOrCreate(['name' => 'admin']);
        if (!$admin->permissions->count()) {
            $admin->givePermissionTo([
                'view users', 'create users', 'edit users',
                'view companies', 'create companies', 'edit companies', 'delete companies',
                'view contacts', 'create contacts', 'edit contacts', 'delete contacts',
                'view deals', 'create deals', 'edit deals', 'delete deals',
                'view leads', 'create leads', 'edit leads', 'delete leads',
                'view quotes', 'create quotes', 'edit quotes', 'delete quotes',
                'view invoices', 'create invoices', 'edit invoices', 'delete invoices',
                'view payments', 'create payments', 'edit payments',
                'view products', 'create products', 'edit products', 'delete products',
                'view tasks', 'create tasks', 'edit tasks', 'delete tasks',
                'view activities', 'create activities', 'edit activities',
                'view employees', 'create employees', 'edit employees',
                'view departments', 'create departments', 'edit departments',
                'access reports', 'access analytics',
                'export data', 'import data',
                'restore deleted',
            ]);
        }

        // 3. SALES MANAGER
        $salesManager = Role::firstOrCreate(['name' => 'sales-manager']);
        if (!$salesManager->permissions->count()) {
            $salesManager->givePermissionTo([
                'view users',
                'view all data', // Can see all sales data
                'view companies', 'create companies', 'edit companies',
                'view contacts', 'create contacts', 'edit contacts',
                'view deals', 'create deals', 'edit deals', 'delete deals',
                'view leads', 'create leads', 'edit leads', 'delete leads',
                'view opportunities', 'create opportunities', 'edit opportunities', 'delete opportunities',
                'view quotes', 'create quotes', 'edit quotes', 'delete quotes',
                'approve quotes',
                'view invoices',
                'view products',
                'view tasks', 'create tasks', 'edit tasks',
                'view activities', 'create activities', 'edit activities',
                'access reports',
                'export deals', 'export leads', 'export quotes',
            ]);
        }

        // 4. SALES REP
        $salesRep = Role::firstOrCreate(['name' => 'sales-rep']);
        if (!$salesRep->permissions->count()) {
            $salesRep->givePermissionTo([
                'manage own data only', // Only see assigned records
                'view companies', 'create companies', 'edit companies',
                'view contacts', 'create contacts', 'edit contacts',
                'view deals', 'create deals', 'edit deals',
                'view leads', 'create leads', 'edit leads',
                'view opportunities', 'create opportunities', 'edit opportunities',
                'view quotes', 'create quotes', 'edit quotes',
                'view invoices',
                'view products',
                'view tasks', 'create tasks', 'edit tasks',
                'view activities', 'create activities', 'edit activities',
                'view calls', 'create calls', 'edit calls',
                'view meetings', 'create meetings', 'edit meetings',
                'view emails', 'create emails',
            ]);
        }

        // 5. ACCOUNTANT
        $accountant = Role::firstOrCreate(['name' => 'accountant']);
        if (!$accountant->permissions->count()) {
            $accountant->givePermissionTo([
                'view companies', 'view contacts',
                'view deals', 'view quotes',
                'view invoices', 'create invoices', 'edit invoices', 'delete invoices',
                'view payments', 'create payments', 'edit payments', 'delete payments',
                'approve payments',
                'view products',
                'access reports', 'access analytics',
                'export invoices', 'export payments',
            ]);
        }

        // 6. HR MANAGER
        $hrManager = Role::firstOrCreate(['name' => 'hr-manager']);
        if (!$hrManager->permissions->count()) {
            $hrManager->givePermissionTo([
                'view users', 'create users', 'edit users',
                'view employees', 'create employees', 'edit employees', 'delete employees',
                'view departments', 'create departments', 'edit departments', 'delete departments',
                'view positions', 'create positions', 'edit positions', 'delete positions',
                'view attendance', 'create attendance', 'edit attendance',
                'view payroll', 'create payroll', 'edit payroll',
                'view leaves', 'create leaves', 'edit leaves', 'delete leaves',
                'approve leaves',
                'view performance', 'create performance', 'edit performance',
                'view recruitment', 'create recruitment', 'edit recruitment',
                'access reports',
                'export employees', 'export payroll', 'export attendance',
            ]);
        }

        // 7. HR STAFF
        $hrStaff = Role::firstOrCreate(['name' => 'hr-staff']);
        if (!$hrStaff->permissions->count()) {
            $hrStaff->givePermissionTo([
                'view users',
                'view employees', 'edit employees',
                'view departments',
                'view attendance', 'create attendance', 'edit attendance',
                'view leaves', 'create leaves', 'edit leaves',
                'view recruitment', 'edit recruitment',
            ]);
        }

        // 8. EMPLOYER (Regular employee)
        $employer = Role::firstOrCreate(['name' => 'employer']);
        if (!$employer->permissions->count()) {
            $employer->givePermissionTo([
                'manage own data only',
                'view employees', // Can view other employees
                'view departments',
                'view attendance', 'create attendance', // Can clock in/out
                'view leaves', 'create leaves', // Can request leave
                'view payroll', // Can view own payroll
                'view performance', // Can view own performance
                'view courses', 'view lessons', // LMS access
            ]);
        }

        // 9. TRAINING MANAGER (LMS)
        $trainingManager = Role::firstOrCreate(['name' => 'training-manager']);
        if (!$trainingManager->permissions->count()) {
            $trainingManager->givePermissionTo([
                'view users', 'view employees',
                'view courses', 'create courses', 'edit courses', 'delete courses',
                'view lessons', 'create lessons', 'edit lessons', 'delete lessons',
                'view assessments', 'create assessments', 'edit assessments', 'delete assessments',
                'view certifications', 'create certifications', 'edit certifications',
                'view learning_paths', 'create learning_paths', 'edit learning_paths',
                'access reports',
                'export courses', 'export certifications',
            ]);
        }

        // 10. INSTRUCTOR
        $instructor = Role::firstOrCreate(['name' => 'instructor']);
        if (!$instructor->permissions->count()) {
            $instructor->givePermissionTo([
                'view courses', 'edit courses',
                'view lessons', 'create lessons', 'edit lessons',
                'view assessments', 'create assessments', 'edit assessments',
                'view certifications',
            ]);
        }

        // 11. PROJECT MANAGER
        $projectManager = Role::firstOrCreate(['name' => 'project-manager']);
        if (!$projectManager->permissions->count()) {
            $projectManager->givePermissionTo([
                'view users', 'view employees',
                'view projects', 'create projects', 'edit projects', 'delete projects',
                'view tasks', 'create tasks', 'edit tasks', 'delete tasks',
                'view activities', 'create activities', 'edit activities',
                'view documents', 'create documents', 'edit documents',
                'access reports',
            ]);
        }

        // 12. SUPPORT AGENT
        $supportAgent = Role::firstOrCreate(['name' => 'support-agent']);
        if (!$supportAgent->permissions->count()) {
            $supportAgent->givePermissionTo([
                'view companies', 'view contacts',
                'view tasks', 'create tasks', 'edit tasks',
                'view activities', 'create activities', 'edit activities',
                'view calls', 'create calls', 'edit calls',
                'view emails', 'create emails',
            ]);
        }

        // 13. VIEWER (Read-only)
        $viewer = Role::firstOrCreate(['name' => 'viewer']);
        if (!$viewer->permissions->count()) {
            $viewer->givePermissionTo([
                'view companies', 'view contacts',
                'view deals', 'view leads',
                'view quotes', 'view invoices',
                'view products',
                'view tasks', 'view activities',
            ]);
        }

        // 14. USER (Basic CRM/System User)
        $user = Role::firstOrCreate(['name' => 'user']);
        if (!$user->permissions->count()) {
            $user->givePermissionTo([
                'manage own data only',
                'view companies', 'view contacts',
                'view deals', 'view leads',
                'view quotes', 'view invoices',
                'view products',
                'view tasks', 'create tasks', 'edit tasks',
                'view activities', 'create activities', 'edit activities',
                'view calls', 'create calls', 'edit calls',
                'view meetings', 'create meetings', 'edit meetings',
                'view emails', 'create emails',
                'view documents', 'create documents',
            ]);
        }

        // ===========================
        // IT DEPARTMENT ROLES
        // ===========================

        // 15. IT DIRECTOR
        $itDirector = Role::firstOrCreate(['name' => 'it-director']);
        if (!$itDirector->permissions->count()) {
            $itDirector->givePermissionTo([
                'view users', 'create users', 'edit users',
                'view all data',
                'manage roles',
                'manage permissions',
                'view tickets', 'create tickets', 'edit tickets', 'delete tickets',
                'view assets', 'create assets', 'edit assets', 'delete assets',
                'view licenses', 'create licenses', 'edit licenses', 'delete licenses',
                'view backups', 'create backups', 'edit backups',
                'view servers', 'create servers', 'edit servers', 'delete servers',
                'view networks', 'create networks', 'edit networks',
                'view security_logs',
                'view system_settings', 'edit system_settings',
                'manage integrations',
                'manage api keys',
                'view audit logs',
                'access reports', 'access analytics',
                'manage system backups',
                'view system health',
                'export assets', 'export licenses', 'export tickets',
            ]);
        }

        // 16. SYSTEM ADMINISTRATOR
        $sysAdmin = Role::firstOrCreate(['name' => 'system-administrator']);
        if (!$sysAdmin->permissions->count()) {
            $sysAdmin->givePermissionTo([
                'view users', 'create users', 'edit users',
                'view tickets', 'create tickets', 'edit tickets',
                'view assets', 'create assets', 'edit assets', 'delete assets',
                'view licenses', 'create licenses', 'edit licenses',
                'view backups', 'create backups', 'edit backups', 'delete backups',
                'view servers', 'create servers', 'edit servers',
                'view networks', 'edit networks',
                'view security_logs',
                'view system_settings', 'edit system_settings',
                'manage integrations',
                'manage api keys',
                'view audit logs',
                'manage system backups',
                'access server console',
                'manage database',
                'view system health',
                'clear cache',
                'run migrations',
                'deploy updates',
                'execute sql queries',
                'view error logs',
            ]);
        }

        // 17. NETWORK ADMINISTRATOR
        $networkAdmin = Role::firstOrCreate(['name' => 'network-administrator']);
        if (!$networkAdmin->permissions->count()) {
            $networkAdmin->givePermissionTo([
                'view tickets', 'create tickets', 'edit tickets',
                'view assets', 'create assets', 'edit assets',
                'view servers', 'edit servers',
                'view networks', 'create networks', 'edit networks', 'delete networks',
                'view security_logs',
                'view system health',
                'export assets',
            ]);
        }

        // 18. DATABASE ADMINISTRATOR
        $dbaAdmin = Role::firstOrCreate(['name' => 'database-administrator']);
        if (!$dbaAdmin->permissions->count()) {
            $dbaAdmin->givePermissionTo([
                'view tickets', 'create tickets', 'edit tickets',
                'view backups', 'create backups', 'edit backups',
                'view security_logs',
                'manage database',
                'execute sql queries',
                'view audit logs',
                'view system health',
                'access server console',
                'run migrations',
            ]);
        }

        // 19. IT SUPPORT SPECIALIST
        $itSupport = Role::firstOrCreate(['name' => 'it-support-specialist']);
        if (!$itSupport->permissions->count()) {
            $itSupport->givePermissionTo([
                'view users', 'edit users',
                'view tickets', 'create tickets', 'edit tickets',
                'view assets', 'create assets', 'edit assets',
                'view licenses',
                'view employees',
                'view departments',
            ]);
        }

        // 20. SECURITY ANALYST
        $securityAnalyst = Role::firstOrCreate(['name' => 'security-analyst']);
        if (!$securityAnalyst->permissions->count()) {
            $securityAnalyst->givePermissionTo([
                'view users',
                'view tickets', 'create tickets', 'edit tickets',
                'view assets',
                'view security_logs', 'create security_logs',
                'view audit logs',
                'view system health',
                'access reports',
                'export security_logs',
            ]);
        }

        // 21. DevOps ENGINEER
        $devOps = Role::firstOrCreate(['name' => 'devops-engineer']);
        if (!$devOps->permissions->count()) {
            $devOps->givePermissionTo([
                'view tickets', 'create tickets', 'edit tickets',
                'view backups', 'create backups',
                'view servers', 'create servers', 'edit servers',
                'view networks',
                'access server console',
                'manage database',
                'view system health',
                'deploy updates',
                'run migrations',
                'clear cache',
                'view error logs',
            ]);
        }

        // ===========================
        // FINANCE/ERP ROLES
        // ===========================

        // 22. CHIEF FINANCIAL OFFICER (CFO)
        $cfo = Role::firstOrCreate(['name' => 'cfo']);
        if (!$cfo->permissions->count()) {
            $cfo->givePermissionTo([
                'view all data',
                'view companies', 'view contacts',
                'view deals', 'view quotes', 'view invoices', 'view payments',
                'view budgets', 'create budgets', 'edit budgets', 'delete budgets',
                'approve budgets',
                'view expenses', 'view purchase_orders',
                'approve expenses',
                'approve purchase_orders',
                'approve payments',
                'view accounts', 'view journals',
                'view fiscal_years', 'create fiscal_years', 'edit fiscal_years',
                'view tax_records',
                'view vendors',
                'access reports', 'access analytics',
                'export data',
            ]);
        }

        // 23. FINANCIAL CONTROLLER
        $controller = Role::firstOrCreate(['name' => 'financial-controller']);
        if (!$controller->permissions->count()) {
            $controller->givePermissionTo([
                'view companies', 'view contacts',
                'view invoices', 'create invoices', 'edit invoices',
                'view payments', 'create payments', 'edit payments',
                'approve payments',
                'view budgets', 'create budgets', 'edit budgets',
                'view expenses', 'create expenses', 'edit expenses',
                'approve expenses',
                'view accounts', 'create accounts', 'edit accounts',
                'view journals', 'create journals', 'edit journals',
                'view fiscal_years',
                'view tax_records', 'create tax_records', 'edit tax_records',
                'access reports', 'access analytics',
                'export invoices', 'export payments', 'export expenses',
            ]);
        }

        // 24. ACCOUNTS PAYABLE CLERK
        $apClerk = Role::firstOrCreate(['name' => 'accounts-payable-clerk']);
        if (!$apClerk->permissions->count()) {
            $apClerk->givePermissionTo([
                'view vendors', 'create vendors', 'edit vendors',
                'view purchase_orders', 'create purchase_orders', 'edit purchase_orders',
                'view expenses', 'create expenses', 'edit expenses',
                'view payments', 'create payments', 'edit payments',
                'view invoices',
                'export expenses', 'export payments',
            ]);
        }

        // 25. ACCOUNTS RECEIVABLE CLERK
        $arClerk = Role::firstOrCreate(['name' => 'accounts-receivable-clerk']);
        if (!$arClerk->permissions->count()) {
            $arClerk->givePermissionTo([
                'view companies', 'view contacts',
                'view deals', 'view quotes',
                'view invoices', 'create invoices', 'edit invoices',
                'view payments', 'create payments', 'edit payments',
                'export invoices', 'export payments',
            ]);
        }

        // 26. BUDGET ANALYST
        $budgetAnalyst = Role::firstOrCreate(['name' => 'budget-analyst']);
        if (!$budgetAnalyst->permissions->count()) {
            $budgetAnalyst->givePermissionTo([
                'view budgets', 'create budgets', 'edit budgets',
                'view expenses',
                'view departments',
                'access reports', 'access analytics',
                'export budgets', 'export expenses',
            ]);
        }

        // 27. TAX SPECIALIST
        $taxSpecialist = Role::firstOrCreate(['name' => 'tax-specialist']);
        if (!$taxSpecialist->permissions->count()) {
            $taxSpecialist->givePermissionTo([
                'view companies', 'view contacts',
                'view invoices', 'view payments',
                'view tax_records', 'create tax_records', 'edit tax_records',
                'view fiscal_years',
                'access reports',
                'export tax_records',
            ]);
        }

        // ===========================
        // PROCUREMENT & OPERATIONS
        // ===========================

        // 28. PROCUREMENT MANAGER
        $procurementManager = Role::firstOrCreate(['name' => 'procurement-manager']);
        if (!$procurementManager->permissions->count()) {
            $procurementManager->givePermissionTo([
                'view all data',
                'view vendors', 'create vendors', 'edit vendors', 'delete vendors',
                'view purchase_orders', 'create purchase_orders', 'edit purchase_orders', 'delete purchase_orders',
                'approve purchase_orders',
                'view requisitions', 'create requisitions', 'edit requisitions',
                'approve requisitions',
                'view bids', 'create bids', 'edit bids',
                'view contracts', 'create contracts', 'edit contracts',
                'view products', 'create products', 'edit products',
                'view suppliers', 'create suppliers', 'edit suppliers',
                'view inventory',
                'access reports',
                'export purchase_orders', 'export vendors',
            ]);
        }

        // 29. PROCUREMENT SPECIALIST
        $procurementSpecialist = Role::firstOrCreate(['name' => 'procurement-specialist']);
        if (!$procurementSpecialist->permissions->count()) {
            $procurementSpecialist->givePermissionTo([
                'view vendors', 'create vendors', 'edit vendors',
                'view purchase_orders', 'create purchase_orders', 'edit purchase_orders',
                'view requisitions', 'create requisitions', 'edit requisitions',
                'view bids', 'create bids', 'edit bids',
                'view contracts',
                'view products',
                'view suppliers',
                'export purchase_orders',
            ]);
        }

        // 30. INVENTORY MANAGER
        $inventoryManager = Role::firstOrCreate(['name' => 'inventory-manager']);
        if (!$inventoryManager->permissions->count()) {
            $inventoryManager->givePermissionTo([
                'view products', 'create products', 'edit products', 'delete products',
                'view inventory', 'create inventory', 'edit inventory', 'delete inventory',
                'view warehouses', 'create warehouses', 'edit warehouses',
                'view suppliers', 'create suppliers', 'edit suppliers',
                'view shipments', 'create shipments', 'edit shipments',
                'view returns', 'create returns', 'edit returns',
                'view quality_control', 'create quality_control', 'edit quality_control',
                'access reports',
                'export inventory', 'export products',
            ]);
        }

        // 31. WAREHOUSE SUPERVISOR
        $warehouseSupervisor = Role::firstOrCreate(['name' => 'warehouse-supervisor']);
        if (!$warehouseSupervisor->permissions->count()) {
            $warehouseSupervisor->givePermissionTo([
                'view products',
                'view inventory', 'create inventory', 'edit inventory',
                'view warehouses', 'edit warehouses',
                'view shipments', 'create shipments', 'edit shipments',
                'view returns', 'create returns', 'edit returns',
                'view quality_control', 'create quality_control', 'edit quality_control',
                'export inventory',
            ]);
        }

        // 32. WAREHOUSE STAFF
        $warehouseStaff = Role::firstOrCreate(['name' => 'warehouse-staff']);
        if (!$warehouseStaff->permissions->count()) {
            $warehouseStaff->givePermissionTo([
                'view products',
                'view inventory', 'edit inventory',
                'view shipments', 'edit shipments',
                'view returns', 'create returns',
            ]);
        }

        // 33. LOGISTICS COORDINATOR
        $logisticsCoordinator = Role::firstOrCreate(['name' => 'logistics-coordinator']);
        if (!$logisticsCoordinator->permissions->count()) {
            $logisticsCoordinator->givePermissionTo([
                'view products', 'view inventory',
                'view warehouses',
                'view shipments', 'create shipments', 'edit shipments',
                'view returns', 'edit returns',
                'view suppliers',
                'export shipments',
            ]);
        }

        // ===========================
        // MARKETING ROLES
        // ===========================

        // 34. MARKETING DIRECTOR
        $marketingDirector = Role::firstOrCreate(['name' => 'marketing-director']);
        if (!$marketingDirector->permissions->count()) {
            $marketingDirector->givePermissionTo([
                'view all data',
                'view companies', 'view contacts', 'view leads',
                'view campaigns', 'create campaigns', 'edit campaigns', 'delete campaigns',
                'view newsletters', 'create newsletters', 'edit newsletters', 'delete newsletters',
                'view events', 'create events', 'edit events', 'delete events',
                'view social_media', 'create social_media', 'edit social_media', 'delete social_media',
                'view content', 'create content', 'edit content', 'delete content',
                'view budgets',
                'approve budgets',
                'access reports', 'access analytics',
                'export campaigns', 'export leads',
            ]);
        }

        // 35. MARKETING MANAGER
        $marketingManager = Role::firstOrCreate(['name' => 'marketing-manager']);
        if (!$marketingManager->permissions->count()) {
            $marketingManager->givePermissionTo([
                'view companies', 'view contacts', 'view leads',
                'view campaigns', 'create campaigns', 'edit campaigns',
                'view newsletters', 'create newsletters', 'edit newsletters',
                'view events', 'create events', 'edit events',
                'view social_media', 'create social_media', 'edit social_media',
                'view content', 'create content', 'edit content',
                'access reports', 'access analytics',
                'export campaigns', 'export leads',
            ]);
        }

        // 36. CONTENT CREATOR
        $contentCreator = Role::firstOrCreate(['name' => 'content-creator']);
        if (!$contentCreator->permissions->count()) {
            $contentCreator->givePermissionTo([
                'view campaigns',
                'view content', 'create content', 'edit content',
                'view social_media', 'create social_media', 'edit social_media',
                'view documents', 'create documents', 'edit documents',
            ]);
        }

        // 37. SOCIAL MEDIA MANAGER
        $socialMediaManager = Role::firstOrCreate(['name' => 'social-media-manager']);
        if (!$socialMediaManager->permissions->count()) {
            $socialMediaManager->givePermissionTo([
                'view campaigns',
                'view social_media', 'create social_media', 'edit social_media', 'delete social_media',
                'view content', 'create content', 'edit content',
                'view analytics',
                'export social_media',
            ]);
        }

        // 38. EMAIL MARKETING SPECIALIST
        $emailMarketing = Role::firstOrCreate(['name' => 'email-marketing-specialist']);
        if (!$emailMarketing->permissions->count()) {
            $emailMarketing->givePermissionTo([
                'view contacts', 'view leads',
                'view campaigns', 'create campaigns', 'edit campaigns',
                'view newsletters', 'create newsletters', 'edit newsletters',
                'view emails', 'create emails',
                'manage email templates',
                'view analytics',
                'export campaigns',
            ]);
        }

        // 39. EVENT COORDINATOR
        $eventCoordinator = Role::firstOrCreate(['name' => 'event-coordinator']);
        if (!$eventCoordinator->permissions->count()) {
            $eventCoordinator->givePermissionTo([
                'view contacts', 'view leads',
                'view events', 'create events', 'edit events',
                'view tasks', 'create tasks', 'edit tasks',
                'view documents', 'create documents',
                'export events',
            ]);
        }

        // ===========================
        // ADDITIONAL SPECIALISED ROLES
        // ===========================

        // 40. RECRUITER
        $recruiter = Role::firstOrCreate(['name' => 'recruiter']);
        if (!$recruiter->permissions->count()) {
            $recruiter->givePermissionTo([
                'view employees',
                'view positions',
                'view departments',
                'view recruitment', 'create recruitment', 'edit recruitment',
                'view tasks', 'create tasks', 'edit tasks',
                'view documents', 'create documents',
                'export recruitment',
            ]);
        }

        // 41. PAYROLL SPECIALIST
        $payrollSpecialist = Role::firstOrCreate(['name' => 'payroll-specialist']);
        if (!$payrollSpecialist->permissions->count()) {
            $payrollSpecialist->givePermissionTo([
                'view employees',
                'view attendance',
                'view payroll', 'create payroll', 'edit payroll',
                'view leaves',
                'export payroll', 'export attendance',
            ]);
        }

        // 42. BUSINESS ANALYST
        $businessAnalyst = Role::firstOrCreate(['name' => 'business-analyst']);
        if (!$businessAnalyst->permissions->count()) {
            $businessAnalyst->givePermissionTo([
                'view companies', 'view contacts',
                'view deals', 'view leads', 'view opportunities',
                'view quotes', 'view invoices',
                'view products', 'view inventory',
                'view projects', 'view tasks',
                'view employees', 'view departments',
                'access reports', 'access analytics',
                'export data',
            ]);
        }

        // 43. DATA ANALYST
        $dataAnalyst = Role::firstOrCreate(['name' => 'data-analyst']);
        if (!$dataAnalyst->permissions->count()) {
            $dataAnalyst->givePermissionTo([
                'view all data',
                'access reports', 'access analytics',
                'export data',
            ]);
        }

        // 44. COMPLIANCE OFFICER
        $complianceOfficer = Role::firstOrCreate(['name' => 'compliance-officer']);
        if (!$complianceOfficer->permissions->count()) {
            $complianceOfficer->givePermissionTo([
                'view users', 'view employees',
                'view companies', 'view contacts',
                'view invoices', 'view payments',
                'view contracts',
                'view tax_records',
                'view audit logs',
                'view security_logs',
                'access reports',
                'export data',
            ]);
        }

        // 45. QUALITY ASSURANCE MANAGER
        $qaManager = Role::firstOrCreate(['name' => 'qa-manager']);
        if (!$qaManager->permissions->count()) {
            $qaManager->givePermissionTo([
                'view products', 'view inventory',
                'view quality_control', 'create quality_control', 'edit quality_control', 'delete quality_control',
                'view returns',
                'view suppliers',
                'access reports',
                'export quality_control',
            ]);
        }

        // 46. CUSTOMER SUCCESS MANAGER
        $csManager = Role::firstOrCreate(['name' => 'customer-success-manager']);
        if (!$csManager->permissions->count()) {
            $csManager->givePermissionTo([
                'view all data',
                'view companies', 'create companies', 'edit companies',
                'view contacts', 'create contacts', 'edit contacts',
                'view deals', 'view opportunities',
                'view tasks', 'create tasks', 'edit tasks',
                'view activities', 'create activities', 'edit activities',
                'view calls', 'create calls', 'edit calls',
                'view meetings', 'create meetings', 'edit meetings',
                'view emails', 'create emails',
                'access reports', 'access analytics',
            ]);
        }

        // 47. TEAM LEAD
        $teamLead = Role::firstOrCreate(['name' => 'team-lead']);
        if (!$teamLead->permissions->count()) {
            $teamLead->givePermissionTo([
                'view users', 'view employees',
                'view projects', 'create projects', 'edit projects',
                'view tasks', 'create tasks', 'edit tasks', 'delete tasks',
                'view activities', 'create activities', 'edit activities',
                'view documents', 'create documents', 'edit documents',
                'view performance', 'create performance', 'edit performance',
                'access reports',
            ]);
        }

        // 48. OPERATIONS MANAGER
        $opsManager = Role::firstOrCreate(['name' => 'operations-manager']);
        if (!$opsManager->permissions->count()) {
            $opsManager->givePermissionTo([
                'view all data',
                'view products', 'view inventory',
                'view warehouses', 'edit warehouses',
                'view shipments', 'edit shipments',
                'view purchase_orders',
                'view suppliers', 'view vendors',
                'view employees', 'view departments',
                'view tasks', 'create tasks', 'edit tasks',
                'access reports', 'access analytics',
                'export inventory', 'export shipments',
            ]);
        }

        // 49. EXECUTIVE ASSISTANT
        $execAssistant = Role::firstOrCreate(['name' => 'executive-assistant']);
        if (!$execAssistant->permissions->count()) {
            $execAssistant->givePermissionTo([
                'view companies', 'view contacts',
                'view deals', 'view leads',
                'view tasks', 'create tasks', 'edit tasks',
                'view activities', 'create activities', 'edit activities',
                'view calls', 'create calls', 'edit calls',
                'view meetings', 'create meetings', 'edit meetings',
                'view emails', 'create emails',
                'view documents', 'create documents', 'edit documents',
                'view employees', 'view departments',
            ]);
        }

        // 50. INTERN
        $intern = Role::firstOrCreate(['name' => 'intern']);
        if (!$intern->permissions->count()) {
            $intern->givePermissionTo([
                'manage own data only',
                'view companies', 'view contacts',
                'view products',
                'view tasks', 'create tasks',
                'view documents',
                'view employees', 'view departments',
                'view courses', 'view lessons',
            ]);
        }

        // 51. CONTRACTOR
        $contractor = Role::firstOrCreate(['name' => 'contractor']);
        if (!$contractor->permissions->count()) {
            $contractor->givePermissionTo([
                'manage own data only',
                'view projects',
                'view tasks', 'create tasks', 'edit tasks',
                'view activities', 'create activities',
                'view documents', 'create documents',
            ]);
        }
    }
}
