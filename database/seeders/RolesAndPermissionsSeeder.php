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
                Permission::create(['name' => "{$action} {$module}"]);
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
            Permission::create(['name' => $permission]);
        }

        // Create roles with strategic permission sets
        $this->createRoles();
    }

    private function createRoles(): void
    {
        // 1. SUPER ADMIN - Full system access
        $superAdmin = Role::create(['name' => 'super-admin']);
        $superAdmin->givePermissionTo(Permission::all());

        // 2. ADMIN - Most permissions, no dangerous ones
        $admin = Role::create(['name' => 'admin']);
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

        // 3. SALES MANAGER
        $salesManager = Role::create(['name' => 'sales-manager']);
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

        // 4. SALES REP
        $salesRep = Role::create(['name' => 'sales-rep']);
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

        // 5. ACCOUNTANT
        $accountant = Role::create(['name' => 'accountant']);
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

        // 6. HR MANAGER
        $hrManager = Role::create(['name' => 'hr-manager']);
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

        // 7. HR STAFF
        $hrStaff = Role::create(['name' => 'hr-staff']);
        $hrStaff->givePermissionTo([
            'view users',
            'view employees', 'edit employees',
            'view departments',
            'view attendance', 'create attendance', 'edit attendance',
            'view leaves', 'create leaves', 'edit leaves',
            'view recruitment', 'edit recruitment',
        ]);

        // 8. EMPLOYEE (Regular employee)
        $employee = Role::create(['name' => 'employee']);
        $employee->givePermissionTo([
            'manage own data only',
            'view employees', // Can view other employees
            'view departments',
            'view attendance', 'create attendance', // Can clock in/out
            'view leaves', 'create leaves', // Can request leave
            'view payroll', // Can view own payroll
            'view performance', // Can view own performance
            'view courses', 'view lessons', // LMS access
        ]);

        // 9. TRAINING MANAGER (LMS)
        $trainingManager = Role::create(['name' => 'training-manager']);
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

        // 10. INSTRUCTOR
        $instructor = Role::create(['name' => 'instructor']);
        $instructor->givePermissionTo([
            'view courses', 'edit courses',
            'view lessons', 'create lessons', 'edit lessons',
            'view assessments', 'create assessments', 'edit assessments',
            'view certifications',
        ]);

        // 11. PROJECT MANAGER
        $projectManager = Role::create(['name' => 'project-manager']);
        $projectManager->givePermissionTo([
            'view users', 'view employees',
            'view projects', 'create projects', 'edit projects', 'delete projects',
            'view tasks', 'create tasks', 'edit tasks', 'delete tasks',
            'view activities', 'create activities', 'edit activities',
            'view documents', 'create documents', 'edit documents',
            'access reports',
        ]);

        // 12. SUPPORT AGENT
        $supportAgent = Role::create(['name' => 'support-agent']);
        $supportAgent->givePermissionTo([
            'view companies', 'view contacts',
            'view tasks', 'create tasks', 'edit tasks',
            'view activities', 'create activities', 'edit activities',
            'view calls', 'create calls', 'edit calls',
            'view emails', 'create emails',
        ]);

        // 13. VIEWER (Read-only)
        $viewer = Role::create(['name' => 'viewer']);
        $viewer->givePermissionTo([
            'view companies', 'view contacts',
            'view deals', 'view leads',
            'view quotes', 'view invoices',
            'view products',
            'view tasks', 'view activities',
        ]);

        // ===========================
        // IT DEPARTMENT ROLES
        // ===========================

        // 14. IT DIRECTOR
        $itDirector = Role::create(['name' => 'it-director']);
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

        // 15. SYSTEM ADMINISTRATOR
        $sysAdmin = Role::create(['name' => 'system-administrator']);
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

        // 16. NETWORK ADMINISTRATOR
        $networkAdmin = Role::create(['name' => 'network-administrator']);
        $networkAdmin->givePermissionTo([
            'view tickets', 'create tickets', 'edit tickets',
            'view assets', 'create assets', 'edit assets',
            'view servers', 'edit servers',
            'view networks', 'create networks', 'edit networks', 'delete networks',
            'view security_logs',
            'view system health',
            'export assets',
        ]);

        // 17. DATABASE ADMINISTRATOR
        $dbaAdmin = Role::create(['name' => 'database-administrator']);
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

        // 18. IT SUPPORT SPECIALIST
        $itSupport = Role::create(['name' => 'it-support-specialist']);
        $itSupport->givePermissionTo([
            'view users', 'edit users',
            'view tickets', 'create tickets', 'edit tickets',
            'view assets', 'create assets', 'edit assets',
            'view licenses',
            'view employees',
            'view departments',
        ]);

        // 19. SECURITY ANALYST
        $securityAnalyst = Role::create(['name' => 'security-analyst']);
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

        // 20. DevOps ENGINEER
        $devOps = Role::create(['name' => 'devops-engineer']);
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

        // ===========================
        // FINANCE/ERP ROLES
        // ===========================

        // 21. CHIEF FINANCIAL OFFICER (CFO)
        $cfo = Role::create(['name' => 'cfo']);
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

        // 22. FINANCIAL CONTROLLER
        $controller = Role::create(['name' => 'financial-controller']);
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

        // 23. ACCOUNTS PAYABLE CLERK
        $apClerk = Role::create(['name' => 'accounts-payable-clerk']);
        $apClerk->givePermissionTo([
            'view vendors', 'create vendors', 'edit vendors',
            'view purchase_orders', 'create purchase_orders', 'edit purchase_orders',
            'view expenses', 'create expenses', 'edit expenses',
            'view payments', 'create payments', 'edit payments',
            'view invoices',
            'export expenses', 'export payments',
        ]);

        // 24. ACCOUNTS RECEIVABLE CLERK
        $arClerk = Role::create(['name' => 'accounts-receivable-clerk']);
        $arClerk->givePermissionTo([
            'view companies', 'view contacts',
            'view deals', 'view quotes',
            'view invoices', 'create invoices', 'edit invoices',
            'view payments', 'create payments', 'edit payments',
            'export invoices', 'export payments',
        ]);

        // 25. BUDGET ANALYST
        $budgetAnalyst = Role::create(['name' => 'budget-analyst']);
        $budgetAnalyst->givePermissionTo([
            'view budgets', 'create budgets', 'edit budgets',
            'view expenses',
            'view departments',
            'access reports', 'access analytics',
            'export budgets', 'export expenses',
        ]);

        // 26. TAX SPECIALIST
        $taxSpecialist = Role::create(['name' => 'tax-specialist']);
        $taxSpecialist->givePermissionTo([
            'view companies', 'view contacts',
            'view invoices', 'view payments',
            'view tax_records', 'create tax_records', 'edit tax_records',
            'view fiscal_years',
            'access reports',
            'export tax_records',
        ]);

        // ===========================
        // PROCUREMENT & OPERATIONS
        // ===========================

        // 27. PROCUREMENT MANAGER
        $procurementManager = Role::create(['name' => 'procurement-manager']);
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

        // 28. PROCUREMENT SPECIALIST
        $procurementSpecialist = Role::create(['name' => 'procurement-specialist']);
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

        // 29. INVENTORY MANAGER
        $inventoryManager = Role::create(['name' => 'inventory-manager']);
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

        // 30. WAREHOUSE SUPERVISOR
        $warehouseSupervisor = Role::create(['name' => 'warehouse-supervisor']);
        $warehouseSupervisor->givePermissionTo([
            'view products',
            'view inventory', 'create inventory', 'edit inventory',
            'view warehouses', 'edit warehouses',
            'view shipments', 'create shipments', 'edit shipments',
            'view returns', 'create returns', 'edit returns',
            'view quality_control', 'create quality_control', 'edit quality_control',
            'export inventory',
        ]);

        // 31. WAREHOUSE STAFF
        $warehouseStaff = Role::create(['name' => 'warehouse-staff']);
        $warehouseStaff->givePermissionTo([
            'view products',
            'view inventory', 'edit inventory',
            'view shipments', 'edit shipments',
            'view returns', 'create returns',
        ]);

        // 32. LOGISTICS COORDINATOR
        $logisticsCoordinator = Role::create(['name' => 'logistics-coordinator']);
        $logisticsCoordinator->givePermissionTo([
            'view products', 'view inventory',
            'view warehouses',
            'view shipments', 'create shipments', 'edit shipments',
            'view returns', 'edit returns',
            'view suppliers',
            'export shipments',
        ]);

        // ===========================
        // MARKETING ROLES
        // ===========================

        // 33. MARKETING DIRECTOR
        $marketingDirector = Role::create(['name' => 'marketing-director']);
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

        // 34. MARKETING MANAGER
        $marketingManager = Role::create(['name' => 'marketing-manager']);
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

        // 35. CONTENT CREATOR
        $contentCreator = Role::create(['name' => 'content-creator']);
        $contentCreator->givePermissionTo([
            'view campaigns',
            'view content', 'create content', 'edit content',
            'view social_media', 'create social_media', 'edit social_media',
            'view documents', 'create documents', 'edit documents',
        ]);

        // 36. SOCIAL MEDIA MANAGER
        $socialMediaManager = Role::create(['name' => 'social-media-manager']);
        $socialMediaManager->givePermissionTo([
            'view campaigns',
            'view social_media', 'create social_media', 'edit social_media', 'delete social_media',
            'view content', 'create content', 'edit content',
            'view analytics',
            'export social_media',
        ]);

        // 37. EMAIL MARKETING SPECIALIST
        $emailMarketing = Role::create(['name' => 'email-marketing-specialist']);
        $emailMarketing->givePermissionTo([
            'view contacts', 'view leads',
            'view campaigns', 'create campaigns', 'edit campaigns',
            'view newsletters', 'create newsletters', 'edit newsletters',
            'view emails', 'create emails',
            'manage email templates',
            'view analytics',
            'export campaigns',
        ]);

        // 38. EVENT COORDINATOR
        $eventCoordinator = Role::create(['name' => 'event-coordinator']);
        $eventCoordinator->givePermissionTo([
            'view contacts', 'view leads',
            'view events', 'create events', 'edit events',
            'view tasks', 'create tasks', 'edit tasks',
            'view documents', 'create documents',
            'export events',
        ]);

        // ===========================
        // ADDITIONAL SPECIALIZED ROLES
        // ===========================

        // 39. RECRUITER
        $recruiter = Role::create(['name' => 'recruiter']);
        $recruiter->givePermissionTo([
            'view employees',
            'view positions',
            'view departments',
            'view recruitment', 'create recruitment', 'edit recruitment',
            'view tasks', 'create tasks', 'edit tasks',
            'view documents', 'create documents',
            'export recruitment',
        ]);

        // 40. PAYROLL SPECIALIST
        $payrollSpecialist = Role::create(['name' => 'payroll-specialist']);
        $payrollSpecialist->givePermissionTo([
            'view employees',
            'view attendance',
            'view payroll', 'create payroll', 'edit payroll',
            'view leaves',
            'export payroll', 'export attendance',
        ]);

        // 41. BUSINESS ANALYST
        $businessAnalyst = Role::create(['name' => 'business-analyst']);
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

        // 42. DATA ANALYST
        $dataAnalyst = Role::create(['name' => 'data-analyst']);
        $dataAnalyst->givePermissionTo([
            'view all data',
            'access reports', 'access analytics',
            'export data',
        ]);

        // 43. COMPLIANCE OFFICER
        $complianceOfficer = Role::create(['name' => 'compliance-officer']);
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

        // 44. QUALITY ASSURANCE MANAGER
        $qaManager = Role::create(['name' => 'qa-manager']);
        $qaManager->givePermissionTo([
            'view products', 'view inventory',
            'view quality_control', 'create quality_control', 'edit quality_control', 'delete quality_control',
            'view returns',
            'view suppliers',
            'access reports',
            'export quality_control',
        ]);

        // 45. CUSTOMER SUCCESS MANAGER
        $csManager = Role::create(['name' => 'customer-success-manager']);
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

        // 46. TEAM LEAD
        $teamLead = Role::create(['name' => 'team-lead']);
        $teamLead->givePermissionTo([
            'view users', 'view employees',
            'view projects', 'create projects', 'edit projects',
            'view tasks', 'create tasks', 'edit tasks', 'delete tasks',
            'view activities', 'create activities', 'edit activities',
            'view documents', 'create documents', 'edit documents',
            'view performance', 'create performance', 'edit performance',
            'access reports',
        ]);

        // 47. OPERATIONS MANAGER
        $opsManager = Role::create(['name' => 'operations-manager']);
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

        // 48. EXECUTIVE ASSISTANT
        $execAssistant = Role::create(['name' => 'executive-assistant']);
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

        // 49. INTERN
        $intern = Role::create(['name' => 'intern']);
        $intern->givePermissionTo([
            'manage own data only',
            'view companies', 'view contacts',
            'view products',
            'view tasks', 'create tasks',
            'view documents',
            'view employees', 'view departments',
            'view courses', 'view lessons',
        ]);

        // 50. CONTRACTOR
        $contractor = Role::create(['name' => 'contractor']);
        $contractor->givePermissionTo([
            'manage own data only',
            'view projects',
            'view tasks', 'create tasks', 'edit tasks',
            'view activities', 'create activities',
            'view documents', 'create documents',
        ]);
    }
}