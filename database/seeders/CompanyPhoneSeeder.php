<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\CompanyPhone;
use App\Models\User;
use Illuminate\Database\Seeder;

class CompanyPhoneSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (CompanyPhone::exists()) {
            $this->command->info('Company Phones already seeded, skipping...');
            return;
        }

        $companies = Company::all();
        $users = User::all();

        if ($companies->isEmpty() || $users->isEmpty()) {
            $this->command->warn('No companies or users found. Please run CompanySeeder and UserSeeder first.');
            return;
        }

        $phoneTypes = ['main', 'fax', 'toll_free', 'mobile'];

        // UK area codes and realistic phone number patterns
        $ukAreaCodes = [
            '020'   => 8, // London
            '0121'  => 7, // Birmingham
            '0161'  => 7, // Manchester
            '0113'  => 7, // Leeds
            '0117'  => 7, // Bristol
            '0131'  => 7, // Edinburgh
            '01223' => 6, // Cambridge
            '01865' => 6, // Oxford
            '01603' => 6, // Norwich
            '01273' => 6, // Brighton
        ];

        $phones = [];

        foreach ($companies as $company) {

            // Each company gets 1–3 phone numbers
            $numPhones = rand(1, 3);

            $areaCode = $company->employee_count > 50000
                ? '020'
                : array_rand($ukAreaCodes);

            $localLength = $ukAreaCodes[$areaCode];

            for ($i = 0; $i < $numPhones; $i++) {

                $type = $phoneTypes[$i % count($phoneTypes)];
                $isPrimary = $i === 0;

                $phones[] = [
                    'company_id' => $company->id,

                    'number' => $this->generatePhoneNumber(
                        $type,
                        $areaCode,
                        $localLength
                    ),

                    'type' => $type,

                    'is_primary' => $isPrimary,

                    'is_real' => rand(0, 9) < 8,

                    'meta' => [
                        'extension' => $type === 'main'
                            ? rand(100, 999)
                            : null,

                        'department' => $type === 'main'
                            ? ['Sales', 'Support', 'Reception', 'Enquiries'][rand(0, 3)]
                            : null,

                        'verified_at' => rand(0, 1)
                            ? now()->subDays(rand(1, 90))->toDateTimeString()
                            : null,

                        'business_hours' => '09:00-17:30 Mon-Fri',
                    ],

                    'created_by' => User::inRandomOrder()->first()?->id,

                    'updated_by' => rand(0, 3)
                        ? User::inRandomOrder()->first()?->id
                        : $users->random()->id,
                ];
            }
        }

        $created = 0;

        foreach ($phones as $phoneData) {
            $phone = CompanyPhone::firstOrCreate(
                [
                    'company_id' => $phoneData['company_id'],
                    'number' => $phoneData['number'],
                ],
                $phoneData
            );

            if ($phone->wasRecentlyCreated) {
                $created++;
            }
        }

        $this->command->info("Created {$created} company phone numbers.");
    }

    /**
     * Generate a realistic phone number based on type.
     */
    private function generatePhoneNumber(
        string $type,
        string $areaCode,
        int $localLength
    ): string {
        switch ($type) {

            case 'toll_free':
                // UK toll-free numbers
                $prefix = ['0800', '0808'][rand(0, 1)];

                return $prefix . ' '
                    . rand(100, 999)
                    . ' '
                    . rand(1000, 9999);

            case 'mobile':
                // UK mobile numbers
                return '07'
                    . rand(100, 999)
                    . ' '
                    . rand(100000, 999999);

            case 'fax':
            case 'main':
            default:
                // Standard UK geographic number
                $local = str_pad(
                    (string) rand(0, (10 ** $localLength) - 1),
                    $localLength,
                    '0',
                    STR_PAD_LEFT
                );

                return $areaCode . ' ' . $this->formatLocal($local);
        }
    }

    /**
     * Format local number part for readability.
     */
    private function formatLocal(string $local): string
    {
        $length = strlen($local);

        if ($length === 8) {
            return substr($local, 0, 4)
                . ' '
                . substr($local, 4);
        }

        if ($length === 7) {
            return substr($local, 0, 3)
                . ' '
                . substr($local, 3);
        }

        if ($length === 6) {
            return substr($local, 0, 3)
                . ' '
                . substr($local, 3);
        }

        return $local;
    }
}
