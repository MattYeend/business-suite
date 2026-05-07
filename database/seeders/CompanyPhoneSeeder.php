<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CompanyPhoneSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $companies = Company::all();
        $users = User::all();
        
        if ($companies->isEmpty() || $users->isEmpty()) {
            $this->command->warn('No companies or users found. Please run CompanySeeder and UserSeeder first.');
            return;
        }

        $phoneTypes = ['main', 'fax', 'toll_free', 'mobile'];
        
        // UK area codes and realistic phone number patterns
        $ukAreaCodes = [
            '020' => 8,      // London
            '0121' => 7,     // Birmingham
            '0161' => 7,     // Manchester
            '0113' => 7,     // Leeds
            '0117' => 7,     // Bristol
            '0131' => 7,     // Edinburgh
            '01223' => 6,    // Cambridge
            '01865' => 6,    // Oxford
            '01603' => 6,    // Norwich
            '01273' => 6,    // Brighton
        ];

        $companyPhones = [];
        $now = now();

        foreach ($companies as $company) {
            $createdBy = $users->random()->id;
            $numPhones = rand(1, 3); // Each company gets 1-3 phone numbers
            
            // Select a random UK area code (weighted towards London for larger companies)
            $areaCode = $company->employee_count > 50000 ? '020' : array_rand($ukAreaCodes);
            $localLength = $ukAreaCodes[$areaCode];

            for ($i = 0; $i < $numPhones; $i++) {
                $type = $phoneTypes[$i % count($phoneTypes)];
                $isPrimary = ($i === 0); // First phone is primary
                
                // Generate realistic phone number based on type
                $number = $this->generatePhoneNumber($type, $areaCode, $localLength);
                
                $companyPhones[] = [
                    'company_id' => $company->id,
                    'type' => $type,
                    'number' => $number,
                    'is_primary' => $isPrimary,
                    'is_real' => rand(0, 9) < 8, // 80% real, 20% not real
                    'meta' => json_encode([
                        'extension' => $type === 'main' ? rand(100, 999) : null,
                        'department' => $type === 'main' ? ['Sales', 'Support', 'Reception', 'Enquiries'][rand(0, 3)] : null,
                        'verified_at' => rand(0, 1) ? now()->subDays(rand(1, 90))->toDateTimeString() : null,
                        'business_hours' => '09:00-17:30 Mon-Fri',
                    ]),
                    'created_by' => $createdBy,
                    'updated_by' => rand(0, 3) ? $createdBy : $users->random()->id,
                    'deleted_by' => null,
                    'restored_by' => null,
                    'restored_at' => null,
                    'created_at' => $now,
                    'updated_at' => $now,
                    'deleted_at' => null,
                ];
            }
        }

        // Add some soft-deleted records (about 5%)
        $numToDelete = (int) (count($companyPhones) * 0.05);
        for ($i = 0; $i < $numToDelete; $i++) {
            $index = array_rand($companyPhones);
            $deleter = $users->random()->id;
            $companyPhones[$index]['deleted_by'] = $deleter;
            $companyPhones[$index]['deleted_at'] = now()->subDays(rand(1, 30));
        }

        DB::table('company_phones')->insert($companyPhones);
        
        $this->command->info('Created ' . count($companyPhones) . ' company phone numbers.');
    }

    /**
     * Generate a realistic phone number based on type
     */
    private function generatePhoneNumber(string $type, string $areaCode, int $localLength): string
    {
        switch ($type) {
            case 'toll_free':
                // UK toll-free numbers start with 0800 or 0808
                $prefix = ['0800', '0808'][rand(0, 1)];
                return $prefix . ' ' . rand(100, 999) . ' ' . rand(1000, 9999);
                
            case 'mobile':
                // UK mobile numbers start with 07
                return '07' . rand(100, 999) . ' ' . rand(100000, 999999);
                
            case 'fax':
                // Fax uses same format as main but we'll mark it differently
                $local = str_pad((string) rand(0, (10 ** $localLength) - 1), $localLength, '0', STR_PAD_LEFT);
                return $areaCode . ' ' . $this->formatLocal($local);
                
            case 'main':
            default:
                // Standard UK geographic number
                $local = str_pad((string) rand(0, (10 ** $localLength) - 1), $localLength, '0', STR_PAD_LEFT);
                return $areaCode . ' ' . $this->formatLocal($local);
        }
    }

    /**
     * Format local number part for readability
     */
    private function formatLocal(string $local): string
    {
        $length = strlen($local);
        
        if ($length === 8) {
            return substr($local, 0, 4) . ' ' . substr($local, 4);
        } elseif ($length === 7) {
            return substr($local, 0, 3) . ' ' . substr($local, 3);
        } elseif ($length === 6) {
            return substr($local, 0, 3) . ' ' . substr($local, 3);
        }
        
        return $local;
    }
}
