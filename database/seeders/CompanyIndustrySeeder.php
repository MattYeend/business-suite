<?php

namespace Database\Seeders;

use App\Models\CompanyIndustry;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CompanyIndustrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $industries = [
            'Accounting',
            'Advertising',
            'Aerospace',
            'Agriculture',
            'Architecture',
            'Automotive',
            'Banking',
            'Biotechnology',
            'Construction',
            'Consulting',
            'Defence',
            'Education',
            'Energy',
            'Engineering',
            'Entertainment',
            'Environmental Services',
            'Fashion',
            'Finance',
            'Food & Beverage',
            'Government',
            'Healthcare',
            'Hospitality',
            'Human Resources',
            'Insurance',
            'IT Services',
            'Legal',
            'Logistics',
            'Manufacturing',
            'Marketing',
            'Media',
            'Mining',
            'Non-Profit',
            'Pharmaceuticals',
            'Property',
            'Public Relations',
            'Real Estate',
            'Recruitment',
            'Retail',
            'Sports',
            'Telecommunications',
            'Technology',
            'Tourism',
            'Transportation',
            'Utilities',
            'Wholesale',
            'Other',
        ];

        foreach ($industries as $name) {
            CompanyIndustry::firstOrCreate(
                ['slug' => Str::slug($name)],
                [
                    'name' => $name,
                    'meta' => ['slug' => Str::slug($name), 'name' => $name],
                ],
            );
        }
    }
}
