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
    2. [Architecture Overview](#architecture-overview)
    3. [Team Structure](#team-structure)
    4. [Permission Structure](#permission-structure)
    5. [RolesEnum Structure](#rolesenum-structure)
    6. [Core System Roles](#core-system-roles)
    7. [Department-Specific Roles](#department-specific-roles)
        1. [Sales and CRM](#sales-and-crm)
        2. [Finance and Accounting](#finance-and-accounting)
        3. [Human Resources](#human-resources)
        4. [IT Department](#it-department)
        5. [Operations & Procurement](#operations--procurement)
        6. [Marketing](#marketing)
        7. [Learning and Development](#learning-and-development)
        8. [Project Management](#project-management)
        9. [Specialized Roles](#specialized-roles)
    8. [Team-Based Permission Management](#team-based-permission-management)
        1. [User Model Features](#user-model-features)
            1. [HasTeam Trait](#hasteam-trait)
            2. [HasUserRoles Trait](#hasuserroles-trait)
            3. [HasUserScopes Trait](#hasuserscopes-trait)
        2. [Team-Scoped Role Assignment](#team-scoped-role-assignment)
        3. [Internal Implementation](#internal-implementation)
    9. [Data Access Control](#data-access-control)
    10. [Usage Examples](#usage-examples)
        1. [Complete User Setup with Team and Roles](#complete-user-setup-with-team-and-roles)
        2. [Checking Permissions](#checking-permissions)
        3. [Controller Authorization](#controller-authorization)
        4. [Blade/Vue Directives](#bladevue-directives)
        5. [Route Middleware](#route-middleware)
    11. [CLI Commands](#cli-commands)
        1. [Sync User Roles](#sync-user-roles)
        2. [Clear Permission Cache](#clear-permission-cache)
    12. [Seeding Roles and Permissions](#seeding-roles-and-permissions)
        1. [Seed Roles and Permissions](#seed-roles-and-permissions)
        2. [Seed Users](#seed-users)
        3. [Assign Roles to Users](#assign-roles-to-users)
    13. [Best Practices](#best-practices)
    14. [Architecture Notes](#architecture-notes)
    15. [Team-Scoped Permission Flow](#team-scoped-permission-flow)
9. [Sponsor The Project](#sponsor-the-project)
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

## Roles and Permissions

This project uses the [Spatie Laravel Permission](https://spatie.be/docs/laravel-permission) package with **team-based permissions** to manage roles and permissions in a flexible and scalable way.

It provides a robust Role-Based Access Control (RBAC) system suitable for complex ERP/CRM/HR/LMS environments with multiple departments and varying access levels.

### Key Concepts

- **Role**: A collection of permissions scoped to a team (e.g., `super-admin`, `sales-manager`, `hr-staff`)
- **Permission**: A specific action within a module (e.g., `create leads`, `edit invoices`, `view reports`, `approve payments`)
- **Team**: A departmental or organizational unit that scopes role and permission assignments
- **User Assignment**: Users belong to a team and can have one or multiple roles within that team context
- **Role Permissions**: Roles can have multiple permissions assigned
- **Direct Permissions**: Individual permissions can be assigned directly to users for exceptions
- **Team Isolation**: Permissions are scoped per team, enabling fine-grained multi-tenancy

### Architecture Overview

The system implements **team-scoped permissions** using Spatie's built-in teams feature:

```php
// config/permission.php
'teams' => true,
'team_foreign_key' => 'team_id',
```

**Key Components:**

1. **`RolesEnum`** - Centralized enum defining all system roles with labels and department categorization
2. **`Teams` Constants** - Defines team IDs and provides team name resolution
3. **`HasTeam` Trait** - Manages team context for users (switching teams, scoping queries)
4. **`HasUserRoles` Trait** - Provides specialized role management beyond base roles
5. **`HasUserScopes` Trait** - Query scopes for filtering users by role type
6. **User Model** - Combines all traits and implements team-based permission checking

### Team Structure

The system defines six core teams via the `Teams` constant class:

| Team ID | Team Name | Constant |
| --- | --- | --- |
| 1 | Head Office | `Teams::HEAD_OFFICE` |
| 2 | Sales Department | `Teams::SALES_DEPARTMENT` |
| 3 | IT Department | `Teams::IT_DEPARTMENT` |
| 4 | HR Department | `Teams::HR_DEPARTMENT` |
| 5 | Finance Department | `Teams::FINANCE_DEPARTMENT` |
| 6 | Marketing Department | `Teams::MARKETING_DEPARTMENT` |

**Usage:**

```php
use App\Constants\Teams;

// Get team name
$teamName = Teams::getName(2); // "Sales Department"

// Get all teams
$allTeams = Teams::all();
// [1 => 'Head Office', 2 => 'Sales Department', ...]
```

### Permission Structure

Permissions follow a consistent naming convention: `{action} {module}`

**Actions**: `view`, `create`, `edit`, `delete`, `export`

**Modules**:

- **CRM**: `companies`, `contacts`, `deals`, `leads`, `opportunities`, `quotes`, `invoices`, `payments`
- **Projects**: `projects`, `tasks`, `activities`, `documents`
- **HR**: `employees`, `departments`, `positions`, `attendance`, `payroll`, `leaves`, `performance`, `recruitment`
- **LMS**: `courses`, `lessons`, `assessments`, `certifications`, `learning_paths`
- **IT**: `tickets`, `assets`, `licenses`, `backups`, `servers`, `networks`, `security_logs`, `system_settings`
- **Finance**: `budgets`, `expenses`, `purchase_orders`, `accounts`, `journals`, `tax_records`, `fiscal_years`, `vendors`
- **Operations**: `inventory`, `warehouses`, `shipments`, `suppliers`, `returns`, `quality_control`
- **Procurement**: `requisitions`, `bids`, `contracts`
- **Marketing**: `campaigns`, `newsletters`, `events`, `social_media`, `content`

**Special Permissions**:

- **Administrative**: `manage settings`, `manage roles`, `manage permissions`, `impersonate users`
- **Data Access**: `view all data`, `manage own data only`
- **Approvals**: `approve quotes`, `approve invoices`, `approve payments`, `approve leaves`, `approve expenses`, `approve purchase_orders`, `approve budgets`, `approve requisitions`
- **Reporting**: `access reports`, `access analytics`, `export data`, `import data`
- **Data Management**: `delete permanently`, `restore deleted`, `view audit logs`
- **System Operations**: `manage integrations`, `manage api keys`, `manage system backups`, `access server console`, `manage database`, `view system health`, `manage email templates`, `manage workflows`, `manage automations`, `bulk operations`, `override validations`, `execute sql queries`, `view error logs`, `clear cache`, `run migrations`, `deploy updates`

### RolesEnum Structure

The `RolesEnum` provides a type-safe, centralized definition of all system roles:

```php
use App\Enums\RolesEnum;

// Get role label
$label = RolesEnum::SALES_MANAGER->label(); // "Sales Manager"

// Get role department
$dept = RolesEnum::ACCOUNTANT->department(); // "Finance"

// Check if role is base or specialized
$isBase = RolesEnum::ADMIN->isBase(); // true
$isSpecialized = RolesEnum::HR_MANAGER->isSpecialized(); // true

// Get all roles by department
$salesRoles = RolesEnum::byDepartment('Sales');
// [SALES_MANAGER, SALES_REP, CUSTOMER_SUCCESS_MANAGER]

// Get all departments
$departments = RolesEnum::departments();
// ['Base', 'Sales', 'Finance', 'HR', 'LMS', 'IT', ...]

// Get all specialized roles (non-base)
$specializedRoles = RolesEnum::specializedRoles();

// Get role from string value
$role = RolesEnum::fromValue('sales-manager');

// Get all role labels (for dropdowns)
$roleLabels = RolesEnum::labels();
// ['super-admin' => 'Super Administrator', 'admin' => 'Administrator', ...]
```

### Core System Roles

| Role | Enum | Description | Key Permissions |
| --- | --- | --- | --- |
| `super-admin` | `RolesEnum::SUPER_ADMIN` | Full unrestricted system access across all teams | All permissions |
| `admin` | `RolesEnum::ADMIN` | Broad access excluding dangerous operations | Most permissions except system-critical actions |
| `user` | `RolesEnum::USER` | Basic authenticated system user | View and manage own data, basic CRM operations |
| `viewer` | `RolesEnum::VIEWER` | Read-only access | View-only permissions across modules |

### Department-Specific Roles

#### Sales and CRM

| Role | Enum | Description |
| --- | --- | --- |
| `sales-manager` | `RolesEnum::SALES_MANAGER` | Full CRM oversight, team management, quote approvals |
| `sales-rep` | `RolesEnum::SALES_REP` | Own data CRM access, lead/deal management |
| `customer-success-manager` | `RolesEnum::CUSTOMER_SUCCESS_MANAGER` | Customer relationship management, support oversight |

#### Finance and Accounting

| Role | Enum | Description |
| --- | --- | --- |
| `cfo` | `RolesEnum::CFO` | Financial oversight, budget approvals, strategic planning |
| `financial-controller` | `RolesEnum::FINANCIAL_CONTROLLER` | Accounting operations, financial reporting |
| `accountant` | `RolesEnum::ACCOUNTANT` | Invoice and payment processing |
| `accounts-payable-clerk` | `RolesEnum::ACCOUNTS_PAYABLE_CLERK` | Vendor payments, expense processing |
| `accounts-receivable-clerk` | `RolesEnum::ACCOUNTS_RECEIVABLE_CLERK` | Customer invoicing, payment tracking |
| `budget-analyst` | `RolesEnum::BUDGET_ANALYST` | Budget planning and analysis |
| `tax-specialist` | `RolesEnum::TAX_SPECIALIST` | Tax compliance and reporting |

#### Human Resources

| Role | Enum | Description |
| --- | --- | --- |
| `hr-manager` | `RolesEnum::HR_MANAGER` | Full HR control, policy management, leave approvals |
| `hr-staff` | `RolesEnum::HR_STAFF` | Employee records, attendance, leave management |
| `employee` | `RolesEnum::EMPLOYEE` | Standard employee access to own HR data and LMS |
| `recruiter` | `RolesEnum::RECRUITER` | Recruitment and candidate management |
| `payroll-specialist` | `RolesEnum::PAYROLL_SPECIALIST` | Payroll processing and reporting |

#### IT Department

| Role | Enum | Description |
| --- | --- | --- |
| `it-director` | `RolesEnum::IT_DIRECTOR` | IT strategy, team oversight, system access control |
| `system-administrator` | `RolesEnum::SYSTEM_ADMINISTRATOR` | Server management, system configuration, deployments |
| `network-administrator` | `RolesEnum::NETWORK_ADMINISTRATOR` | Network infrastructure and security |
| `database-administrator` | `RolesEnum::DATABASE_ADMINISTRATOR` | Database management, backups, queries |
| `it-support-specialist` | `RolesEnum::IT_SUPPORT_SPECIALIST` | User support, ticket management |
| `security-analyst` | `RolesEnum::SECURITY_ANALYST` | Security monitoring, audit logs, compliance |
| `devops-engineer` | `RolesEnum::DEVOPS_ENGINEER` | Deployments, CI/CD, infrastructure automation |

#### Operations & Procurement

| Role | Enum | Description |
| --- | --- | --- |
| `operations-manager` | `RolesEnum::OPERATIONS_MANAGER` | Operations oversight, inventory management |
| `procurement-manager` | `RolesEnum::PROCUREMENT_MANAGER` | Vendor management, purchase approvals |
| `procurement-specialist` | `RolesEnum::PROCUREMENT_SPECIALIST` | Purchase orders, requisitions |
| `inventory-manager` | `RolesEnum::INVENTORY_MANAGER` | Stock control, warehouse management |
| `warehouse-supervisor` | `RolesEnum::WAREHOUSE_SUPERVISOR` | Warehouse operations, shipments |
| `warehouse-staff` | `RolesEnum::WAREHOUSE_STAFF` | Inventory handling, stock updates |
| `logistics-coordinator` | `RolesEnum::LOGISTICS_COORDINATOR` | Shipment coordination and tracking |

#### Marketing

| Role | Enum | Description |
| --- | --- | --- |
| `marketing-director` | `RolesEnum::MARKETING_DIRECTOR` | Marketing strategy, campaign oversight, budget approvals |
| `marketing-manager` | `RolesEnum::MARKETING_MANAGER` | Campaign management, content coordination |
| `content-creator` | `RolesEnum::CONTENT_CREATOR` | Content creation and editing |
| `social-media-manager` | `RolesEnum::SOCIAL_MEDIA_MANAGER` | Social media strategy and posting |
| `email-marketing-specialist` | `RolesEnum::EMAIL_MARKETING_SPECIALIST` | Email campaigns and newsletters |
| `event-coordinator` | `RolesEnum::EVENT_COORDINATOR` | Event planning and management |

#### Learning and Development

| Role | Enum | Description |
| --- | --- | --- |
| `training-manager` | `RolesEnum::TRAINING_MANAGER` | LMS administration, course management |
| `instructor` | `RolesEnum::INSTRUCTOR` | Course delivery, assessments |

#### Project Management

| Role | Enum | Description |
| --- | --- | --- |
| `project-manager` | `RolesEnum::PROJECT_MANAGER` | Project oversight, task assignment, resource allocation |
| `team-lead` | `RolesEnum::TEAM_LEAD` | Team coordination, performance tracking |
| `support-agent` | `RolesEnum::SUPPORT_AGENT` | Customer support, ticket management |
| `executive-assistant` | `RolesEnum::EXECUTIVE_ASSISTANT` | Administrative support, calendar management |

#### Specialized Roles

| Role | Enum | Description |
| --- | --- | --- |
| `business-analyst` | `RolesEnum::BUSINESS_ANALYST` | Data analysis, reporting across modules |
| `data-analyst` | `RolesEnum::DATA_ANALYST` | Advanced analytics, data exports |
| `compliance-officer` | `RolesEnum::COMPLIANCE_OFFICER` | Regulatory compliance, audit support |
| `qa-manager` | `RolesEnum::QA_MANAGER` | Quality control and assurance |
| `intern` | `RolesEnum::INTERN` | Limited access for learning purposes |
| `contractor` | `RolesEnum::CONTRACTOR` | Project-specific access only |

### Team-Based Permission Management

#### User Model Features

The `User` model provides three custom traits for team and role management:

##### HasTeam Trait

```php
// Check if user has a team
if ($user->hasTeam()) {
    $teamId = $user->getTeamId();
}

// Set team context
$user->withTeam(Teams::SALES_DEPARTMENT);

// Switch teams (clears permission cache)
$user->switchTeam(Teams::IT_DEPARTMENT);

// Query scopes
$salesTeam = User::inTeam(Teams::SALES_DEPARTMENT)->get();
$noTeam = User::withoutAssignedTeam()->get();

// Get team name via accessor
echo $user->teamName; // "Sales Department"
```

##### HasUserRoles Trait

```php
// Assign specialized roles
$user->assignSpecialisedRole(RolesEnum::SALES_MANAGER->value);
$user->assignSpecialisedRole([
    RolesEnum::ACCOUNTANT->value,
    RolesEnum::BUDGET_ANALYST->value
]);

// Remove specialized roles
$user->removeSpecialisedRole(RolesEnum::SALES_REP->value);

// Get only specialized roles (excludes super-admin, admin, user)
$specializedRoles = $user->specialisedRoles; // Collection

// Check if user has any specialized roles
if ($user->hasSpecialisedRoles()) {
    // User has roles beyond base roles
}
```

##### HasUserScopes Trait

```php
// Query scopes for filtering users
$users = User::users()->get();           // Only users with is_user = true
$admins = User::admins()->get();         // Only users with is_admin = true
$superAdmins = User::superAdmins()->get(); // Only users with is_super_admin = true
$realUsers = User::real()->get();        // Only users with is_real = true

// Combine scopes
$realAdmins = User::real()->admins()->inTeam(Teams::HEAD_OFFICE)->get();
```

#### Team-Scoped Role Assignment

```php
use App\Constants\Teams;
use App\Enums\RolesEnum;

// Assign role within user's current team
$user = User::find(1);
$user->assignRoleInTeam(RolesEnum::SALES_MANAGER->value);

// Assign role in a specific team
$user->assignRoleInTeam(RolesEnum::ACCOUNTANT->value, Teams::FINANCE_DEPARTMENT);

// Check permission within team context
if ($user->hasPermissionInTeam('approve budgets')) {
    // User can approve budgets in their team
}

// Check role within team context
if ($user->hasRoleInTeam(RolesEnum::HR_MANAGER->value)) {
    // User is an HR manager in their team
}
```

#### Internal Implementation

The `User` model implements team context management internally:

```php
// User.php
protected function executeInTeamContext(callable $callback, ?int $teamId): mixed
{
    if (!$teamId) {
        return $callback();
    }

    setPermissionsTeamId($teamId);
    $result = $callback();
    setPermissionsTeamId(null);

    return $result;
}
```

This ensures all permission checks respect team boundaries automatically.

### Data Access Control

The system implements two levels of data access:

- **`view all data`** - Users can see all records in their team regardless of ownership (typically for managers)
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

Users with `view all data` permission see all records in their team. Users with only `manage own data only` see records where `created_by` or `assigned_to` matches their user ID.

### Usage Examples

#### Complete User Setup with Team and Roles

```php
use App\Constants\Teams;
use App\Enums\RolesEnum;
use App\Models\User;

// Create user in Sales Department
$user = User::create([
    'name' => 'John Doe',
    'email' => 'john@example.com',
    'password' => bcrypt('password'),
    'team_id' => Teams::SALES_DEPARTMENT,
    'is_user' => true,
    'is_admin' => false,
    'is_super_admin' => false,
]);

// Assign base role in team context
$user->assignRoleInTeam(RolesEnum::USER->value);

// Assign specialized role
$user->assignSpecialisedRole(RolesEnum::SALES_MANAGER->value);

// User now has permissions scoped to Sales Department
```

#### Checking Permissions

```php
// Check base role type
if ($user->isUser()) {
    // User has is_user flag set
}

if ($user->isAdmin()) {
    // User has is_admin flag set
}

if ($user->isSuperAdmin()) {
    // User has is_super_admin flag set
}

// Check Spatie role
if ($user->hasRole(RolesEnum::SALES_MANAGER->value)) {
    // User is a sales manager
}

// Check specific permission
if ($user->can('approve quotes')) {
    // User can approve quotes
}

// Check any role
if ($user->hasAnyRole([
    RolesEnum::SALES_MANAGER->value,
    RolesEnum::ADMIN->value
])) {
    // User has at least one of these roles
}

// Check direct permission (not from role)
if ($user->hasDirectPermission('approve budgets')) {
    // User was directly assigned this permission
}

// Get user's primary role
$primaryRole = $user->primaryRole; // First assigned role name

// Get all roles as string
$rolesList = $user->rolesList; // "user, sales-manager"

// Get role display name (based on boolean flags)
$roleDisplay = $user->roleDisplay; // "User", "Admin", or "Super Admin"
```

#### Controller Authorization

```php
class DealController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', Deal::class);
        
        // Only accessible deals returned automatically (team-scoped)
        $deals = Deal::latest()->paginate(20);
        
        return inertia('Deals/Index', compact('deals'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', Deal::class);
        
        $deal = Deal::create([
            ...$request->validated(),
            'team_id' => auth()->user()->team_id,
            'created_by' => auth()->id(),
        ]);
        
        return redirect()->route('deals.show', $deal);
    }
    
    public function approve(Deal $deal)
    {
        // Check team-scoped permission
        if (!auth()->user()->hasPermissionInTeam('approve deals')) {
            abort(403, 'You do not have permission to approve deals in your team.');
        }
        
        $deal->update(['status' => 'approved']);
        
        return back()->with('success', 'Deal approved successfully.');
    }
}
```

#### Blade/Vue Directives

**Blade:**

```blade
@role(RolesEnum::ADMIN->value)
    <button>Admin Panel</button>
@endrole

@can('edit invoices')
    <button>Edit Invoice</button>
@endcan

@hasanyrole(RolesEnum::SALES_MANAGER->value . '|' . RolesEnum::ADMIN->value)
    <a href="/reports">View Reports</a>
@endhasanyrole

{{-- Display user info --}}
<p>Team: {{ auth()->user()->teamName }}</p>
<p>Role: {{ auth()->user()->roleDisplay }}</p>
<p>Initials: {{ auth()->user()->initials }}</p>

{{-- Check specialized roles --}}
@if(auth()->user()->hasSpecialisedRoles())
    <p>Specialized Roles: {{ auth()->user()->specialisedRoles->pluck('name')->implode(', ') }}</p>
@endif
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
    
    <div v-if="$page.props.auth.user.team_id">
      Team: {{ getTeamName($page.props.auth.user.team_id) }}
    </div>
    
    <p>{{ $page.props.auth.user.initials }}</p>
  </div>
</template>

<script setup>
import { Teams } from '@/Constants/Teams';

const getTeamName = (teamId) => {
  const teamMap = {
    1: 'Head Office',
    2: 'Sales Department',
    3: 'IT Department',
    4: 'HR Department',
    5: 'Finance Department',
    6: 'Marketing Department',
  };
  return teamMap[teamId] || `Team ${teamId}`;
};
</script>
```

#### Route Middleware

```php
use App\Enums\RolesEnum;

// Role-based routes
Route::middleware(['role:' . RolesEnum::ADMIN->value])->group(function () {
    Route::resource('users', UserController::class);
});

// Permission-based routes
Route::middleware(['permission:view reports'])->group(function () {
    Route::get('/reports/sales', [ReportController::class, 'sales']);
});

// Multiple roles
Route::middleware([
    'role:' . RolesEnum::SALES_MANAGER->value . '|' . RolesEnum::ADMIN->value
])->group(function () {
    Route::resource('deals', DealController::class);
});

// Team-specific routes
Route::middleware(['auth'])->group(function () {
    Route::get('/team/dashboard', function () {
        $teamId = auth()->user()->team_id;
        $teamName = Teams::getName($teamId);
        
        return inertia('Team/Dashboard', compact('teamName'));
    });
});
```

### CLI Commands

#### Sync User Roles

Synchronizes Spatie roles with user boolean flags across all teams:

```bash
php artisan app:sync-user-roles
```

This command:

- Reads all users' `is_super_admin`, `is_admin`, `is_user` flags
- Sets appropriate team context via `setPermissionsTeamId()`
- Assigns corresponding Spatie roles (`super-admin`, `admin`, `user`) within team scope
- Useful after bulk user imports, flag changes, or team migrations

#### Clear Permission Cache

```bash
php artisan permission:cache-reset
```

Run this after modifying permissions, roles, or team assignments in the database.

### Seeding Roles and Permissions

#### Seed Roles and Permissions

```bash
php artisan db:seed --class=RolesAndPermissionsSeeder
```

This creates:

- **50+ predefined roles** across all departments (defined in `RolesEnum`)
- **300+ granular permissions** following the `{action} {module}` pattern
- **Role-permission assignments** based on job functions
- **Team-scoped role setup** for multi-tenancy

#### Seed Users

```bash
php artisan db:seed --class=UserSeeder
```

Creates sample users across different teams:

- Super Admin (no team - global access)
- Admins in Head Office and Sales
- Department-specific users (Sales, HR, IT, Finance, Marketing)
- Test users for development

#### Assign Roles to Users

```bash
php artisan db:seed --class=AssignRolesToUsersSeeder
```

This:

- Assigns base roles based on `is_super_admin`, `is_admin`, `is_user` flags
- Assigns specialized roles based on email/name patterns
- Sets correct team context using `setPermissionsTeamId()`
- Ensures all role assignments are team-scoped

**Full seeding workflow:**

```bash
php artisan migrate:fresh
php artisan db:seed --class=RolesAndPermissionsSeeder
php artisan db:seed --class=UserSeeder
php artisan db:seed --class=AssignRolesToUsersSeeder
```

### Best Practices

**Use RolesEnum for type safety** - Always reference roles via `RolesEnum::ROLE_NAME->value`  
**Use Teams constants** - Reference teams via `Teams::TEAM_NAME` for consistency  
**Leverage team scoping** - All role/permission operations automatically respect team boundaries  
**Use roles as the foundation** - Assign standard permission sets via roles, not individual permissions  
**Use direct permissions sparingly** - Only for exceptions or temporary access  
**Check permissions in policies** - Centralize authorization logic in Policy classes  
**Use model scopes for data filtering** - Implement team-scoped queries via global scopes  
**Protect routes with middleware** - Layer security at the route level with role/permission middleware  
**Share permissions with frontend** - Make authorization decisions in both backend and UI  
**Document custom permissions** - Maintain clarity on what each permission controls  
**Regular permission audits** - Review and clean up unused permissions  
**Test permission boundaries** - Ensure users can't access unauthorized resources across team boundaries  
**Use User model helper methods** - `hasPermissionInTeam()`, `hasRoleInTeam()`, `assignRoleInTeam()`  
**Sync roles after bulk operations** - Run `app:sync-user-roles` after imports or mass updates

### Architecture Notes

- **Team-Based Multi-Tenancy**: Spatie's teams feature provides native data isolation per team
- **Hybrid Model**: Combines boolean flags (`is_user`, `is_admin`, `is_super_admin`) with Spatie roles for flexibility
- **Automatic Sync**: User roles automatically sync with boolean flags via `UserObserver`
- **Type-Safe Roles**: `RolesEnum` provides compile-time safety and IDE autocomplete for all roles
- **Team Constants**: `Teams` class centralizes team ID management and name resolution
- **Data Isolation**: Users with `manage own data only` see only their assigned records within their team
- **Permission Caching**: Spatie caches permissions per team for performance
- **Policy-Based**: Complex authorization logic lives in dedicated Policy classes
- **Auditable**: Permission checks can be logged via audit logs for compliance
- **Scalable**: Supports 50+ roles and 300+ permissions without performance impact
- **Trait Composition**: User model composes `HasTeam`, `HasUserRoles`, `HasUserScopes` for clean separation
- **Custom Accessors**: Computed properties like `teamName`, `initials`, `roleDisplay`, `primaryRole`, `rolesList`

### Team-Scoped Permission Flow

User Login
↓
User.team_id = 2 (Sales Department)
↓
assignRoleInTeam('sales-manager')
↓
setPermissionsTeamId(2)  ← Set global team context
↓
assignRole('sales-manager')  ← Spatie role assignment
↓
setPermissionsTeamId(null)  ← Clear global context
↓
Role 'sales-manager' + Permissions now scoped to Team 2
↓
Permission checks automatically filtered by team_id = 2

**Key Insight**: The `setPermissionsTeamId()` function is Spatie's mechanism for scoping all role/permission operations to a specific team. All our custom methods (`assignRoleInTeam`, `hasPermissionInTeam`, `hasRoleInTeam`) wrap Spatie methods with this team context.

---

## Sponsor The Project

If you find this project useful, consider sponsoring it to support future development and maintenance.<br>
<a href="https://www.buymeacoffee.com/mattyeend">☕ Buy Me a Coffee</a><br>
<a href="https://github.com/sponsors/MattYeend">💸 Personal GitHub Sponsor</a><br>
<a href="https://github.com/sponsors/MatthewYeend">🏢 Company Github Sponsor</a>