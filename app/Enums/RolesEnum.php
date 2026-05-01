<?php

namespace App\Enums;

enum RolesEnum: string
{
    // Base Roles
    case SUPER_ADMIN = 'super-admin';
    case ADMIN = 'admin';
    case USER = 'user';

    // Sales Roles
    case SALES_MANAGER = 'sales-manager';
    case SALES_REP = 'sales-rep';
    case CUSTOMER_SUCCESS_MANAGER = 'customer-success-manager';

    // Finance Roles
    case ACCOUNTANT = 'accountant';
    case CFO = 'cfo';
    case FINANCIAL_CONTROLLER = 'financial-controller';
    case ACCOUNTS_PAYABLE_CLERK = 'accounts-payable-clerk';
    case ACCOUNTS_RECEIVABLE_CLERK = 'accounts-receivable-clerk';
    case BUDGET_ANALYST = 'budget-analyst';
    case TAX_SPECIALIST = 'tax-specialist';

    // HR Roles
    case HR_MANAGER = 'hr-manager';
    case HR_STAFF = 'hr-staff';
    case EMPLOYER = 'employer';
    case RECRUITER = 'recruiter';
    case PAYROLL_SPECIALIST = 'payroll-specialist';

    // LMS Roles
    case TRAINING_MANAGER = 'training-manager';
    case INSTRUCTOR = 'instructor';

    // IT Roles
    case IT_DIRECTOR = 'it-director';
    case SYSTEM_ADMINISTRATOR = 'system-administrator';
    case NETWORK_ADMINISTRATOR = 'network-administrator';
    case DATABASE_ADMINISTRATOR = 'database-administrator';
    case IT_SUPPORT_SPECIALIST = 'it-support-specialist';
    case SECURITY_ANALYST = 'security-analyst';
    case DEVOPS_ENGINEER = 'devops-engineer';

    // Project & Operations Roles
    case PROJECT_MANAGER = 'project-manager';
    case SUPPORT_AGENT = 'support-agent';
    case VIEWER = 'viewer';
    case TEAM_LEAD = 'team-lead';
    case OPERATIONS_MANAGER = 'operations-manager';
    case EXECUTIVE_ASSISTANT = 'executive-assistant';

    // Procurement & Warehouse Roles
    case PROCUREMENT_MANAGER = 'procurement-manager';
    case PROCUREMENT_SPECIALIST = 'procurement-specialist';
    case INVENTORY_MANAGER = 'inventory-manager';
    case WAREHOUSE_SUPERVISOR = 'warehouse-supervisor';
    case WAREHOUSE_STAFF = 'warehouse-staff';
    case LOGISTICS_COORDINATOR = 'logistics-coordinator';

    // Marketing Roles
    case MARKETING_DIRECTOR = 'marketing-director';
    case MARKETING_MANAGER = 'marketing-manager';
    case CONTENT_CREATOR = 'content-creator';
    case SOCIAL_MEDIA_MANAGER = 'social-media-manager';
    case EMAIL_MARKETING_SPECIALIST = 'email-marketing-specialist';
    case EVENT_COORDINATOR = 'event-coordinator';

    // Analysis & Compliance Roles
    case BUSINESS_ANALYST = 'business-analyst';
    case DATA_ANALYST = 'data-analyst';
    case COMPLIANCE_OFFICER = 'compliance-officer';
    case QA_MANAGER = 'qa-manager';

    // Other Roles
    case INTERN = 'intern';
    case CONTRACTOR = 'contractor';

    /**
     * Get a human-readable label for the role.
     *
     * @return string
     */
    public function label(): string
    {
        return $this->labelBase()
            ?? $this->labelSales()
            ?? $this->labelFinance()
            ?? $this->labelHr()
            ?? $this->labelLms()
            ?? $this->labelIt()
            ?? $this->labelOperations()
            ?? $this->labelProcurement()
            ?? $this->labelMarketing()
            ?? $this->labelAnalysis()
            ?? $this->labelOther()
            ?? 'Unknown';
    }

    /**
     * Get a human-readable label for the base role.
     *
     * @return string|null
     */
    private function labelBase(): ?string
    {
        return match ($this) {
            self::SUPER_ADMIN => 'Super Administrator',
            self::ADMIN => 'Administrator',
            self::USER => 'User',
            default => null,
        };
    }

    /**
     * Get a human-readable label for sales roles.
     *
     * @return string|null
     */
    private function labelSales(): ?string
    {
        return match ($this) {
            self::SALES_MANAGER => 'Sales Manager',
            self::SALES_REP => 'Sales Representative',
            self::CUSTOMER_SUCCESS_MANAGER => 'Customer Success Manager',
            default => null,
        };
    }

    /**
     * Get a human-readable label for finance roles.
     *
     * @return string|null
     */
    private function labelFinance(): ?string
    {
        return match ($this) {
            self::ACCOUNTANT => 'Accountant',
            self::CFO => 'Chief Financial Officer',
            self::FINANCIAL_CONTROLLER => 'Financial Controller',
            self::ACCOUNTS_PAYABLE_CLERK => 'Accounts Payable Clerk',
            self::ACCOUNTS_RECEIVABLE_CLERK => 'Accounts Receivable Clerk',
            self::BUDGET_ANALYST => 'Budget Analyst',
            self::TAX_SPECIALIST => 'Tax Specialist',
            default => null,
        };
    }

    /**
     * Get a human-readable label for HR roles.
     *
     * @return string|null
     */
    private function labelHr(): ?string
    {
        return match ($this) {
            self::HR_MANAGER => 'HR Manager',
            self::HR_STAFF => 'HR Staff',
            self::EMPLOYER => 'Employee',
            self::RECRUITER => 'Recruiter',
            self::PAYROLL_SPECIALIST => 'Payroll Specialist',
            default => null,
        };
    }

    /**
     * Get a human-readable label for LMS roles.
     *
     * @return string|null
     */
    private function labelLms(): ?string
    {
        return match ($this) {
            self::TRAINING_MANAGER => 'Training Manager',
            self::INSTRUCTOR => 'Instructor',
            default => null,
        };
    }

    /**
     * Get a human-readable label for IT roles.
     *
     * @return string|null
     */
    private function labelIt(): ?string
    {
        return match ($this) {
            self::IT_DIRECTOR => 'IT Director',
            self::SYSTEM_ADMINISTRATOR => 'System Administrator',
            self::NETWORK_ADMINISTRATOR => 'Network Administrator',
            self::DATABASE_ADMINISTRATOR => 'Database Administrator',
            self::IT_SUPPORT_SPECIALIST => 'IT Support Specialist',
            self::SECURITY_ANALYST => 'Security Analyst',
            self::DEVOPS_ENGINEER => 'DevOps Engineer',
            default => null,
        };
    }

    /**
     * Get a human-readable label for project and operations roles.
     *
     * @return string|null
     */
    private function labelOperations(): ?string
    {
        return match ($this) {
            self::PROJECT_MANAGER => 'Project Manager',
            self::SUPPORT_AGENT => 'Support Agent',
            self::VIEWER => 'Viewer',
            self::TEAM_LEAD => 'Team Lead',
            self::OPERATIONS_MANAGER => 'Operations Manager',
            self::EXECUTIVE_ASSISTANT => 'Executive Assistant',
            default => null,
        };
    }

    /**
     * Get a human-readable label for procurement and warehouse roles.
     *
     * @return string|null
     */
    private function labelProcurement(): ?string
    {
        return match ($this) {
            self::PROCUREMENT_MANAGER => 'Procurement Manager',
            self::PROCUREMENT_SPECIALIST => 'Procurement Specialist',
            self::INVENTORY_MANAGER => 'Inventory Manager',
            self::WAREHOUSE_SUPERVISOR => 'Warehouse Supervisor',
            self::WAREHOUSE_STAFF => 'Warehouse Staff',
            self::LOGISTICS_COORDINATOR => 'Logistics Coordinator',
            default => null,
        };
    }

    /**
     * Get a human-readable label for marketing roles.
     *
     * @return string|null
     */
    private function labelMarketing(): ?string
    {
        return match ($this) {
            self::MARKETING_DIRECTOR => 'Marketing Director',
            self::MARKETING_MANAGER => 'Marketing Manager',
            self::CONTENT_CREATOR => 'Content Creator',
            self::SOCIAL_MEDIA_MANAGER => 'Social Media Manager',
            self::EMAIL_MARKETING_SPECIALIST => 'Email Marketing Specialist',
            self::EVENT_COORDINATOR => 'Event Coordinator',
            default => null,
        };
    }

    /**
     * Get a human-readable label for analysis and compliance roles.
     *
     * @return string|null
     */
    private function labelAnalysis(): ?string
    {
        return match ($this) {
            self::BUSINESS_ANALYST => 'Business Analyst',
            self::DATA_ANALYST => 'Data Analyst',
            self::COMPLIANCE_OFFICER => 'Compliance Officer',
            self::QA_MANAGER => 'Quality Assurance Manager',
            default => null,
        };
    }

    /**
     * Get a human-readable label for other roles.
     *
     * @return string|null
     */
    private function labelOther(): ?string
    {
        return match ($this) {
            self::INTERN => 'Intern',
            self::CONTRACTOR => 'Contractor',
            default => null,
        };
    }
}
