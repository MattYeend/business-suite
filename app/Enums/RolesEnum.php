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
    case EMPLOYEE = 'employee';
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
        return $this->labelForBaseRoles()
            ?? $this->labelForSalesRoles()
            ?? $this->labelForFinanceRoles()
            ?? $this->labelForHrRoles()
            ?? $this->labelForLmsRoles()
            ?? $this->labelForItRoles()
            ?? $this->labelForOperationsRoles()
            ?? $this->labelForProcurementRoles()
            ?? $this->labelForMarketingRoles()
            ?? $this->labelForAnalysisRoles()
            ?? $this->labelForOtherRoles()
            ?? 'Unknown';
    }

    /**
     * Check if this is a base role.
     *
     * @return bool
     */
    public function isBase(): bool
    {
        return in_array($this, self::baseRoles());
    }

    /**
     * Check if this is a specialized role.
     *
     * @return bool
     */
    public function isSpecialized(): bool
    {
        return ! $this->isBase();
    }

    /**
     * Get the department category for this role.
     *
     * @return string
     */
    public function department(): string
    {
        return $this->departmentForBaseRoles()
            ?? $this->departmentForSalesRoles()
            ?? $this->departmentForFinanceRoles()
            ?? $this->departmentForHrRoles()
            ?? $this->departmentForLmsRoles()
            ?? $this->departmentForItRoles()
            ?? $this->departmentForOperationsRoles()
            ?? $this->departmentForProcurementRoles()
            ?? $this->departmentForMarketingRoles()
            ?? $this->departmentForAnalysisRoles()
            ?? $this->departmentForOtherRoles()
            ?? 'Unknown';
    }

    /**
     * Get all base roles.
     *
     * @return array<self>
     */
    public static function baseRoles(): array
    {
        return [
            self::SUPER_ADMIN,
            self::ADMIN,
            self::USER,
        ];
    }

    /**
     * Get all specialized roles (non-base).
     *
     * @return array<self>
     */
    public static function specializedRoles(): array
    {
        return array_values(
            array_filter(
                self::cases(),
                fn (self $role) => $role->isSpecialized()
            )
        );
    }

    /**
     * Get roles by department.
     *
     * @param  string $department
     *
     * @return array<self>
     */
    public static function byDepartment(string $department): array
    {
        return array_values(
            array_filter(
                self::cases(),
                fn (self $role) => $role->department() === $department
            )
        );
    }

    /**
     * Get all departments.
     *
     * @return array<string>
     */
    public static function departments(): array
    {
        return array_values(
            array_unique(
                array_map(
                    fn (self $role) => $role->department(),
                    self::cases()
                )
            )
        );
    }

    /**
     * Get role from string value.
     *
     * @param  string $value
     *
     * @return self|null
     */
    public static function fromValue(string $value): ?self
    {
        return self::tryFrom($value);
    }

    /**
     * Get all role values as strings.
     *
     * @return array<string>
     */
    public static function values(): array
    {
        return array_map(
            fn (self $role) => $role->value,
            self::cases()
        );
    }

    /**
     * Get all role labels.
     *
     * @return array<string, string>
     */
    public static function labels(): array
    {
        $labels = [];

        foreach (self::cases() as $role) {
            $labels[$role->value] = $role->label();
        }

        return $labels;
    }

    /**
     * Get label for base roles.
     *
     * @return string|null
     */
    private function labelForBaseRoles(): ?string
    {
        return match ($this) {
            self::SUPER_ADMIN => 'Super Administrator',
            self::ADMIN => 'Administrator',
            self::USER => 'User',
            default => null,
        };
    }

    /**
     * Get label for sales roles.
     *
     * @return string|null
     */
    private function labelForSalesRoles(): ?string
    {
        return match ($this) {
            self::SALES_MANAGER => 'Sales Manager',
            self::SALES_REP => 'Sales Representative',
            self::CUSTOMER_SUCCESS_MANAGER => 'Customer Success Manager',
            default => null,
        };
    }

    /**
     * Get label for finance roles.
     *
     * @return string|null
     */
    private function labelForFinanceRoles(): ?string
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
     * Get label for HR roles.
     *
     * @return string|null
     */
    private function labelForHrRoles(): ?string
    {
        return match ($this) {
            self::HR_MANAGER => 'HR Manager',
            self::HR_STAFF => 'HR Staff',
            self::EMPLOYEE => 'Employee',
            self::RECRUITER => 'Recruiter',
            self::PAYROLL_SPECIALIST => 'Payroll Specialist',
            default => null,
        };
    }

    /**
     * Get label for LMS roles.
     *
     * @return string|null
     */
    private function labelForLmsRoles(): ?string
    {
        return match ($this) {
            self::TRAINING_MANAGER => 'Training Manager',
            self::INSTRUCTOR => 'Instructor',
            default => null,
        };
    }

    /**
     * Get label for IT roles.
     *
     * @return string|null
     */
    private function labelForItRoles(): ?string
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
     * Get label for operations roles.
     *
     * @return string|null
     */
    private function labelForOperationsRoles(): ?string
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
     * Get label for procurement roles.
     *
     * @return string|null
     */
    private function labelForProcurementRoles(): ?string
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
     * Get label for marketing roles.
     *
     * @return string|null
     */
    private function labelForMarketingRoles(): ?string
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
     * Get label for analysis roles.
     *
     * @return string|null
     */
    private function labelForAnalysisRoles(): ?string
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
     * Get label for other roles.
     *
     * @return string|null
     */
    private function labelForOtherRoles(): ?string
    {
        return match ($this) {
            self::INTERN => 'Intern',
            self::CONTRACTOR => 'Contractor',
            default => null,
        };
    }

    /**
     * Get department for base roles.
     *
     * @return string|null
     */
    private function departmentForBaseRoles(): ?string
    {
        return match ($this) {
            self::SUPER_ADMIN, self::ADMIN, self::USER => 'Base',
            default => null,
        };
    }

    /**
     * Get department for sales roles.
     *
     * @return string|null
     */
    private function departmentForSalesRoles(): ?string
    {
        return match ($this) {
            self::SALES_MANAGER, self::SALES_REP,
            self::CUSTOMER_SUCCESS_MANAGER => 'Sales',
            default => null,
        };
    }

    /**
     * Get department for finance roles.
     *
     * @return string|null
     */
    private function departmentForFinanceRoles(): ?string
    {
        return match ($this) {
            self::ACCOUNTANT, self::CFO, self::FINANCIAL_CONTROLLER,
            self::ACCOUNTS_PAYABLE_CLERK, self::ACCOUNTS_RECEIVABLE_CLERK,
            self::BUDGET_ANALYST, self::TAX_SPECIALIST => 'Finance',
            default => null,
        };
    }

    /**
     * Get department for HR roles.
     *
     * @return string|null
     */
    private function departmentForHrRoles(): ?string
    {
        return match ($this) {
            self::HR_MANAGER, self::HR_STAFF, self::EMPLOYEE,
            self::RECRUITER, self::PAYROLL_SPECIALIST => 'HR',
            default => null,
        };
    }

    /**
     * Get department for LMS roles.
     *
     * @return string|null
     */
    private function departmentForLmsRoles(): ?string
    {
        return match ($this) {
            self::TRAINING_MANAGER, self::INSTRUCTOR => 'LMS',
            default => null,
        };
    }

    /**
     * Get department for IT roles.
     *
     * @return string|null
     */
    private function departmentForItRoles(): ?string
    {
        return match ($this) {
            self::IT_DIRECTOR, self::SYSTEM_ADMINISTRATOR,
            self::NETWORK_ADMINISTRATOR, self::DATABASE_ADMINISTRATOR,
            self::IT_SUPPORT_SPECIALIST, self::SECURITY_ANALYST,
            self::DEVOPS_ENGINEER => 'IT',
            default => null,
        };
    }

    /**
     * Get department for operations roles.
     *
     * @return string|null
     */
    private function departmentForOperationsRoles(): ?string
    {
        return match ($this) {
            self::PROJECT_MANAGER, self::SUPPORT_AGENT, self::VIEWER,
            self::TEAM_LEAD, self::OPERATIONS_MANAGER,
            self::EXECUTIVE_ASSISTANT => 'Operations',
            default => null,
        };
    }

    /**
     * Get department for procurement roles.
     *
     * @return string|null
     */
    private function departmentForProcurementRoles(): ?string
    {
        return match ($this) {
            self::PROCUREMENT_MANAGER, self::PROCUREMENT_SPECIALIST,
            self::INVENTORY_MANAGER, self::WAREHOUSE_SUPERVISOR,
            self::WAREHOUSE_STAFF, self::LOGISTICS_COORDINATOR => 'Procurement',
            default => null,
        };
    }

    /**
     * Get department for marketing roles.
     *
     * @return string|null
     */
    private function departmentForMarketingRoles(): ?string
    {
        return match ($this) {
            self::MARKETING_DIRECTOR, self::MARKETING_MANAGER,
            self::CONTENT_CREATOR, self::SOCIAL_MEDIA_MANAGER,
            self::EMAIL_MARKETING_SPECIALIST,
            self::EVENT_COORDINATOR => 'Marketing',
            default => null,
        };
    }

    /**
     * Get department for analysis roles.
     *
     * @return string|null
     */
    private function departmentForAnalysisRoles(): ?string
    {
        return match ($this) {
            self::BUSINESS_ANALYST, self::DATA_ANALYST,
            self::COMPLIANCE_OFFICER, self::QA_MANAGER => 'Analysis',
            default => null,
        };
    }

    /**
     * Get department for other roles.
     *
     * @return string|null
     */
    private function departmentForOtherRoles(): ?string
    {
        return match ($this) {
            self::INTERN, self::CONTRACTOR => 'Other',
            default => null,
        };
    }
}
