# CRM System

A Laravel 13 CRM, ERP, HR, LMS system

<!-- TOC -->
## Table of Contents

1. [Tech Stack](#tech-stack)
2. [General Information](#general-information)
    1. [Key Highlights](#key-highlights)
    2. [Core Features](#core-features)
    3. [Key Functional Areas](#key-functional-areas)
        1. [CRM Features](#crm-features)
        2. [ERP Features](#erp-features)
        3. [HR Features](#hr-features)
        4. [LMS Features](#lms-features)
3. [How To Setup](#how-to-setup)
4. [How To Contribute](#how-to-contribute)
    1. [Commit Conventions](#commit-conventions)
    2. [Maintainer Merge Strategy](#maintainer-merge-strategy)
5. [General CLI Commands](#general-cli-commands)
6. [Specific CLI Commands](#specific-cli-commands)
7. [Events and Listeners](#events-and-listeners)
    1. [Registered Events](#registered-events)
    2. [Model Observers](#model-observers)
8. [Roles and Permissions](#roles-and-permissions)
    1. [Key Concepts](#key-concepts)
    2. [Permission Structure](#permission-structure)
    3. [Core System Roles](#core-system-roles)
    4. [Department-Specific Roles](#department-specific-roles)
        1. [Sales & CRM](#sales--crm)
        2. [Finance & Accounting](#finance--accounting)
        3. [Human Resources](#human-resources)
        4. [IT Department](#it-department)
        5. [Operations & Procurement](#operations--procurement)
        6. [Marketing](#marketing)
        7. [Learning & Development](#learning--development)
        8. [Project Management](#project-management)
        9. [Specialised Roles](#specialised-roles)
    5. [Data Access Control](#data-access-control)
    6. [Usage Examples](#usage-examples)
        1. [Assign Role to User](#assign-role-to-user)
        2. [Assign Permission to Role](#assign-permission-to-role)
        3. [Assign Permission Directly to User](#assign-permission-directly-to-user)
        4. [Checking Permissions](#checking-permissions)
        5. [Controller Authorization](#controller-authorization)
        6. [Blade/Vue Directives](#bladevue-directives)
        7. [Route Middleware](#route-middleware)
    7. [CLI Commands](#cli-commands)
        1. [Sync User Roles](#sync-user-roles)
        2. [Clear Permission Cache](#clear-permission-cache)
    8. [Seeding Roles and Permissions](#seeding-roles-and-permissions)
    9. [Best Practices](#best-practices)
    10. [Architecture Notes](#architecture-notes)
9. [Sponsor The Project](#sponsor-the-project)
<!-- /TOC -->
<!-- /TOC -->

---

## Tech Stack

| Tech | Version |
|------|---------|
| PHP | 8.4.6 |
| Laravel | 13.6.0 |
| Composer | 2.8.8 |
| NPM | 11.13.0 |
| Node | v23.11.0 |
| VueJS | 3.5.33 |
| Vite | 8.0.10 |
| MySQL | 8.0.42 |

---

## General Information

This project is an all-in-one CRM/ERP/HR/LMS system designed to help businesses manage customers, leads, sales pipelines, projects, and internal workflows from a single, unified platform. It is built with Laravel 13, following established Laravel open-source conventions and best practices. The architecture emphasises clean separation of concerns, modular design, extensibility, and long-term maintainability.

### Key Highlights

- **Built in Laravel 13** - Leveraging the latest framework features for performance, security, and scalability
- **Modular & Extensible** - Easily add new modules or integrate with external APIs
- **User-Friendly Interface** - Modern, responsive design for smooth navigation and usability

### Core Features

- **Customer & Lead Management** - Organise contacts, track leads, and maintain detailed profiles
- **Role-Based Access Control** - Secure user management with customizable permissions
- **Analytics & Reporting** - Gain insights into business performance with dynamic dashboards

### Key Functional Areas

#### CRM Features

- **Lead management & qualification** - Capture leads from multiple sources, track status, score and qualify prospects, and convert them into deals or customers
- **Deal pipelines & stage tracking** - Visual sales pipelines with configurable stages, probability tracking, forecasting, and performance insights
- **Contact & company management** - Centralised database of individuals and organisations, including communication history, linked deals, and related projects
- **Activity logging (calls, emails, notes)** - Full interaction timeline per lead, contact, or deal to maintain context and improve collaboration
- **Task assignment & follow-ups** - Assign tasks to team members, set deadlines, reminders, and ensure timely follow-ups

#### ERP Features

- **Project management** - Manage projects from initiation to completion, assign team members, track progress, and monitor milestones
- **Invoicing & billing** - Generate invoices, manage payment statuses, track outstanding balances, and maintain financial records
- **Role-based access control** - Fine-grained permission system to control access to modules, actions, and sensitive data
- **Workflow automation** - Automate repetitive processes such as status changes, notifications, and task creation based on business rules
- **Reporting & dashboards** - Real-time insights into sales performance, revenue, pipeline health, and operational metrics

#### HR Features

- **Employee management & records** - Centralised employee database with personal details, job roles, departments, contracts, and document storage
- **Attendance & leave management** - Track working hours, holidays, sick leave, and approvals with configurable policies and calendars
- **Performance management & reviews** - Set objectives, conduct performance appraisals, track KPIs, and support employee development plans
- **Recruitment & onboarding** - Manage job postings, applications, candidate pipelines, interviews, and structured onboarding workflows
- **Document management & compliance** - Store contracts, policies, and compliance documents with version control and audit trails

#### LMS Features

- **Course creation & management** - Build structured courses with modules, lessons, and multimedia content (video, documents, quizzes)
- **Progress tracking & completion status** - Monitor learner progress, completion rates, and performance metrics in real-time
- **Assessments & quizzes** - Create quizzes, tests, and assignments with automated grading and feedback mechanisms
- **Certifications & achievements** - Issue certificates upon course completion and track employee learning milestones
- **Reporting & analytics** - Gain insights into training effectiveness, engagement levels, and skill gaps across the organisation

---

## How To Setup

Follow these steps to set up the project locally:

1. Clone the repository

```bash
git clone https://github.com/MattYeend/business-suite.git
cd business-suite
```

2. Install PHP dependencies

```bash
composer install
```

3. Install Node dependencies

```bash
npm install && npm run build
```

4. Set up environment

```bash
cp .env.example .env
php artisan key:generate
```

5. Configure your database in `.env` and run migrations:

```bash
php artisan migrate
```

6. Seed all tables if needed:

```bash
php artisan db:seed
```

7. Set up storage

```bash
php artisan storage:link
```

8. Run the development servers

```bash
php artisan serve
npm run dev
```

---

## How To Contribute

This project follows the standard Laravel OSS fork-and-pull-request workflow, used by most open-source Laravel packages and applications.

1. Fork the repository.
2. Create a new branch: `git checkout -b feature/your-feature-name`.
3. Make your changes and commit: `git commit -m '#issue-number Add your message here'`.
4. Run `php artisan insights` and make any relevant changes that it might suggest.
5. Ensure that the relevant language file(s) have been created.
6. Ensure there's relevant tests and that they work and pass.
7. If anything requires `vue.js` changes, run: `npm i && npm run build`.
8. Push to your fork: `git push origin feature/your-feature-name`.
9. Create a Pull Request.

Please follow the code style and commit message conventions.

---

### Commit Conventions

To keep the commit history clean and consistent, please follow these conventions:

```graphql
#issue-number Short, clear description in the imperative mood
```

Examples

```graphql
#42 Add customer export feature
#87 Fix validation for lead creation
#101 Refactor role permission checks
```

Guidelines:

- Reference an issue number where applicable
- Use the imperative mood ("Add", not "Added")
- Keep commits focused and descriptive
- Avoid bundling unrelated changes into a single commit
Maintainers may squash commits on merge.

---

### Maintainer Merge Strategy

For clarity and transparency:

- External contributors do not merge directly
- All changes enter the project via Pull Requests
- Pull Requests are reviewed before merging
- The preferred merge method is Squash and Merge
- Keeps `main` and `develop` history clean
- One commit per feature or fix
- Commit message may be edited by maintainers
The `main` and `develop` branches are protected and should never be pushed to directly.

---

## General CLI Commands

| Command | Description |
| --- | --- |
| `php artisan make:model ModelName -mcr` | Create a model, migration, and resource controller |
| `php artisan make:model ModelName -a` or `php artisan make:model ModelName --all` | Create a model, migration, factory, seeder, controller, resource, request(s) |
| `php artisan make:model ModelName` | Create a model |
| `php artisan make:controller ControllerName` | Create a controller |
| `php artisan make:controller ControllerName --resource` | Create a resource controller |
| `php artisan make:migration migration_name_table` | Create a migration |
| `php artisan make:seeder SeederName` | Create a seeder |
| `php artisan make:factory FactoryName` | Create a factory |
| `php artisan make:request RequestName` | Creates a form request for validation |
| `php artisan make:event EventName` | Creates an event class |
| `php artisan make:listener ListenerName` | Creates a listener class |
| `php artisan make:job JobName` | Creates a queued job |
| `php artisan make:rule RuleName` | Create a new rule |
| `php artisan make:test TestName` | Create a new test |
| `php artisan queue:work` | Starts the queue worker to process queued jobs (e.g. emails, notifications) |
| `php artisan make:trait TraitName` | Creates a new trait |

For further CLI commands, visit <a href="https://artisan.page/">here</a>

---

## Specific CLI Commands

| Command | Description |
| --- | --- |
| `php artisan make:service ServiceName` | Creates a new service class |
| `php artisan make:class NameRegistry` | Creates a custom class (e.g. registry/helper/service class such as a NameRegistry) |
| `php artisan insights` | Run insights package |

---

## Events and Listeners

The system uses Laravel Events & Listeners to handle asynchronous workflows and maintain separation of concerns.

Event listeners are registered in `AppServiceProvider::boot()` using `Event::listen()`.

### Registered Events

| Event | Listener | Description |
| --- | --- | --- |
| `Illuminate\Auth\Events\Registered` | `App\Listeners\SendWelcomeEmail` | Sends a welcome email to newly created users including their login credentials |

### Model Observers

The system uses observers to automatically handle role synchronization and data ownership:

| Model | Observer | Description |
| --- | --- | --- |
| `App\Models\User` | `App\Observers\UserObserver` | Automatically syncs Spatie roles based on boolean flags (`is_user`, `is_admin`, `is_super_admin`) when users are created or updated |

**UserObserver Behavior:**

- On user creation: Assigns appropriate role based on boolean flags
- On user update: Re-syncs roles if boolean flags change
- Sync order: `is_super_admin` -> `super-admin` role, `is_admin` -> `admin` role, `is_user` -> `user` role

---

## Roles and Permissions

This project uses the [Spatie Laravel Permission](https://spatie.be/docs/laravel-permission) package to manage roles and permissions in a flexible and scalable way.

It provides a robust Role-Based Access Control (RBAC) system suitable for complex ERP/CRM/HR/LMS environments with multiple departments and varying access levels.

### Key Concepts

- **Role**: A collection of permissions (e.g., `super-admin`, `sales-manager`, `hr-staff`)
- **Permission**: A specific action (e.g., `create leads`, `edit invoices`, `view reports`, `approve payments`)
- **User Assignment**: Users can have one or multiple roles
- **Role Permissions**: Roles can have multiple permissions
- **Direct Permissions**: Individual permissions can be assigned directly to users for exceptions

### Permission Structure

Permissions follow a consistent naming convention: `{action} {module}`

**Actions**: `view`, `create`, `edit`, `delete`, `export`

**Modules**:

- CRM: `companies`, `contacts`, `deals`, `leads`, `opportunities`, `quotes`, `invoices`, `payments`
- Projects: `projects`, `tasks`, `activities`, `documents`
- HR: `employees`, `departments`, `positions`, `attendance`, `payroll`, `leaves`, `performance`, `recruitment`
- LMS: `courses`, `lessons`, `assessments`, `certifications`, `learning_paths`
- IT: `tickets`, `assets`, `licenses`, `backups`, `servers`, `networks`, `security_logs`
- Finance: `budgets`, `expenses`, `purchase_orders`, `accounts`, `journals`, `tax_records`
- Operations: `inventory`, `warehouses`, `shipments`, `vendors`, `suppliers`
- Marketing: `campaigns`, `newsletters`, `events`, `social_media`, `content`

**Special Permissions**:

- `manage settings`, `manage roles`, `manage permissions`
- `view all data`, `manage own data only`
- `approve quotes`, `approve invoices`, `approve payments`, `approve leaves`, `approve expenses`
- `access reports`, `access analytics`, `export data`, `import data`
- `delete permanently`, `restore deleted`, `view audit logs`
- `impersonate users`, `manage integrations`, `manage api keys`

### Core System Roles

| Role | Description | Key Permissions |
| --- | --- | --- |
| `super-admin` | Full unrestricted system access | All permissions |
| `admin` | Broad access excluding dangerous operations | Most permissions except system-critical actions |
| `user` | Basic authenticated system user | View and manage own data, basic CRM operations |
| `viewer` | Read-only access | View-only permissions across modules |

### Department-Specific Roles

#### Sales & CRM

| Role | Description |
| --- | --- |
| `sales-manager` | Full CRM oversight, team management, approvals |
| `sales-rep` | Own data CRM access, lead/deal management |
| `customer-success-manager` | Customer relationship management, support oversight |

#### Finance & Accounting

| Role | Description |
| --- | --- |
| `cfo` | Financial oversight, budget approvals, strategic planning |
| `financial-controller` | Accounting operations, financial reporting |
| `accountant` | Invoice and payment processing |
| `accounts-payable-clerk` | Vendor payments, expense processing |
| `accounts-receivable-clerk` | Customer invoicing, payment tracking |
| `budget-analyst` | Budget planning and analysis |
| `tax-specialist` | Tax compliance and reporting |

#### Human Resources

| Role | Description |
| --- | --- |
| `hr-manager` | Full HR control, policy management |
| `hr-staff` | Employee records, attendance, leave management |
| `recruiter` | Recruitment and candidate management |
| `payroll-specialist` | Payroll processing and reporting |

#### IT Department

| Role | Description |
| --- | --- |
| `it-director` | IT strategy, team oversight, system access control |
| `system-administrator` | Server management, system configuration, deployments |
| `network-administrator` | Network infrastructure and security |
| `database-administrator` | Database management, backups, queries |
| `it-support-specialist` | User support, ticket management |
| `security-analyst` | Security monitoring, audit logs, compliance |
| `devops-engineer` | Deployments, CI/CD, infrastructure automation |

#### Operations & Procurement

| Role | Description |
| --- | --- |
| `operations-manager` | Operations oversight, inventory management |
| `procurement-manager` | Vendor management, purchase approvals |
| `procurement-specialist` | Purchase orders, requisitions |
| `inventory-manager` | Stock control, warehouse management |
| `warehouse-supervisor` | Warehouse operations, shipments |
| `warehouse-staff` | Inventory handling, stock updates |
| `logistics-coordinator` | Shipment coordination and tracking |

#### Marketing

| Role | Description |
| --- | --- |
| `marketing-director` | Marketing strategy, campaign oversight, budget approvals |
| `marketing-manager` | Campaign management, content coordination |
| `content-creator` | Content creation and editing |
| `social-media-manager` | Social media strategy and posting |
| `email-marketing-specialist` | Email campaigns and newsletters |
| `event-coordinator` | Event planning and management |

#### Learning & Development

| Role | Description |
| --- | --- |
| `training-manager` | LMS administration, course management |
| `instructor` | Course delivery, assessments |
| `employee` | Course access, learning participation |

#### Project Management

| Role | Description |
| --- | --- |
| `project-manager` | Project oversight, task assignment, resource allocation |
| `team-lead` | Team coordination, performance tracking |

#### Specialised Roles

| Role | Description |
| --- | --- |
| `business-analyst` | Data analysis, reporting across modules |
| `data-analyst` | Advanced analytics, data exports |
| `compliance-officer` | Regulatory compliance, audit support |
| `qa-manager` | Quality control and assurance |
| `executive-assistant` | Administrative support, calendar management |
| `intern` | Limited access for learning purposes |
| `contractor` | Project-specific access only |

### Data Access Control

The system implements two levels of data access:

- **`view all data`** - Users can see all records regardless of ownership (typically for managers)
- **`manage own data only`** - Users can only see records they created or are assigned to (default for staff)

This is implemented through the `HasDataOwnership` trait:

```php
use App\Concerns\HasDataOwnership;

class Deal extends Model
{
    use HasDataOwnership;

    protected static function booted()
    {
        if (auth()->check()) {
            static::addGlobalScope('accessible', function ($query) {
                $query->accessibleBy(auth()->user());
            });
        }
    }
}
```

### Usage Examples

#### Assign Role to User

```php
$user->assignRole('sales-manager');

// Multiple roles
$user->assignRole(['sales-rep', 'project-manager']);
```

#### Assign Permission to Role

```php
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

$role = Role::findByName('accountant');
$permission = Permission::findByName('edit invoices');

$role->givePermissionTo($permission);

// Multiple permissions
$role->givePermissionTo(['edit invoices', 'delete invoices']);
```

#### Assign Permission Directly to User

```php
// Give extra permissions beyond role
$user->givePermissionTo('approve budgets');

// Remove direct permission
$user->revokePermissionTo('approve budgets');
```

#### Checking Permissions

```php
// Check role
if ($user->hasRole('admin')) {
    // User is an admin
}

// Check specific permission
if ($user->can('edit invoices')) {
    // User can edit invoices
}

// Check any role
if ($user->hasAnyRole(['sales-manager', 'admin'])) {
    // User has at least one of these roles
}

// Check all roles
if ($user->hasAllRoles(['sales-rep', 'project-manager'])) {
    // User has both roles
}

// Check direct permission (not from role)
if ($user->hasDirectPermission('approve budgets')) {
    // User was directly assigned this permission
}
```

#### Controller Authorization

```php
class DealController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', Deal::class);
        
        // Only accessible deals returned automatically
        $deals = Deal::latest()->paginate(20);
        
        return inertia('Deals/Index', compact('deals'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', Deal::class);
        
        $deal = Deal::create([
            ...$request->validated(),
            'created_by' => auth()->id(),
        ]);
        
        return redirect()->route('deals.show', $deal);
    }
}
```

#### Blade/Vue Directives

**Blade:**

```blade
@role('admin')
    <button>Admin Panel</button>
@endrole

@can('edit invoices')
    <button>Edit Invoice</button>
@endcan

@hasanyrole('sales-manager|admin')
    <a href="/reports">View Reports</a>
@endhasanyrole
```

**Vue (Inertia):**

```vue
<template>
  <div>
    <button v-if="$page.props.auth.can['create deals']">
      Create Deal
    </button>
    
    <button v-if="$page.props.auth.can.is_admin">
      Admin Settings
    </button>
  </div>
</template>
```

#### Route Middleware

```php
// Role-based routes
Route::middleware(['role:admin'])->group(function () {
    Route::resource('users', UserController::class);
});

// Permission-based routes
Route::middleware(['permission:view reports'])->group(function () {
    Route::get('/reports/sales', [ReportController::class, 'sales']);
});

// Multiple roles
Route::middleware(['role:sales-manager|admin'])->group(function () {
    Route::resource('deals', DealController::class);
});
```

### CLI Commands

#### Sync User Roles

Synchronizes Spatie roles with user boolean flags:

```bash
php artisan app:sync-user-roles
```

This command:

- Reads all users' `is_super_admin`, `is_admin`, `is_user` flags
- Assigns corresponding Spatie roles (`super-admin`, `admin`, `user`)
- Useful after bulk user imports or flag changes

#### Clear Permission Cache

```bash
php artisan permission:cache-reset
```

Run this after modifying permissions or roles in the database.

### Seeding Roles and Permissions

Roles and permissions are seeded via:

```bash
php artisan db:seed --class=RolesAndPermissionsSeeder
```

This creates:

- 50+ predefined roles across all departments
- 300+ granular permissions following the `{action} {module}` pattern
- Role-permission assignments based on job functions

To assign roles to existing users:

```bash
php artisan db:seed --class=AssignRolesToUsersSeeder
```

### Best Practices

**Use roles as the foundation** - Assign standard permission sets via roles  
**Use direct permissions sparingly** - Only for exceptions or temporary access  
**Check permissions in policies** - Centralize authorization logic  
**Use scopes for data filtering** - Implement `HasDataOwnership` trait  
**Protect routes with middleware** - Layer security at the route level  
**Share permissions with frontend** - Make authorization decisions in both backend and UI  
**Document custom permissions** - Maintain clarity on what each permission controls  
**Regular permission audits** - Review and clean up unused permissions  
**Test permission boundaries** - Ensure users can't access unauthorized resources

### Architecture Notes

- **Hybrid Model**: Combines role-based and permission-based access control
- **Data Isolation**: Users with `manage own data only` see only their assigned records
- **Automatic Sync**: User roles automatically sync with boolean flags via observers
- **Policy-Based**: Complex authorization logic lives in dedicated Policy classes
- **Auditable**: Permission checks can be logged via audit logs for compliance
- **Scalable**: Supports 50+ roles and 300+ permissions without performance impact

---

## Sponsor The Project

If you find this project useful, consider sponsoring it to support future development and maintenance.<br>
<a href="https://www.buymeacoffee.com/mattyeend">☕ Buy Me a Coffee</a><br>
<a href="https://github.com/sponsors/MattYeend">💸 Personal GitHub Sponsor</a><br>
<a href="https://github.com/sponsors/MatthewYeend">🏢 Company Github Sponsor</a>