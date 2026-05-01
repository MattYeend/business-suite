<?php

namespace App\Constants;

class Teams
{
    public const HEAD_OFFICE = 1;
    public const SALES_DEPARTMENT = 2;
    public const IT_DEPARTMENT = 3;
    public const HR_DEPARTMENT = 4;
    public const FINANCE_DEPARTMENT = 5;
    public const MARKETING_DEPARTMENT = 6;

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
