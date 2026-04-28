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
7. [Events And Listeners](#events-and-listeners)
8. [Sponsor The Project](#sponsor-the-project)
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
git clone https://github.com/MattYeend/CRM.git
cd CRM
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

For further CLI commands, visit <a href="https://artisan.page/">here</a>

---

## Specific CLI Commands

| Command | Description |
| --- | --- |
| `php artisan make:service ServiceName` | Creates a new service class |
| `php artisan make:class NameRegistry` |  Creates a custom class (e.g. registry/helper/service class such as a NameRegistry) |
| `php artisan permission:clear` | Clear permissions if changed |
| `php artisan insights` | Run insights package |

---

## Events And Listeners

The system uses Laravel Events & Listeners to handle asynchronous workflows.

Event listeners are registered in `AppServiceProvider::boot()` using `Event::listen()`.

| Event | Listener | Description |
| --- | --- | --- |
| `Illuminate\Auth\Events\Registered` | `App\Listeners\SendWelcomeEmail` | Sends a welcome email to newly created users including their login credentials |

---

## Sponsor The Project

If you find this project useful, consider sponsoring it to support future development and maintenance.<br>
<a href="https://www.buymeacoffee.com/mattyeend">☕ Buy Me a Coffee</a><br>
<a href="https://github.com/sponsors/MattYeend">💸 Personal GitHub Sponsor</a><br>
<a href="https://github.com/sponsors/MatthewYeend">🏢 Company Github Sponsor</a>