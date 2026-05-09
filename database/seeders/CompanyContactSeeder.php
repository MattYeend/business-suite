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
        $companies = Company::all();
        $users = User::all();
        
        if ($companies->isEmpty() || $users->isEmpty()) {
            $this->command->warn('No companies or users found. Please run CompanySeeder and UserSeeder first.');
            return;
        }

        // Get company IDs
        $armId = Company::where('name', 'ARM Holdings')->first()?->id;
        $sageId = Company::where('name', 'Sage Group')->first()?->id;
        $raspberryPiId = Company::where('name', 'Raspberry Pi Ltd')->first()?->id;
        $sophosId = Company::where('name', 'Sophos Ltd')->first()?->id;
        $rollsRoyceMotorsId = Company::where('name', 'Rolls-Royce Motor Cars')->first()?->id;
        $bentleyId = Company::where('name', 'Bentley Motors')->first()?->id;
        $astonMartinId = Company::where('name', 'Aston Martin Lagonda')->first()?->id;
        $mclarenId = Company::where('name', 'McLaren Automotive')->first()?->id;
        $tescoId = Company::where('name', 'Tesco PLC')->first()?->id;
        $marksAndSpencerId = Company::where('name', 'Marks & Spencer')->first()?->id;
        $johnLewisId = Company::where('name', 'John Lewis Partnership')->first()?->id;
        $nextId = Company::where('name', 'Next PLC')->first()?->id;
        $diageoId = Company::where('name', 'Diageo PLC')->first()?->id;
        $greggsId = Company::where('name', 'Greggs PLC')->first()?->id;
        $pretId = Company::where('name', 'Pret A Manger')->first()?->id;
        $whitbreadId = Company::where('name', 'Whitbread PLC')->first()?->id;
        $burberryId = Company::where('name', 'Burberry Group')->first()?->id;
        $mulberryId = Company::where('name', 'Mulberry Group')->first()?->id;
        $barbourId = Company::where('name', 'Barbour')->first()?->id;
        $hsbcId = Company::where('name', 'HSBC Holdings')->first()?->id;
        $barclaysId = Company::where('name', 'Barclays PLC')->first()?->id;
        $lloydsId = Company::where('name', 'Lloyds Banking Group')->first()?->id;
        $natwestId = Company::where('name', 'NatWest Group')->first()?->id;
        $astrazenecaId = Company::where('name', 'AstraZeneca PLC')->first()?->id;
        $gskId = Company::where('name', 'GlaxoSmithKline')->first()?->id;
        $btId = Company::where('name', 'BT Group PLC')->first()?->id;
        $vodafoneId = Company::where('name', 'Vodafone Group')->first()?->id;
        $bpId = Company::where('name', 'BP PLC')->first()?->id;
        $shellId = Company::where('name', 'Shell PLC')->first()?->id;
        $rollsRoyceHoldingsId = Company::where('name', 'Rolls-Royce Holdings')->first()?->id;
        $baeSystemsId = Company::where('name', 'BAE Systems')->first()?->id;
        $pwcId = Company::where('name', 'PricewaterhouseCoopers UK')->first()?->id;
        $kpmgId = Company::where('name', 'KPMG UK')->first()?->id;
        $bbcId = Company::where('name', 'BBC Studios')->first()?->id;
        $skyId = Company::where('name', 'Sky UK')->first()?->id;

        $contacts = [
            // ARM Holdings
            [
                'company_id' => $armId,
                'first_name' => 'Rene',
                'last_name' => 'Haas',
                'email' => 'rene.haas@arm.com',
                'phone' => '+44 (0)1223 400400',
                'mobile' => '+44 (0)7700 900101',
                'job_title' => 'CEO',
                'is_primary' => true,
                'is_real' => true,
                'created_by' => User::inRandomOrder()->first()?->id,
            ],
            [
                'company_id' => $armId,
                'first_name' => 'Jason',
                'last_name' => 'Child',
                'email' => 'jason.child@arm.com',
                'phone' => '+44 (0)1223 400401',
                'mobile' => null,
                'job_title' => 'Chief Financial Officer',
                'is_primary' => false,
                'is_real' => true,
                'created_by' => User::inRandomOrder()->first()?->id,
            ],

            // Sage Group
            [
                'company_id' => $sageId,
                'first_name' => 'Steve',
                'last_name' => 'Hare',
                'email' => 'steve.hare@sage.com',
                'phone' => '+44 (0)191 294 3000',
                'mobile' => '+44 (0)7700 900201',
                'job_title' => 'CEO',
                'is_primary' => true,
                'is_real' => true,
                'created_by' => User::inRandomOrder()->first()?->id,
            ],
            [
                'company_id' => $sageId,
                'first_name' => 'Jonathan',
                'last_name' => 'Howell',
                'email' => 'jonathan.howell@sage.com',
                'phone' => '+44 (0)191 294 3001',
                'mobile' => '+44 (0)7700 900202',
                'job_title' => 'Chief Financial Officer',
                'is_primary' => false,
                'is_real' => true,
                'created_by' => User::inRandomOrder()->first()?->id,
            ],

            // Raspberry Pi Ltd
            [
                'company_id' => $raspberryPiId,
                'first_name' => 'Eben',
                'last_name' => 'Upton',
                'email' => 'eben.upton@raspberrypi.com',
                'phone' => '+44 (0)1223 322633',
                'mobile' => '+44 (0)7700 900301',
                'job_title' => 'CEO',
                'is_primary' => true,
                'is_real' => true,
                'created_by' => User::inRandomOrder()->first()?->id,
            ],

            // Sophos Ltd
            [
                'company_id' => $sophosId,
                'first_name' => 'Joe',
                'last_name' => 'Levy',
                'email' => 'joe.levy@sophos.com',
                'phone' => '+44 (0)1235 559933',
                'mobile' => '+44 (0)7700 900401',
                'job_title' => 'CEO',
                'is_primary' => true,
                'is_real' => true,
                'created_by' => User::inRandomOrder()->first()?->id,
            ],

            // Rolls-Royce Motor Cars
            [
                'company_id' => $rollsRoyceMotorsId,
                'first_name' => 'Chris',
                'last_name' => 'Brownridge',
                'email' => 'chris.brownridge@rolls-roycemotorcars.com',
                'phone' => '+44 (0)1243 525700',
                'mobile' => '+44 (0)7700 900501',
                'job_title' => 'CEO',
                'is_primary' => true,
                'is_real' => true,
                'created_by' => User::inRandomOrder()->first()?->id,
            ],

            // Bentley Motors
            [
                'company_id' => $bentleyId,
                'first_name' => 'Adrian',
                'last_name' => 'Hallmark',
                'email' => 'adrian.hallmark@bentley.co.uk',
                'phone' => '+44 (0)1270 259959',
                'mobile' => '+44 (0)7700 900601',
                'job_title' => 'Chairman & CEO',
                'is_primary' => true,
                'is_real' => true,
                'created_by' => User::inRandomOrder()->first()?->id,
            ],

            // Aston Martin Lagonda
            [
                'company_id' => $astonMartinId,
                'first_name' => 'Amedeo',
                'last_name' => 'Felisa',
                'email' => 'amedeo.felisa@astonmartin.com',
                'phone' => '+44 (0)1926 644644',
                'mobile' => '+44 (0)7700 900701',
                'job_title' => 'CEO',
                'is_primary' => true,
                'is_real' => true,
                'created_by' => User::inRandomOrder()->first()?->id,
            ],
            [
                'company_id' => $astonMartinId,
                'first_name' => 'Doug',
                'last_name' => 'Lafferty',
                'email' => 'doug.lafferty@astonmartin.com',
                'phone' => '+44 (0)1926 644645',
                'mobile' => null,
                'job_title' => 'Chief Financial Officer',
                'is_primary' => false,
                'is_real' => true,
                'created_by' => User::inRandomOrder()->first()?->id,
            ],

            // McLaren Automotive
            [
                'company_id' => $mclarenId,
                'first_name' => 'Michael',
                'last_name' => 'Leiters',
                'email' => 'michael.leiters@mclaren.com',
                'phone' => '+44 (0)1483 261000',
                'mobile' => '+44 (0)7700 900801',
                'job_title' => 'CEO',
                'is_primary' => true,
                'is_real' => true,
                'created_by' => User::inRandomOrder()->first()?->id,
            ],

            // Tesco PLC
            [
                'company_id' => $tescoId,
                'first_name' => 'Ken',
                'last_name' => 'Murphy',
                'email' => 'ken.murphy@tesco.com',
                'phone' => '+44 (0)800 505555',
                'mobile' => '+44 (0)7700 900901',
                'job_title' => 'CEO',
                'is_primary' => true,
                'is_real' => true,
                'created_by' => User::inRandomOrder()->first()?->id,
            ],
            [
                'company_id' => $tescoId,
                'first_name' => 'Imran',
                'last_name' => 'Nawaz',
                'email' => 'imran.nawaz@tesco.com',
                'phone' => '+44 (0)800 505556',
                'mobile' => '+44 (0)7700 900902',
                'job_title' => 'Chief Financial Officer',
                'is_primary' => false,
                'is_real' => true,
                'created_by' => User::inRandomOrder()->first()?->id,
            ],

            // Marks & Spencer
            [
                'company_id' => $marksAndSpencerId,
                'first_name' => 'Stuart',
                'last_name' => 'Machin',
                'email' => 'stuart.machin@marks-and-spencer.com',
                'phone' => '+44 (0)333 014 8555',
                'mobile' => '+44 (0)7700 901001',
                'job_title' => 'CEO',
                'is_primary' => true,
                'is_real' => true,
                'created_by' => User::inRandomOrder()->first()?->id,
            ],
            [
                'company_id' => $marksAndSpencerId,
                'first_name' => 'Eoin',
                'last_name' => 'Tonge',
                'email' => 'eoin.tonge@marks-and-spencer.com',
                'phone' => '+44 (0)333 014 8556',
                'mobile' => null,
                'job_title' => 'Chief Financial Officer',
                'is_primary' => false,
                'is_real' => true,
                'created_by' => User::inRandomOrder()->first()?->id,
            ],

            // John Lewis Partnership
            [
                'company_id' => $johnLewisId,
                'first_name' => 'Nish',
                'last_name' => 'Kankiwala',
                'email' => 'nish.kankiwala@johnlewis.co.uk',
                'phone' => '+44 (0)345 604 9049',
                'mobile' => '+44 (0)7700 901101',
                'job_title' => 'CEO',
                'is_primary' => true,
                'is_real' => true,
                'created_by' => User::inRandomOrder()->first()?->id,
            ],

            // Next PLC
            [
                'company_id' => $nextId,
                'first_name' => 'Simon',
                'last_name' => 'Wolfson',
                'email' => 'simon.wolfson@next.co.uk',
                'phone' => '+44 (0)333 777 8000',
                'mobile' => '+44 (0)7700 901201',
                'job_title' => 'CEO',
                'is_primary' => true,
                'is_real' => true,
                'created_by' => User::inRandomOrder()->first()?->id,
            ],
            [
                'company_id' => $nextId,
                'first_name' => 'Amanda',
                'last_name' => 'James',
                'email' => 'amanda.james@next.co.uk',
                'phone' => '+44 (0)333 777 8001',
                'mobile' => null,
                'job_title' => 'Chief Financial Officer',
                'is_primary' => false,
                'is_real' => true,
                'created_by' => User::inRandomOrder()->first()?->id,
            ],

            // Diageo PLC
            [
                'company_id' => $diageoId,
                'first_name' => 'Debra',
                'last_name' => 'Crew',
                'email' => 'debra.crew@diageo.com',
                'phone' => '+44 (0)20 7927 5200',
                'mobile' => '+44 (0)7700 901301',
                'job_title' => 'CEO',
                'is_primary' => true,
                'is_real' => true,
                'created_by' => User::inRandomOrder()->first()?->id,
            ],
            [
                'company_id' => $diageoId,
                'first_name' => 'Lavanya',
                'last_name' => 'Chandrashekar',
                'email' => 'lavanya.chandrashekar@diageo.com',
                'phone' => '+44 (0)20 7927 5201',
                'mobile' => '+44 (0)7700 901302',
                'job_title' => 'Chief Financial Officer',
                'is_primary' => false,
                'is_real' => true,
                'created_by' => User::inRandomOrder()->first()?->id,
            ],

            // Greggs PLC
            [
                'company_id' => $greggsId,
                'first_name' => 'Roisin',
                'last_name' => 'Currie',
                'email' => 'roisin.currie@greggs.co.uk',
                'phone' => '+44 (0)191 281 7721',
                'mobile' => '+44 (0)7700 901401',
                'job_title' => 'CEO',
                'is_primary' => true,
                'is_real' => true,
                'created_by' => User::inRandomOrder()->first()?->id,
            ],
            [
                'company_id' => $greggsId,
                'first_name' => 'Richard',
                'last_name' => 'Hutton',
                'email' => 'richard.hutton@greggs.co.uk',
                'phone' => '+44 (0)191 281 7722',
                'mobile' => null,
                'job_title' => 'Chief Financial Officer',
                'is_primary' => false,
                'is_real' => true,
                'created_by' => User::inRandomOrder()->first()?->id,
            ],

            // Pret A Manger
            [
                'company_id' => $pretId,
                'first_name' => 'Pano',
                'last_name' => 'Christou',
                'email' => 'pano.christou@pret.com',
                'phone' => '+44 (0)20 7932 5000',
                'mobile' => '+44 (0)7700 901501',
                'job_title' => 'CEO',
                'is_primary' => true,
                'is_real' => true,
                'created_by' => User::inRandomOrder()->first()?->id,
            ],

            // Whitbread PLC
            [
                'company_id' => $whitbreadId,
                'first_name' => 'Dominic',
                'last_name' => 'Paul',
                'email' => 'dominic.paul@whitbread.com',
                'phone' => '+44 (0)1582 424200',
                'mobile' => '+44 (0)7700 901601',
                'job_title' => 'CEO',
                'is_primary' => true,
                'is_real' => true,
                'created_by' => User::inRandomOrder()->first()?->id,
            ],
            [
                'company_id' => $whitbreadId,
                'first_name' => 'Hemant',
                'last_name' => 'Patel',
                'email' => 'hemant.patel@whitbread.com',
                'phone' => '+44 (0)1582 424201',
                'mobile' => null,
                'job_title' => 'Chief Financial Officer',
                'is_primary' => false,
                'is_real' => true,
                'created_by' => User::inRandomOrder()->first()?->id,
            ],

            // Burberry Group
            [
                'company_id' => $burberryId,
                'first_name' => 'Joshua',
                'last_name' => 'Schulman',
                'email' => 'joshua.schulman@burberry.com',
                'phone' => '+44 (0)20 3367 3000',
                'mobile' => '+44 (0)7700 901701',
                'job_title' => 'CEO',
                'is_primary' => true,
                'is_real' => true,
                'created_by' => User::inRandomOrder()->first()?->id,
            ],
            [
                'company_id' => $burberryId,
                'first_name' => 'Kate',
                'last_name' => 'Ferry',
                'email' => 'kate.ferry@burberry.com',
                'phone' => '+44 (0)20 3367 3001',
                'mobile' => '+44 (0)7700 901702',
                'job_title' => 'Chief Financial Officer',
                'is_primary' => false,
                'is_real' => true,
                'created_by' => User::inRandomOrder()->first()?->id,
            ],

            // Mulberry Group
            [
                'company_id' => $mulberryId,
                'first_name' => 'Thierry',
                'last_name' => 'Andretta',
                'email' => 'thierry.andretta@mulberry.com',
                'phone' => '+44 (0)1761 234500',
                'mobile' => '+44 (0)7700 901801',
                'job_title' => 'CEO',
                'is_primary' => true,
                'is_real' => true,
                'created_by' => User::inRandomOrder()->first()?->id,
            ],

            // Barbour
            [
                'company_id' => $barbourId,
                'first_name' => 'Steve',
                'last_name' => 'Buck',
                'email' => 'steve.buck@barbour.com',
                'phone' => '+44 (0)191 455 4444',
                'mobile' => '+44 (0)7700 901901',
                'job_title' => 'CEO',
                'is_primary' => true,
                'is_real' => true,
                'created_by' => User::inRandomOrder()->first()?->id,
            ],

            // HSBC Holdings
            [
                'company_id' => $hsbcId,
                'first_name' => 'Noel',
                'last_name' => 'Quinn',
                'email' => 'noel.quinn@hsbc.co.uk',
                'phone' => '+44 (0)345 740 4404',
                'mobile' => '+44 (0)7700 902001',
                'job_title' => 'Group CEO',
                'is_primary' => true,
                'is_real' => true,
                'created_by' => User::inRandomOrder()->first()?->id,
            ],
            [
                'company_id' => $hsbcId,
                'first_name' => 'Georges',
                'last_name' => 'Elhedery',
                'email' => 'georges.elhedery@hsbc.co.uk',
                'phone' => '+44 (0)345 740 4405',
                'mobile' => '+44 (0)7700 902002',
                'job_title' => 'Chief Financial Officer',
                'is_primary' => false,
                'is_real' => true,
                'created_by' => User::inRandomOrder()->first()?->id,
            ],

            // Barclays PLC
            [
                'company_id' => $barclaysId,
                'first_name' => 'C.S.',
                'last_name' => 'Venkatakrishnan',
                'email' => 'venkat@barclays.co.uk',
                'phone' => '+44 (0)345 734 5345',
                'mobile' => '+44 (0)7700 902101',
                'job_title' => 'Group CEO',
                'is_primary' => true,
                'is_real' => true,
                'created_by' => User::inRandomOrder()->first()?->id,
            ],
            [
                'company_id' => $barclaysId,
                'first_name' => 'Anna',
                'last_name' => 'Cross',
                'email' => 'anna.cross@barclays.co.uk',
                'phone' => '+44 (0)345 734 5346',
                'mobile' => null,
                'job_title' => 'Chief Financial Officer',
                'is_primary' => false,
                'is_real' => true,
                'created_by' => User::inRandomOrder()->first()?->id,
            ],

            // Lloyds Banking Group
            [
                'company_id' => $lloydsId,
                'first_name' => 'Charlie',
                'last_name' => 'Nunn',
                'email' => 'charlie.nunn@lloydsbanking.com',
                'phone' => '+44 (0)345 300 0000',
                'mobile' => '+44 (0)7700 902201',
                'job_title' => 'Group CEO',
                'is_primary' => true,
                'is_real' => true,
                'created_by' => User::inRandomOrder()->first()?->id,
            ],
            [
                'company_id' => $lloydsId,
                'first_name' => 'William',
                'last_name' => 'Chalmers',
                'email' => 'william.chalmers@lloydsbanking.com',
                'phone' => '+44 (0)345 300 0001',
                'mobile' => '+44 (0)7700 902202',
                'job_title' => 'Chief Financial Officer',
                'is_primary' => false,
                'is_real' => true,
                'created_by' => User::inRandomOrder()->first()?->id,
            ],

            // NatWest Group
            [
                'company_id' => $natwestId,
                'first_name' => 'Paul',
                'last_name' => 'Thwaite',
                'email' => 'paul.thwaite@natwest.com',
                'phone' => '+44 (0)345 788 8444',
                'mobile' => '+44 (0)7700 902301',
                'job_title' => 'CEO',
                'is_primary' => true,
                'is_real' => true,
                'created_by' => User::inRandomOrder()->first()?->id,
            ],
            [
                'company_id' => $natwestId,
                'first_name' => 'Katie',
                'last_name' => 'Murray',
                'email' => 'katie.murray@natwest.com',
                'phone' => '+44 (0)345 788 8445',
                'mobile' => null,
                'job_title' => 'Chief Financial Officer',
                'is_primary' => false,
                'is_real' => true,
                'created_by' => User::inRandomOrder()->first()?->id,
            ],

            // AstraZeneca PLC
            [
                'company_id' => $astrazenecaId,
                'first_name' => 'Pascal',
                'last_name' => 'Soriot',
                'email' => 'pascal.soriot@astrazeneca.com',
                'phone' => '+44 (0)20 3749 5000',
                'mobile' => '+44 (0)7700 902401',
                'job_title' => 'CEO',
                'is_primary' => true,
                'is_real' => true,
                'created_by' => User::inRandomOrder()->first()?->id,
            ],
            [
                'company_id' => $astrazenecaId,
                'first_name' => 'Aradhana',
                'last_name' => 'Sarin',
                'email' => 'aradhana.sarin@astrazeneca.com',
                'phone' => '+44 (0)20 3749 5001',
                'mobile' => '+44 (0)7700 902402',
                'job_title' => 'Chief Financial Officer',
                'is_primary' => false,
                'is_real' => true,
                'created_by' => User::inRandomOrder()->first()?->id,
            ],

            // GlaxoSmithKline
            [
                'company_id' => $gskId,
                'first_name' => 'Emma',
                'last_name' => 'Walmsley',
                'email' => 'emma.walmsley@gsk.com',
                'phone' => '+44 (0)20 8047 5000',
                'mobile' => '+44 (0)7700 902501',
                'job_title' => 'CEO',
                'is_primary' => true,
                'is_real' => true,
                'created_by' => User::inRandomOrder()->first()?->id,
            ],
            [
                'company_id' => $gskId,
                'first_name' => 'Julie',
                'last_name' => 'Brown',
                'email' => 'julie.brown@gsk.com',
                'phone' => '+44 (0)20 8047 5001',
                'mobile' => null,
                'job_title' => 'Chief Financial Officer',
                'is_primary' => false,
                'is_real' => true,
                'created_by' => User::inRandomOrder()->first()?->id,
            ],

            // BT Group PLC
            [
                'company_id' => $btId,
                'first_name' => 'Philip',
                'last_name' => 'Jansen',
                'email' => 'philip.jansen@bt.com',
                'phone' => '+44 (0)800 800 150',
                'mobile' => '+44 (0)7700 902601',
                'job_title' => 'CEO',
                'is_primary' => true,
                'is_real' => true,
                'created_by' => User::inRandomOrder()->first()?->id,
            ],
            [
                'company_id' => $btId,
                'first_name' => 'Simon',
                'last_name' => 'Lowth',
                'email' => 'simon.lowth@bt.com',
                'phone' => '+44 (0)800 800 151',
                'mobile' => '+44 (0)7700 902602',
                'job_title' => 'Chief Financial Officer',
                'is_primary' => false,
                'is_real' => true,
                'created_by' => User::inRandomOrder()->first()?->id,
            ],

            // Vodafone Group
            [
                'company_id' => $vodafoneId,
                'first_name' => 'Margherita',
                'last_name' => 'Della Valle',
                'email' => 'margherita.dellavalle@vodafone.com',
                'phone' => '+44 (0)333 304 0191',
                'mobile' => '+44 (0)7700 902701',
                'job_title' => 'Group CEO',
                'is_primary' => true,
                'is_real' => true,
                'created_by' => User::inRandomOrder()->first()?->id,
            ],
            [
                'company_id' => $vodafoneId,
                'first_name' => 'Luka',
                'last_name' => 'Mucic',
                'email' => 'luka.mucic@vodafone.com',
                'phone' => '+44 (0)333 304 0192',
                'mobile' => null,
                'job_title' => 'Chief Financial Officer',
                'is_primary' => false,
                'is_real' => true,
                'created_by' => User::inRandomOrder()->first()?->id,
            ],

            // BP PLC
            [
                'company_id' => $bpId,
                'first_name' => 'Murray',
                'last_name' => 'Auchincloss',
                'email' => 'murray.auchincloss@bp.com',
                'phone' => '+44 (0)20 7496 4000',
                'mobile' => '+44 (0)7700 902801',
                'job_title' => 'CEO',
                'is_primary' => true,
                'is_real' => true,
                'created_by' => User::inRandomOrder()->first()?->id,
            ],
            [
                'company_id' => $bpId,
                'first_name' => 'Kate',
                'last_name' => 'Thomson',
                'email' => 'kate.thomson@bp.com',
                'phone' => '+44 (0)20 7496 4001',
                'mobile' => '+44 (0)7700 902802',
                'job_title' => 'Chief Financial Officer',
                'is_primary' => false,
                'is_real' => true,
                'created_by' => User::inRandomOrder()->first()?->id,
            ],

            // Shell PLC
            [
                'company_id' => $shellId,
                'first_name' => 'Wael',
                'last_name' => 'Sawan',
                'email' => 'wael.sawan@shell.com',
                'phone' => '+44 (0)20 7934 1234',
                'mobile' => '+44 (0)7700 902901',
                'job_title' => 'CEO',
                'is_primary' => true,
                'is_real' => true,
                'created_by' => User::inRandomOrder()->first()?->id,
            ],
            [
                'company_id' => $shellId,
                'first_name' => 'Sinead',
                'last_name' => 'Gorman',
                'email' => 'sinead.gorman@shell.com',
                'phone' => '+44 (0)20 7934 1235',
                'mobile' => '+44 (0)7700 902902',
                'job_title' => 'Chief Financial Officer',
                'is_primary' => false,
                'is_real' => true,
                'created_by' => User::inRandomOrder()->first()?->id,
            ],

            // Rolls-Royce Holdings
            [
                'company_id' => $rollsRoyceHoldingsId,
                'first_name' => 'Tufan',
                'last_name' => 'Erginbilgic',
                'email' => 'tufan.erginbilgic@rolls-royce.com',
                'phone' => '+44 (0)1332 242424',
                'mobile' => '+44 (0)7700 903001',
                'job_title' => 'CEO',
                'is_primary' => true,
                'is_real' => true,
                'created_by' => User::inRandomOrder()->first()?->id,
            ],
            [
                'company_id' => $rollsRoyceHoldingsId,
                'first_name' => 'Helen',
                'last_name' => 'McCabe',
                'email' => 'helen.mccabe@rolls-royce.com',
                'phone' => '+44 (0)1332 242425',
                'mobile' => null,
                'job_title' => 'Chief Financial Officer',
                'is_primary' => false,
                'is_real' => true,
                'created_by' => User::inRandomOrder()->first()?->id,
            ],

            // BAE Systems
            [
                'company_id' => $baeSystemsId,
                'first_name' => 'Charles',
                'last_name' => 'Woodburn',
                'email' => 'charles.woodburn@baesystems.com',
                'phone' => '+44 (0)1252 373232',
                'mobile' => '+44 (0)7700 903101',
                'job_title' => 'CEO',
                'is_primary' => true,
                'is_real' => true,
                'created_by' => User::inRandomOrder()->first()?->id,
            ],
            [
                'company_id' => $baeSystemsId,
                'first_name' => 'Bradley',
                'last_name' => 'Greve',
                'email' => 'bradley.greve@baesystems.com',
                'phone' => '+44 (0)1252 373233',
                'mobile' => '+44 (0)7700 903102',
                'job_title' => 'Chief Financial Officer',
                'is_primary' => false,
                'is_real' => true,
                'created_by' => User::inRandomOrder()->first()?->id,
            ],

            // PricewaterhouseCoopers UK
            [
                'company_id' => $pwcId,
                'first_name' => 'Kevin',
                'last_name' => 'Ellis',
                'email' => 'kevin.ellis@pwc.com',
                'phone' => '+44 (0)20 7583 5000',
                'mobile' => '+44 (0)7700 903201',
                'job_title' => 'UK Chairman',
                'is_primary' => true,
                'is_real' => true,
                'created_by' => User::inRandomOrder()->first()?->id,
            ],
            [
                'company_id' => $pwcId,
                'first_name' => 'Laura',
                'last_name' => 'Hinton',
                'email' => 'laura.hinton@pwc.com',
                'phone' => '+44 (0)20 7583 5001',
                'mobile' => '+44 (0)7700 903202',
                'job_title' => 'UK Senior Partner',
                'is_primary' => false,
                'is_real' => true,
                'created_by' => User::inRandomOrder()->first()?->id,
            ],

            // KPMG UK
            [
                'company_id' => $kpmgId,
                'first_name' => 'Jon',
                'last_name' => 'Holt',
                'email' => 'jon.holt@kpmg.co.uk',
                'phone' => '+44 (0)20 7311 1000',
                'mobile' => '+44 (0)7700 903301',
                'job_title' => 'UK Chairman',
                'is_primary' => true,
                'is_real' => true,
                'created_by' => User::inRandomOrder()->first()?->id,
            ],

            // BBC Studios
            [
                'company_id' => $bbcId,
                'first_name' => 'Tom',
                'last_name' => 'Fussell',
                'email' => 'tom.fussell@bbcstudios.com',
                'phone' => '+44 (0)20 8433 2000',
                'mobile' => '+44 (0)7700 903401',
                'job_title' => 'CEO',
                'is_primary' => true,
                'is_real' => true,
                'created_by' => User::inRandomOrder()->first()?->id,
            ],

            // Sky UK
            [
                'company_id' => $skyId,
                'first_name' => 'Dana',
                'last_name' => 'Strong',
                'email' => 'dana.strong@sky.uk',
                'phone' => '+44 (0)333 100 0333',
                'mobile' => '+44 (0)7700 903501',
                'job_title' => 'Group CEO',
                'is_primary' => true,
                'is_real' => true,
                'created_by' => User::inRandomOrder()->first()?->id,
            ],
            [
                'company_id' => $skyId,
                'first_name' => 'Andrew',
                'last_name' => 'Griffith',
                'email' => 'andrew.griffith@sky.uk',
                'phone' => '+44 (0)333 100 0334',
                'mobile' => '+44 (0)7700 903502',
                'job_title' => 'Chief Financial Officer',
                'is_primary' => false,
                'is_real' => true,
                'created_by' => User::inRandomOrder()->first()?->id,
            ],
        ];

        $created = 0;

        foreach ($contacts as $contactData) {

            $contact = CompanyContact::firstOrCreate(
                [
                    'company_id' => $contactData['company_id'],
                    'email' => $contactData['email'],
                ],
                $contactData
            );

            if ($contact->wasRecentlyCreated) {
                $created++;
            }
        }

        $this->command->info("Created {$created} company contacts.");
    }
}
