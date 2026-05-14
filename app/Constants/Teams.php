<?php

namespace App\Constants;

/**
 * Team identifier constants for the CRM system.
 *
 * Provides constant definitions for organizational teams/departments
 * used throughout the application, particularly for Spatie Permission
 * team-scoped permissions and authorisation.
 */
class Teams
{
    public const HEAD_OFFICE = 1;
    public const SALES_DEPARTMENT = 2;
    public const IT_DEPARTMENT = 3;
    public const HR_DEPARTMENT = 4;
    public const FINANCE_DEPARTMENT = 5;
    public const MARKETING_DEPARTMENT = 6;

    /**
     * Get the human-readable name for a given team ID.
     *
     * @param  int $teamId
     *
     * @return string
     */
    public static function getName(int $teamId): string
    {
        return match ($teamId) {
            self::HEAD_OFFICE => 'Head Office',
            self::SALES_DEPARTMENT => 'Sales Department',
            self::IT_DEPARTMENT => 'IT Department',
            self::HR_DEPARTMENT => 'HR Department',
            self::FINANCE_DEPARTMENT => 'Finance Department',
            self::MARKETING_DEPARTMENT => 'Marketing Department',
            default => 'Team ' . $teamId,
        };
    }

    /**
     * Get all teams as an associative array.
     *
     * @return array<int,string>
     */
    public static function all(): array
    {
        return [
            self::HEAD_OFFICE => 'Head Office',
            self::SALES_DEPARTMENT => 'Sales Department',
            self::IT_DEPARTMENT => 'IT Department',
            self::HR_DEPARTMENT => 'HR Department',
            self::FINANCE_DEPARTMENT => 'Finance Department',
            self::MARKETING_DEPARTMENT => 'Marketing Department',
        ];
    }
}
