<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\CompanyContact;
use App\Models\User;
use Illuminate\Database\Seeder;

class CompanyContactSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::first();
        
        if (!$user) {
            $this->command->error('No users found. Please run UserSeeder first.');
            return;
        }

        // Get company IDs
        $appleId = Company::where('name', 'Apple Inc.')->first()?->id;
        $microsoftId = Company::where('name', 'Microsoft Corporation')->first()?->id;
        $googleId = Company::where('name', 'Google LLC')->first()?->id;
        $metaId = Company::where('name', 'Meta Platforms Inc.')->first()?->id;
        $amazonId = Company::where('name', 'Amazon.com Inc.')->first()?->id;
        $netflixId = Company::where('name', 'Netflix Inc.')->first()?->id;
        $ibmId = Company::where('name', 'IBM Corporation')->first()?->id;
        $intelId = Company::where('name', 'Intel Corporation')->first()?->id;
        $teslaId = Company::where('name', 'Tesla Inc.')->first()?->id;
        $toyotaId = Company::where('name', 'Toyota Motor Corporation')->first()?->id;
        $fordId = Company::where('name', 'Ford Motor Company')->first()?->id;
        $gmId = Company::where('name', 'General Motors')->first()?->id;
        $walmartId = Company::where('name', 'Walmart Inc.')->first()?->id;
        $targetId = Company::where('name', 'Target Corporation')->first()?->id;
        $cokeId = Company::where('name', 'Coca-Cola Company')->first()?->id;
        $pepsiId = Company::where('name', 'PepsiCo Inc.')->first()?->id;
        $starbucksId = Company::where('name', 'Starbucks Corporation')->first()?->id;
        $mcdonaldsId = Company::where('name', 'McDonald\'s Corporation')->first()?->id;
        $nikeId = Company::where('name', 'Nike Inc.')->first()?->id;
        $adidasId = Company::where('name', 'Adidas AG')->first()?->id;
        $jpmorganId = Company::where('name', 'JPMorgan Chase & Co.')->first()?->id;
        $boaId = Company::where('name', 'Bank of America')->first()?->id;
        $pfizerId = Company::where('name', 'Pfizer Inc.')->first()?->id;
        $jnjId = Company::where('name', 'Johnson & Johnson')->first()?->id;
        $verizonId = Company::where('name', 'Verizon Communications')->first()?->id;
        $attId = Company::where('name', 'AT&T Inc.')->first()?->id;
        $exxonId = Company::where('name', 'ExxonMobil Corporation')->first()?->id;
        $chevronId = Company::where('name', 'Chevron Corporation')->first()?->id;
        $boeingId = Company::where('name', 'Boeing Company')->first()?->id;
        $airbusId = Company::where('name', 'Airbus SE')->first()?->id;
        $deloitteId = Company::where('name', 'Deloitte')->first()?->id;
        $mckinseyId = Company::where('name', 'McKinsey & Company')->first()?->id;

        $contacts = [
            // Apple Inc.
            [
                'company_id' => $appleId,
                'first_name' => 'Tim',
                'last_name' => 'Cook',
                'email' => 'tim.cook@apple.com',
                'phone' => '+1 (408) 996-1010',
                'mobile' => '+1 (408) 555-0101',
                'job_title' => 'CEO',
                'is_primary' => true,
                'is_real' => true,
                'created_by' => $user->id,
            ],
            [
                'company_id' => $appleId,
                'first_name' => 'Jeff',
                'last_name' => 'Williams',
                'email' => 'jeff.williams@apple.com',
                'phone' => '+1 (408) 996-1011',
                'mobile' => '+1 (408) 555-0102',
                'job_title' => 'Chief Operating Officer',
                'is_primary' => false,
                'is_real' => true,
                'created_by' => $user->id,
            ],
            [
                'company_id' => $appleId,
                'first_name' => 'Luca',
                'last_name' => 'Maestri',
                'email' => 'luca.maestri@apple.com',
                'phone' => '+1 (408) 996-1012',
                'mobile' => null,
                'job_title' => 'Chief Financial Officer',
                'is_primary' => false,
                'is_real' => true,
                'created_by' => $user->id,
            ],

            // Microsoft Corporation
            [
                'company_id' => $microsoftId,
                'first_name' => 'Satya',
                'last_name' => 'Nadella',
                'email' => 'satya.nadella@microsoft.com',
                'phone' => '+1 (425) 882-8080',
                'mobile' => '+1 (425) 555-0201',
                'job_title' => 'CEO',
                'is_primary' => true,
                'is_real' => true,
                'created_by' => $user->id,
            ],
            [
                'company_id' => $microsoftId,
                'first_name' => 'Amy',
                'last_name' => 'Hood',
                'email' => 'amy.hood@microsoft.com',
                'phone' => '+1 (425) 882-8081',
                'mobile' => '+1 (425) 555-0202',
                'job_title' => 'Chief Financial Officer',
                'is_primary' => false,
                'is_real' => true,
                'created_by' => $user->id,
            ],

            // Google LLC
            [
                'company_id' => $googleId,
                'first_name' => 'Sundar',
                'last_name' => 'Pichai',
                'email' => 'sundar.pichai@google.com',
                'phone' => '+1 (650) 253-0000',
                'mobile' => '+1 (650) 555-0301',
                'job_title' => 'CEO',
                'is_primary' => true,
                'is_real' => true,
                'created_by' => $user->id,
            ],
            [
                'company_id' => $googleId,
                'first_name' => 'Ruth',
                'last_name' => 'Porat',
                'email' => 'ruth.porat@google.com',
                'phone' => '+1 (650) 253-0001',
                'mobile' => null,
                'job_title' => 'Chief Financial Officer',
                'is_primary' => false,
                'is_real' => true,
                'created_by' => $user->id,
            ],

            // Meta Platforms Inc.
            [
                'company_id' => $metaId,
                'first_name' => 'Mark',
                'last_name' => 'Zuckerberg',
                'email' => 'mark.zuckerberg@meta.com',
                'phone' => '+1 (650) 543-4800',
                'mobile' => '+1 (650) 555-0401',
                'job_title' => 'CEO',
                'is_primary' => true,
                'is_real' => true,
                'created_by' => $user->id,
            ],
            [
                'company_id' => $metaId,
                'first_name' => 'Susan',
                'last_name' => 'Li',
                'email' => 'susan.li@meta.com',
                'phone' => '+1 (650) 543-4801',
                'mobile' => '+1 (650) 555-0402',
                'job_title' => 'Chief Financial Officer',
                'is_primary' => false,
                'is_real' => true,
                'created_by' => $user->id,
            ],

            // Amazon.com Inc.
            [
                'company_id' => $amazonId,
                'first_name' => 'Andy',
                'last_name' => 'Jassy',
                'email' => 'andy.jassy@amazon.com',
                'phone' => '+1 (206) 266-1000',
                'mobile' => '+1 (206) 555-0501',
                'job_title' => 'CEO',
                'is_primary' => true,
                'is_real' => true,
                'created_by' => $user->id,
            ],
            [
                'company_id' => $amazonId,
                'first_name' => 'Brian',
                'last_name' => 'Olsavsky',
                'email' => 'brian.olsavsky@amazon.com',
                'phone' => '+1 (206) 266-1001',
                'mobile' => null,
                'job_title' => 'Chief Financial Officer',
                'is_primary' => false,
                'is_real' => true,
                'created_by' => $user->id,
            ],

            // Netflix Inc.
            [
                'company_id' => $netflixId,
                'first_name' => 'Ted',
                'last_name' => 'Sarandos',
                'email' => 'ted.sarandos@netflix.com',
                'phone' => '+1 (408) 540-3700',
                'mobile' => '+1 (408) 555-0601',
                'job_title' => 'Co-CEO',
                'is_primary' => true,
                'is_real' => true,
                'created_by' => $user->id,
            ],
            [
                'company_id' => $netflixId,
                'first_name' => 'Spencer',
                'last_name' => 'Neumann',
                'email' => 'spencer.neumann@netflix.com',
                'phone' => '+1 (408) 540-3701',
                'mobile' => '+1 (408) 555-0602',
                'job_title' => 'Chief Financial Officer',
                'is_primary' => false,
                'is_real' => true,
                'created_by' => $user->id,
            ],

            // IBM Corporation
            [
                'company_id' => $ibmId,
                'first_name' => 'Arvind',
                'last_name' => 'Krishna',
                'email' => 'arvind.krishna@ibm.com',
                'phone' => '+1 (914) 499-1900',
                'mobile' => '+1 (914) 555-0701',
                'job_title' => 'CEO',
                'is_primary' => true,
                'is_real' => true,
                'created_by' => $user->id,
            ],
            [
                'company_id' => $ibmId,
                'first_name' => 'James',
                'last_name' => 'Kavanaugh',
                'email' => 'james.kavanaugh@ibm.com',
                'phone' => '+1 (914) 499-1901',
                'mobile' => null,
                'job_title' => 'Chief Financial Officer',
                'is_primary' => false,
                'is_real' => true,
                'created_by' => $user->id,
            ],

            // Intel Corporation
            [
                'company_id' => $intelId,
                'first_name' => 'Pat',
                'last_name' => 'Gelsinger',
                'email' => 'pat.gelsinger@intel.com',
                'phone' => '+1 (408) 765-8080',
                'mobile' => '+1 (408) 555-0801',
                'job_title' => 'CEO',
                'is_primary' => true,
                'is_real' => true,
                'created_by' => $user->id,
            ],
            [
                'company_id' => $intelId,
                'first_name' => 'David',
                'last_name' => 'Zinsner',
                'email' => 'david.zinsner@intel.com',
                'phone' => '+1 (408) 765-8081',
                'mobile' => '+1 (408) 555-0802',
                'job_title' => 'Chief Financial Officer',
                'is_primary' => false,
                'is_real' => true,
                'created_by' => $user->id,
            ],

            // Tesla Inc.
            [
                'company_id' => $teslaId,
                'first_name' => 'Elon',
                'last_name' => 'Musk',
                'email' => 'elon.musk@tesla.com',
                'phone' => '+1 (512) 516-8177',
                'mobile' => '+1 (512) 555-0901',
                'job_title' => 'CEO',
                'is_primary' => true,
                'is_real' => true,
                'created_by' => $user->id,
            ],
            [
                'company_id' => $teslaId,
                'first_name' => 'Vaibhav',
                'last_name' => 'Taneja',
                'email' => 'vaibhav.taneja@tesla.com',
                'phone' => '+1 (512) 516-8178',
                'mobile' => null,
                'job_title' => 'Chief Financial Officer',
                'is_primary' => false,
                'is_real' => true,
                'created_by' => $user->id,
            ],

            // Toyota Motor Corporation
            [
                'company_id' => $toyotaId,
                'first_name' => 'Koji',
                'last_name' => 'Sato',
                'email' => 'koji.sato@toyota.com',
                'phone' => '+81 3-3817-7111',
                'mobile' => '+81 90-1234-5601',
                'job_title' => 'President',
                'is_primary' => true,
                'is_real' => true,
                'created_by' => $user->id,
            ],
            [
                'company_id' => $toyotaId,
                'first_name' => 'Yoichi',
                'last_name' => 'Miyazaki',
                'email' => 'yoichi.miyazaki@toyota.com',
                'phone' => '+81 3-3817-7112',
                'mobile' => '+81 90-1234-5602',
                'job_title' => 'Chief Financial Officer',
                'is_primary' => false,
                'is_real' => true,
                'created_by' => $user->id,
            ],

            // Ford Motor Company
            [
                'company_id' => $fordId,
                'first_name' => 'Jim',
                'last_name' => 'Farley',
                'email' => 'jim.farley@ford.com',
                'phone' => '+1 (313) 322-3000',
                'mobile' => '+1 (313) 555-1001',
                'job_title' => 'CEO',
                'is_primary' => true,
                'is_real' => true,
                'created_by' => $user->id,
            ],
            [
                'company_id' => $fordId,
                'first_name' => 'John',
                'last_name' => 'Lawler',
                'email' => 'john.lawler@ford.com',
                'phone' => '+1 (313) 322-3001',
                'mobile' => null,
                'job_title' => 'Chief Financial Officer',
                'is_primary' => false,
                'is_real' => true,
                'created_by' => $user->id,
            ],

            // General Motors
            [
                'company_id' => $gmId,
                'first_name' => 'Mary',
                'last_name' => 'Barra',
                'email' => 'mary.barra@gm.com',
                'phone' => '+1 (313) 667-1500',
                'mobile' => '+1 (313) 555-1101',
                'job_title' => 'CEO',
                'is_primary' => true,
                'is_real' => true,
                'created_by' => $user->id,
            ],
            [
                'company_id' => $gmId,
                'first_name' => 'Paul',
                'last_name' => 'Jacobson',
                'email' => 'paul.jacobson@gm.com',
                'phone' => '+1 (313) 667-1501',
                'mobile' => '+1 (313) 555-1102',
                'job_title' => 'Chief Financial Officer',
                'is_primary' => false,
                'is_real' => true,
                'created_by' => $user->id,
            ],

            // Walmart Inc.
            [
                'company_id' => $walmartId,
                'first_name' => 'Doug',
                'last_name' => 'McMillon',
                'email' => 'doug.mcmillon@walmart.com',
                'phone' => '+1 (479) 273-4000',
                'mobile' => '+1 (479) 555-1201',
                'job_title' => 'CEO',
                'is_primary' => true,
                'is_real' => true,
                'created_by' => $user->id,
            ],
            [
                'company_id' => $walmartId,
                'first_name' => 'John',
                'last_name' => 'David Rainey',
                'email' => 'john.rainey@walmart.com',
                'phone' => '+1 (479) 273-4001',
                'mobile' => null,
                'job_title' => 'Chief Financial Officer',
                'is_primary' => false,
                'is_real' => true,
                'created_by' => $user->id,
            ],

            // Target Corporation
            [
                'company_id' => $targetId,
                'first_name' => 'Brian',
                'last_name' => 'Cornell',
                'email' => 'brian.cornell@target.com',
                'phone' => '+1 (612) 304-6073',
                'mobile' => '+1 (612) 555-1301',
                'job_title' => 'CEO',
                'is_primary' => true,
                'is_real' => true,
                'created_by' => $user->id,
            ],
            [
                'company_id' => $targetId,
                'first_name' => 'Michael',
                'last_name' => 'Fiddelke',
                'email' => 'michael.fiddelke@target.com',
                'phone' => '+1 (612) 304-6074',
                'mobile' => '+1 (612) 555-1302',
                'job_title' => 'Chief Financial Officer',
                'is_primary' => false,
                'is_real' => true,
                'created_by' => $user->id,
            ],

            // Coca-Cola Company
            [
                'company_id' => $cokeId,
                'first_name' => 'James',
                'last_name' => 'Quincey',
                'email' => 'james.quincey@coca-cola.com',
                'phone' => '+1 (404) 676-2121',
                'mobile' => '+1 (404) 555-1401',
                'job_title' => 'CEO',
                'is_primary' => true,
                'is_real' => true,
                'created_by' => $user->id,
            ],
            [
                'company_id' => $cokeId,
                'first_name' => 'John',
                'last_name' => 'Murphy',
                'email' => 'john.murphy@coca-cola.com',
                'phone' => '+1 (404) 676-2122',
                'mobile' => null,
                'job_title' => 'Chief Financial Officer',
                'is_primary' => false,
                'is_real' => true,
                'created_by' => $user->id,
            ],

            // PepsiCo Inc.
            [
                'company_id' => $pepsiId,
                'first_name' => 'Ramon',
                'last_name' => 'Laguarta',
                'email' => 'ramon.laguarta@pepsico.com',
                'phone' => '+1 (914) 253-2000',
                'mobile' => '+1 (914) 555-1501',
                'job_title' => 'CEO',
                'is_primary' => true,
                'is_real' => true,
                'created_by' => $user->id,
            ],
            [
                'company_id' => $pepsiId,
                'first_name' => 'Hugh',
                'last_name' => 'Johnston',
                'email' => 'hugh.johnston@pepsico.com',
                'phone' => '+1 (914) 253-2001',
                'mobile' => '+1 (914) 555-1502',
                'job_title' => 'Chief Financial Officer',
                'is_primary' => false,
                'is_real' => true,
                'created_by' => $user->id,
            ],

            // Starbucks Corporation
            [
                'company_id' => $starbucksId,
                'first_name' => 'Laxman',
                'last_name' => 'Narasimhan',
                'email' => 'laxman.narasimhan@starbucks.com',
                'phone' => '+1 (206) 447-1575',
                'mobile' => '+1 (206) 555-1601',
                'job_title' => 'CEO',
                'is_primary' => true,
                'is_real' => true,
                'created_by' => $user->id,
            ],
            [
                'company_id' => $starbucksId,
                'first_name' => 'Rachel',
                'last_name' => 'Ruggeri',
                'email' => 'rachel.ruggeri@starbucks.com',
                'phone' => '+1 (206) 447-1576',
                'mobile' => null,
                'job_title' => 'Chief Financial Officer',
                'is_primary' => false,
                'is_real' => true,
                'created_by' => $user->id,
            ],

            // McDonald's Corporation
            [
                'company_id' => $mcdonaldsId,
                'first_name' => 'Chris',
                'last_name' => 'Kempczinski',
                'email' => 'chris.kempczinski@mcdonalds.com',
                'phone' => '+1 (630) 623-3000',
                'mobile' => '+1 (630) 555-1701',
                'job_title' => 'CEO',
                'is_primary' => true,
                'is_real' => true,
                'created_by' => $user->id,
            ],
            [
                'company_id' => $mcdonaldsId,
                'first_name' => 'Ian',
                'last_name' => 'Borden',
                'email' => 'ian.borden@mcdonalds.com',
                'phone' => '+1 (630) 623-3001',
                'mobile' => '+1 (630) 555-1702',
                'job_title' => 'Chief Financial Officer',
                'is_primary' => false,
                'is_real' => true,
                'created_by' => $user->id,
            ],

            // Nike Inc.
            [
                'company_id' => $nikeId,
                'first_name' => 'John',
                'last_name' => 'Donahoe',
                'email' => 'john.donahoe@nike.com',
                'phone' => '+1 (503) 671-6453',
                'mobile' => '+1 (503) 555-1801',
                'job_title' => 'CEO',
                'is_primary' => true,
                'is_real' => true,
                'created_by' => $user->id,
            ],
            [
                'company_id' => $nikeId,
                'first_name' => 'Matthew',
                'last_name' => 'Friend',
                'email' => 'matthew.friend@nike.com',
                'phone' => '+1 (503) 671-6454',
                'mobile' => null,
                'job_title' => 'Chief Financial Officer',
                'is_primary' => false,
                'is_real' => true,
                'created_by' => $user->id,
            ],

            // Adidas AG
            [
                'company_id' => $adidasId,
                'first_name' => 'Bjorn',
                'last_name' => 'Gulden',
                'email' => 'bjorn.gulden@adidas.com',
                'phone' => '+49 9132 84-0',
                'mobile' => '+49 172 1234567',
                'job_title' => 'CEO',
                'is_primary' => true,
                'is_real' => true,
                'created_by' => $user->id,
            ],
            [
                'company_id' => $adidasId,
                'first_name' => 'Harm',
                'last_name' => 'Ohlmeyer',
                'email' => 'harm.ohlmeyer@adidas.com',
                'phone' => '+49 9132 84-1',
                'mobile' => '+49 172 1234568',
                'job_title' => 'Chief Financial Officer',
                'is_primary' => false,
                'is_real' => true,
                'created_by' => $user->id,
            ],

            // JPMorgan Chase & Co.
            [
                'company_id' => $jpmorganId,
                'first_name' => 'Jamie',
                'last_name' => 'Dimon',
                'email' => 'jamie.dimon@jpmchase.com',
                'phone' => '+1 (212) 270-6000',
                'mobile' => '+1 (212) 555-1901',
                'job_title' => 'CEO',
                'is_primary' => true,
                'is_real' => true,
                'created_by' => $user->id,
            ],
            [
                'company_id' => $jpmorganId,
                'first_name' => 'Jeremy',
                'last_name' => 'Barnum',
                'email' => 'jeremy.barnum@jpmchase.com',
                'phone' => '+1 (212) 270-6001',
                'mobile' => null,
                'job_title' => 'Chief Financial Officer',
                'is_primary' => false,
                'is_real' => true,
                'created_by' => $user->id,
            ],

            // Bank of America
            [
                'company_id' => $boaId,
                'first_name' => 'Brian',
                'last_name' => 'Moynihan',
                'email' => 'brian.moynihan@bankofamerica.com',
                'phone' => '+1 (704) 386-5681',
                'mobile' => '+1 (704) 555-2001',
                'job_title' => 'CEO',
                'is_primary' => true,
                'is_real' => true,
                'created_by' => $user->id,
            ],
            [
                'company_id' => $boaId,
                'first_name' => 'Alastair',
                'last_name' => 'Borthwick',
                'email' => 'alastair.borthwick@bankofamerica.com',
                'phone' => '+1 (704) 386-5682',
                'mobile' => '+1 (704) 555-2002',
                'job_title' => 'Chief Financial Officer',
                'is_primary' => false,
                'is_real' => true,
                'created_by' => $user->id,
            ],

            // Pfizer Inc.
            [
                'company_id' => $pfizerId,
                'first_name' => 'Albert',
                'last_name' => 'Bourla',
                'email' => 'albert.bourla@pfizer.com',
                'phone' => '+1 (212) 733-2323',
                'mobile' => '+1 (212) 555-2101',
                'job_title' => 'CEO',
                'is_primary' => true,
                'is_real' => true,
                'created_by' => $user->id,
            ],
            [
                'company_id' => $pfizerId,
                'first_name' => 'David',
                'last_name' => 'Denton',
                'email' => 'david.denton@pfizer.com',
                'phone' => '+1 (212) 733-2324',
                'mobile' => null,
                'job_title' => 'Chief Financial Officer',
                'is_primary' => false,
                'is_real' => true,
                'created_by' => $user->id,
            ],

            // Johnson & Johnson
            [
                'company_id' => $jnjId,
                'first_name' => 'Joaquin',
                'last_name' => 'Duato',
                'email' => 'joaquin.duato@its.jnj.com',
                'phone' => '+1 (732) 524-0400',
                'mobile' => '+1 (732) 555-2201',
                'job_title' => 'CEO',
                'is_primary' => true,
                'is_real' => true,
                'created_by' => $user->id,
            ],
            [
                'company_id' => $jnjId,
                'first_name' => 'Joseph',
                'last_name' => 'Wolk',
                'email' => 'joseph.wolk@its.jnj.com',
                'phone' => '+1 (732) 524-0401',
                'mobile' => '+1 (732) 555-2202',
                'job_title' => 'Chief Financial Officer',
                'is_primary' => false,
                'is_real' => true,
                'created_by' => $user->id,
            ],

            // Verizon Communications
            [
                'company_id' => $verizonId,
                'first_name' => 'Hans',
                'last_name' => 'Vestberg',
                'email' => 'hans.vestberg@verizon.com',
                'phone' => '+1 (212) 395-1000',
                'mobile' => '+1 (212) 555-2301',
                'job_title' => 'CEO',
                'is_primary' => true,
                'is_real' => true,
                'created_by' => $user->id,
            ],
            [
                'company_id' => $verizonId,
                'first_name' => 'Tony',
                'last_name' => 'Skiadas',
                'email' => 'tony.skiadas@verizon.com',
                'phone' => '+1 (212) 395-1001',
                'mobile' => null,
                'job_title' => 'Chief Financial Officer',
                'is_primary' => false,
                'is_real' => true,
                'created_by' => $user->id,
            ],

            // AT&T Inc.
            [
                'company_id' => $attId,
                'first_name' => 'John',
                'last_name' => 'Stankey',
                'email' => 'john.stankey@att.com',
                'phone' => '+1 (210) 821-4105',
                'mobile' => '+1 (210) 555-2401',
                'job_title' => 'CEO',
                'is_primary' => true,
                'is_real' => true,
                'created_by' => $user->id,
            ],
            [
                'company_id' => $attId,
                'first_name' => 'Pascal',
                'last_name' => 'Desroches',
                'email' => 'pascal.desroches@att.com',
                'phone' => '+1 (210) 821-4106',
                'mobile' => '+1 (210) 555-2402',
                'job_title' => 'Chief Financial Officer',
                'is_primary' => false,
                'is_real' => true,
                'created_by' => $user->id,
            ],

            // ExxonMobil Corporation
            [
                'company_id' => $exxonId,
                'first_name' => 'Darren',
                'last_name' => 'Woods',
                'email' => 'darren.woods@exxonmobil.com',
                'phone' => '+1 (972) 940-6000',
                'mobile' => '+1 (972) 555-2501',
                'job_title' => 'CEO',
                'is_primary' => true,
                'is_real' => true,
                'created_by' => $user->id,
            ],
            [
                'company_id' => $exxonId,
                'first_name' => 'Kathryn',
                'last_name' => 'Mikells',
                'email' => 'kathryn.mikells@exxonmobil.com',
                'phone' => '+1 (972) 940-6001',
                'mobile' => null,
                'job_title' => 'Chief Financial Officer',
                'is_primary' => false,
                'is_real' => true,
                'created_by' => $user->id,
            ],

            // Chevron Corporation
            [
                'company_id' => $chevronId,
                'first_name' => 'Mike',
                'last_name' => 'Wirth',
                'email' => 'mike.wirth@chevron.com',
                'phone' => '+1 (925) 842-1000',
                'mobile' => '+1 (925) 555-2601',
                'job_title' => 'CEO',
                'is_primary' => true,
                'is_real' => true,
                'created_by' => $user->id,
            ],
            [
                'company_id' => $chevronId,
                'first_name' => 'Pierre',
                'last_name' => 'Breber',
                'email' => 'pierre.breber@chevron.com',
                'phone' => '+1 (925) 842-1001',
                'mobile' => '+1 (925) 555-2602',
                'job_title' => 'Chief Financial Officer',
                'is_primary' => false,
                'is_real' => true,
                'created_by' => $user->id,
            ],

            // Boeing Company
            [
                'company_id' => $boeingId,
                'first_name' => 'David',
                'last_name' => 'Calhoun',
                'email' => 'david.calhoun@boeing.com',
                'phone' => '+1 (312) 544-2000',
                'mobile' => '+1 (312) 555-2701',
                'job_title' => 'CEO',
                'is_primary' => true,
                'is_real' => true,
                'created_by' => $user->id,
            ],
            [
                'company_id' => $boeingId,
                'first_name' => 'Brian',
                'last_name' => 'West',
                'email' => 'brian.west@boeing.com',
                'phone' => '+1 (312) 544-2001',
                'mobile' => null,
                'job_title' => 'Chief Financial Officer',
                'is_primary' => false,
                'is_real' => true,
                'created_by' => $user->id,
            ],

            // Airbus SE
            [
                'company_id' => $airbusId,
                'first_name' => 'Guillaume',
                'last_name' => 'Faury',
                'email' => 'guillaume.faury@airbus.com',
                'phone' => '+33 5 61 93 33 33',
                'mobile' => '+33 6 12 34 56 01',
                'job_title' => 'CEO',
                'is_primary' => true,
                'is_real' => true,
                'created_by' => $user->id,
            ],
            [
                'company_id' => $airbusId,
                'first_name' => 'Thomas',
                'last_name' => 'Toepfer',
                'email' => 'thomas.toepfer@airbus.com',
                'phone' => '+33 5 61 93 33 34',
                'mobile' => '+33 6 12 34 56 02',
                'job_title' => 'Chief Financial Officer',
                'is_primary' => false,
                'is_real' => true,
                'created_by' => $user->id,
            ],

            // Deloitte
            [
                'company_id' => $deloitteId,
                'first_name' => 'Joe',
                'last_name' => 'Ucuzoglu',
                'email' => 'joe.ucuzoglu@deloitte.com',
                'phone' => '+1 (212) 492-4000',
                'mobile' => '+1 (212) 555-2801',
                'job_title' => 'CEO',
                'is_primary' => true,
                'is_real' => true,
                'created_by' => $user->id,
            ],
            [
                'company_id' => $deloitteId,
                'first_name' => 'Jason',
                'last_name' => 'Girzadas',
                'email' => 'jason.girzadas@deloitte.com',
                'phone' => '+1 (212) 492-4001',
                'mobile' => '+1 (212) 555-2802',
                'job_title' => 'Chief Financial Officer',
                'is_primary' => false,
                'is_real' => true,
                'created_by' => $user->id,
            ],

            // McKinsey & Company
            [
                'company_id' => $mckinseyId,
                'first_name' => 'Bob',
                'last_name' => 'Sternfels',
                'email' => 'bob.sternfels@mckinsey.com',
                'phone' => '+1 (212) 446-7000',
                'mobile' => '+1 (212) 555-2901',
                'job_title' => 'Global Managing Partner',
                'is_primary' => true,
                'is_real' => true,
                'created_by' => $user->id,
            ],
            [
                'company_id' => $mckinseyId,
                'first_name' => 'Liz',
                'last_name' => 'Hilton Segel',
                'email' => 'liz.segel@mckinsey.com',
                'phone' => '+1 (212) 446-7001',
                'mobile' => '+1 (212) 555-2902',
                'job_title' => 'Chief Client Officer',
                'is_primary' => false,
                'is_real' => true,
                'created_by' => $user->id,
            ],
        ];

        foreach ($contacts as $contactData) {
            CompanyContact::create($contactData);
        }

        $this->command->info('Created ' . count($contacts) . ' company contacts.');
    }
}
