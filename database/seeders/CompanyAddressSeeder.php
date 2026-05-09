<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\CompanyAddress;
use App\Models\User;
use Illuminate\Database\Seeder;

class CompanyAddressSeeder extends Seeder
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

        $addressesData = [
            /*
            |--------------------------------------------------------------------------
            | Technology Companies
            |--------------------------------------------------------------------------
            */

            // ARM Holdings
            [
                'company_id' => $armId,
                'type' => CompanyAddress::TYPE_OFFICE,
                'address_line_1' => '110 Fulbourn Road',
                'city' => 'Cambridge',
                'county' => 'Cambridgeshire',
                'postal_code' => 'CB1 9NJ',
                'country' => 'United Kingdom',
                'is_primary' => true,
                'is_real' => true,
                'created_by' => User::inRandomOrder()->first()?->id,
            ],
            [
                'company_id' => $armId,
                'type' => CompanyAddress::TYPE_BILLING,
                'address_line_1' => '110 Fulbourn Road',
                'city' => 'Cambridge',
                'county' => 'Cambridgeshire',
                'postal_code' => 'CB1 9NJ',
                'country' => 'United Kingdom',
                'is_primary' => false,
                'is_real' => true,
                'created_by' => User::inRandomOrder()->first()?->id,
            ],

            // Sage Group
            [
                'company_id' => $sageId,
                'type' => CompanyAddress::TYPE_OFFICE,
                'address_line_1' => 'North Park',
                'city' => 'Newcastle upon Tyne',
                'county' => 'Tyne and Wear',
                'postal_code' => 'NE13 9AA',
                'country' => 'United Kingdom',
                'is_primary' => true,
                'is_real' => true,
                'created_by' => User::inRandomOrder()->first()?->id,
            ],

            // Raspberry Pi Ltd
            [
                'company_id' => $raspberryPiId,
                'type' => CompanyAddress::TYPE_OFFICE,
                'address_line_1' => 'Maurice Wilkes Building',
                'address_line_2' => 'St Johns Innovation Park',
                'city' => 'Cambridge',
                'county' => 'Cambridgeshire',
                'postal_code' => 'CB4 0DS',
                'country' => 'United Kingdom',
                'is_primary' => true,
                'is_real' => true,
                'created_by' => User::inRandomOrder()->first()?->id,
            ],

            // Sophos Ltd
            [
                'company_id' => $sophosId,
                'type' => CompanyAddress::TYPE_OFFICE,
                'address_line_1' => 'The Pentagon',
                'address_line_2' => 'Abingdon Science Park',
                'city' => 'Abingdon',
                'county' => 'Oxfordshire',
                'postal_code' => 'OX14 3YP',
                'country' => 'United Kingdom',
                'is_primary' => true,
                'is_real' => true,
                'created_by' => User::inRandomOrder()->first()?->id,
            ],

            /*
            |--------------------------------------------------------------------------
            | Automotive Companies
            |--------------------------------------------------------------------------
            */

            // Rolls-Royce Motor Cars
            [
                'company_id' => $rollsRoyceMotorsId,
                'type' => CompanyAddress::TYPE_OFFICE,
                'address_line_1' => 'The Drive',
                'address_line_2' => 'Westhampnett',
                'city' => 'Chichester',
                'county' => 'West Sussex',
                'postal_code' => 'PO18 0SH',
                'country' => 'United Kingdom',
                'is_primary' => true,
                'is_real' => true,
                'created_by' => User::inRandomOrder()->first()?->id,
            ],

            // Bentley Motors
            [
                'company_id' => $bentleyId,
                'type' => CompanyAddress::TYPE_OFFICE,
                'address_line_1' => 'Pyms Lane',
                'city' => 'Crewe',
                'county' => 'Cheshire',
                'postal_code' => 'CW1 3PL',
                'country' => 'United Kingdom',
                'is_primary' => true,
                'is_real' => true,
                'created_by' => User::inRandomOrder()->first()?->id,
            ],
            [
                'company_id' => $bentleyId,
                'type' => CompanyAddress::TYPE_FACTORY,
                'address_line_1' => 'Bentley Motors Factory',
                'address_line_2' => 'Pyms Lane',
                'city' => 'Crewe',
                'county' => 'Cheshire',
                'postal_code' => 'CW1 3PL',
                'country' => 'United Kingdom',
                'is_primary' => false,
                'is_real' => true,
                'created_by' => User::inRandomOrder()->first()?->id,
            ],

            // Aston Martin Lagonda
            [
                'company_id' => $astonMartinId,
                'type' => CompanyAddress::TYPE_OFFICE,
                'address_line_1' => 'Banbury Road',
                'city' => 'Gaydon',
                'county' => 'Warwickshire',
                'postal_code' => 'CV35 0DB',
                'country' => 'United Kingdom',
                'is_primary' => true,
                'is_real' => true,
                'created_by' => User::inRandomOrder()->first()?->id,
            ],
            [
                'company_id' => $astonMartinId,
                'type' => CompanyAddress::TYPE_FACTORY,
                'address_line_1' => 'St Athan Facility',
                'city' => 'St Athan',
                'county' => 'Vale of Glamorgan',
                'postal_code' => 'CF62 4YF',
                'country' => 'United Kingdom',
                'is_primary' => false,
                'is_real' => true,
                'created_by' => User::inRandomOrder()->first()?->id,
            ],

            // McLaren Automotive
            [
                'company_id' => $mclarenId,
                'type' => CompanyAddress::TYPE_OFFICE,
                'address_line_1' => 'McLaren Technology Centre',
                'address_line_2' => 'Chertsey Road',
                'city' => 'Woking',
                'county' => 'Surrey',
                'postal_code' => 'GU21 4YH',
                'country' => 'United Kingdom',
                'is_primary' => true,
                'is_real' => true,
                'created_by' => User::inRandomOrder()->first()?->id,
            ],
            [
                'company_id' => $mclarenId,
                'type' => CompanyAddress::TYPE_SHOWROOM,
                'address_line_1' => 'McLaren London',
                'address_line_2' => 'One Hyde Park',
                'city' => 'London',
                'county' => 'Greater London',
                'postal_code' => 'SW1X 7LJ',
                'country' => 'United Kingdom',
                'is_primary' => false,
                'is_real' => true,
                'created_by' => User::inRandomOrder()->first()?->id,
            ],

            /*
            |--------------------------------------------------------------------------
            | Retail Companies
            |--------------------------------------------------------------------------
            */

            // Tesco PLC
            [
                'company_id' => $tescoId,
                'type' => CompanyAddress::TYPE_OFFICE,
                'address_line_1' => 'Tesco House',
                'address_line_2' => 'Shire Park',
                'city' => 'Welwyn Garden City',
                'county' => 'Hertfordshire',
                'postal_code' => 'AL7 1GA',
                'country' => 'United Kingdom',
                'is_primary' => true,
                'is_real' => true,
                'created_by' => User::inRandomOrder()->first()?->id,
            ],
            [
                'company_id' => $tescoId,
                'type' => CompanyAddress::TYPE_WAREHOUSE,
                'address_line_1' => 'Tesco Distribution Centre',
                'address_line_2' => 'Pochin Way',
                'city' => 'Middlewich',
                'county' => 'Cheshire',
                'postal_code' => 'CW10 0TE',
                'country' => 'United Kingdom',
                'is_primary' => false,
                'is_real' => true,
                'created_by' => User::inRandomOrder()->first()?->id,
            ],
            [
                'company_id' => $tescoId,
                'type' => CompanyAddress::TYPE_RETAIL,
                'address_line_1' => 'Tesco Extra',
                'address_line_2' => '1 Trinity Square',
                'city' => 'Nottingham',
                'county' => 'Nottinghamshire',
                'postal_code' => 'NG1 4AF',
                'country' => 'United Kingdom',
                'is_primary' => false,
                'is_real' => true,
                'created_by' => User::inRandomOrder()->first()?->id,
            ],

            // Marks & Spencer
            [
                'company_id' => $marksAndSpencerId,
                'type' => CompanyAddress::TYPE_OFFICE,
                'address_line_1' => 'Waterside House',
                'address_line_2' => '35 North Wharf Road',
                'city' => 'London',
                'county' => 'Greater London',
                'postal_code' => 'W2 1NW',
                'country' => 'United Kingdom',
                'is_primary' => true,
                'is_real' => true,
                'created_by' => User::inRandomOrder()->first()?->id,
            ],

            // John Lewis Partnership
            [
                'company_id' => $johnLewisId,
                'type' => CompanyAddress::TYPE_OFFICE,
                'address_line_1' => '171 Victoria Street',
                'city' => 'London',
                'county' => 'Greater London',
                'postal_code' => 'SW1E 5NN',
                'country' => 'United Kingdom',
                'is_primary' => true,
                'is_real' => true,
                'created_by' => User::inRandomOrder()->first()?->id,
            ],

            // Next PLC
            [
                'company_id' => $nextId,
                'type' => CompanyAddress::TYPE_OFFICE,
                'address_line_1' => 'Desford Road',
                'city' => 'Leicester',
                'county' => 'Leicestershire',
                'postal_code' => 'LE19 4AT',
                'country' => 'United Kingdom',
                'is_primary' => true,
                'is_real' => true,
                'created_by' => User::inRandomOrder()->first()?->id,
            ],

            /*
            |--------------------------------------------------------------------------
            | Food & Beverage Companies
            |--------------------------------------------------------------------------
            */

            // Diageo PLC
            [
                'company_id' => $diageoId,
                'type' => CompanyAddress::TYPE_OFFICE,
                'address_line_1' => 'Lakeside Drive',
                'city' => 'London',
                'county' => 'Greater London',
                'postal_code' => 'NW10 7HQ',
                'country' => 'United Kingdom',
                'is_primary' => true,
                'is_real' => true,
                'created_by' => User::inRandomOrder()->first()?->id,
            ],

            // Greggs PLC
            [
                'company_id' => $greggsId,
                'type' => CompanyAddress::TYPE_OFFICE,
                'address_line_1' => 'Greggs House',
                'address_line_2' => 'Quorum Business Park',
                'city' => 'Newcastle upon Tyne',
                'county' => 'Tyne and Wear',
                'postal_code' => 'NE12 8BU',
                'country' => 'United Kingdom',
                'is_primary' => true,
                'is_real' => true,
                'created_by' => User::inRandomOrder()->first()?->id,
            ],

            // Pret A Manger
            [
                'company_id' => $pretId,
                'type' => CompanyAddress::TYPE_OFFICE,
                'address_line_1' => '75B Verde',
                'city' => 'London',
                'county' => 'Greater London',
                'postal_code' => 'E14 9SQ',
                'country' => 'United Kingdom',
                'is_primary' => true,
                'is_real' => true,
                'created_by' => User::inRandomOrder()->first()?->id,
            ],

            // Whitbread PLC
            [
                'company_id' => $whitbreadId,
                'type' => CompanyAddress::TYPE_OFFICE,
                'address_line_1' => 'Whitbread Court',
                'address_line_2' => 'Houghton Hall Business Park',
                'city' => 'Dunstable',
                'county' => 'Bedfordshire',
                'postal_code' => 'LU5 5XE',
                'country' => 'United Kingdom',
                'is_primary' => true,
                'is_real' => true,
                'created_by' => User::inRandomOrder()->first()?->id,
            ],

            /*
            |--------------------------------------------------------------------------
            | Fashion/Apparel Companies
            |--------------------------------------------------------------------------
            */

            // Burberry Group
            [
                'company_id' => $burberryId,
                'type' => CompanyAddress::TYPE_OFFICE,
                'address_line_1' => 'Horseferry House',
                'address_line_2' => 'Horseferry Road',
                'city' => 'London',
                'county' => 'Greater London',
                'postal_code' => 'SW1P 2AW',
                'country' => 'United Kingdom',
                'is_primary' => true,
                'is_real' => true,
                'created_by' => User::inRandomOrder()->first()?->id,
            ],

            // Mulberry Group
            [
                'company_id' => $mulberryId,
                'type' => CompanyAddress::TYPE_OFFICE,
                'address_line_1' => 'The Rookery',
                'city' => 'Chilcompton',
                'county' => 'Somerset',
                'postal_code' => 'BA3 4EH',
                'country' => 'United Kingdom',
                'is_primary' => true,
                'is_real' => true,
                'created_by' => User::inRandomOrder()->first()?->id,
            ],

            // Barbour
            [
                'company_id' => $barbourId,
                'type' => CompanyAddress::TYPE_OFFICE,
                'address_line_1' => 'Simonside',
                'city' => 'South Shields',
                'county' => 'Tyne and Wear',
                'postal_code' => 'NE34 9PD',
                'country' => 'United Kingdom',
                'is_primary' => true,
                'is_real' => true,
                'created_by' => User::inRandomOrder()->first()?->id,
            ],

            /*
            |--------------------------------------------------------------------------
            | Banking Companies
            |--------------------------------------------------------------------------
            */

            // HSBC Holdings
            [
                'company_id' => $hsbcId,
                'type' => CompanyAddress::TYPE_OFFICE,
                'address_line_1' => '8 Canada Square',
                'city' => 'London',
                'county' => 'Greater London',
                'postal_code' => 'E14 5HQ',
                'country' => 'United Kingdom',
                'is_primary' => true,
                'is_real' => true,
                'created_by' => User::inRandomOrder()->first()?->id,
            ],
            [
                'company_id' => $hsbcId,
                'type' => CompanyAddress::TYPE_BRANCH,
                'address_line_1' => '60 Queen Victoria Street',
                'city' => 'London',
                'county' => 'Greater London',
                'postal_code' => 'EC4N 4TR',
                'country' => 'United Kingdom',
                'is_primary' => false,
                'is_real' => true,
                'created_by' => User::inRandomOrder()->first()?->id,
            ],

            // Barclays PLC
            [
                'company_id' => $barclaysId,
                'type' => CompanyAddress::TYPE_OFFICE,
                'address_line_1' => '1 Churchill Place',
                'city' => 'London',
                'county' => 'Greater London',
                'postal_code' => 'E14 5HP',
                'country' => 'United Kingdom',
                'is_primary' => true,
                'is_real' => true,
                'created_by' => User::inRandomOrder()->first()?->id,
            ],
            [
                'company_id' => $barclaysId,
                'type' => CompanyAddress::TYPE_BRANCH,
                'address_line_1' => '126 High Street',
                'city' => 'Oxford',
                'county' => 'Oxfordshire',
                'postal_code' => 'OX1 4DH',
                'country' => 'United Kingdom',
                'is_primary' => false,
                'is_real' => true,
                'created_by' => User::inRandomOrder()->first()?->id,
            ],

            // Lloyds Banking Group
            [
                'company_id' => $lloydsId,
                'type' => CompanyAddress::TYPE_OFFICE,
                'address_line_1' => '25 Gresham Street',
                'city' => 'London',
                'county' => 'Greater London',
                'postal_code' => 'EC2V 7HN',
                'country' => 'United Kingdom',
                'is_primary' => true,
                'is_real' => true,
                'created_by' => User::inRandomOrder()->first()?->id,
            ],

            // NatWest Group
            [
                'company_id' => $natwestId,
                'type' => CompanyAddress::TYPE_OFFICE,
                'address_line_1' => '250 Bishopsgate',
                'city' => 'London',
                'county' => 'Greater London',
                'postal_code' => 'EC2M 4AA',
                'country' => 'United Kingdom',
                'is_primary' => true,
                'is_real' => true,
                'created_by' => User::inRandomOrder()->first()?->id,
            ],

            /*
            |--------------------------------------------------------------------------
            | Pharmaceuticals Companies
            |--------------------------------------------------------------------------
            */

            // AstraZeneca PLC
            [
                'company_id' => $astrazenecaId,
                'type' => CompanyAddress::TYPE_OFFICE,
                'address_line_1' => '1 Francis Crick Avenue',
                'city' => 'Cambridge',
                'county' => 'Cambridgeshire',
                'postal_code' => 'CB2 0AA',
                'country' => 'United Kingdom',
                'is_primary' => true,
                'is_real' => true,
                'created_by' => User::inRandomOrder()->first()?->id,
            ],

            // GlaxoSmithKline
            [
                'company_id' => $gskId,
                'type' => CompanyAddress::TYPE_OFFICE,
                'address_line_1' => '980 Great West Road',
                'city' => 'Brentford',
                'county' => 'Greater London',
                'postal_code' => 'TW8 9GS',
                'country' => 'United Kingdom',
                'is_primary' => true,
                'is_real' => true,
                'created_by' => User::inRandomOrder()->first()?->id,
            ],

            /*
            |--------------------------------------------------------------------------
            | Telecommunications Companies
            |--------------------------------------------------------------------------
            */

            // BT Group PLC
            [
                'company_id' => $btId,
                'type' => CompanyAddress::TYPE_OFFICE,
                'address_line_1' => '1 Braham Street',
                'city' => 'London',
                'county' => 'Greater London',
                'postal_code' => 'E1 8EE',
                'country' => 'United Kingdom',
                'is_primary' => true,
                'is_real' => true,
                'created_by' => User::inRandomOrder()->first()?->id,
            ],

            // Vodafone Group
            [
                'company_id' => $vodafoneId,
                'type' => CompanyAddress::TYPE_OFFICE,
                'address_line_1' => 'Vodafone House',
                'address_line_2' => 'The Connection',
                'city' => 'Newbury',
                'county' => 'Berkshire',
                'postal_code' => 'RG14 2FN',
                'country' => 'United Kingdom',
                'is_primary' => true,
                'is_real' => true,
                'created_by' => User::inRandomOrder()->first()?->id,
            ],

            /*
            |--------------------------------------------------------------------------
            | Energy Companies
            |--------------------------------------------------------------------------
            */

            // BP PLC
            [
                'company_id' => $bpId,
                'type' => CompanyAddress::TYPE_OFFICE,
                'address_line_1' => '1 St James Square',
                'city' => 'London',
                'county' => 'Greater London',
                'postal_code' => 'SW1Y 4PD',
                'country' => 'United Kingdom',
                'is_primary' => true,
                'is_real' => true,
                'created_by' => User::inRandomOrder()->first()?->id,
            ],

            // Shell PLC
            [
                'company_id' => $shellId,
                'type' => CompanyAddress::TYPE_OFFICE,
                'address_line_1' => 'Shell Centre',
                'city' => 'London',
                'county' => 'Greater London',
                'postal_code' => 'SE1 7NA',
                'country' => 'United Kingdom',
                'is_primary' => true,
                'is_real' => true,
                'created_by' => User::inRandomOrder()->first()?->id,
            ],

            /*
            |--------------------------------------------------------------------------
            | Aerospace Companies
            |--------------------------------------------------------------------------
            */

            // Rolls-Royce Holdings
            [
                'company_id' => $rollsRoyceHoldingsId,
                'type' => CompanyAddress::TYPE_OFFICE,
                'address_line_1' => '62 Buckingham Gate',
                'city' => 'London',
                'county' => 'Greater London',
                'postal_code' => 'SW1E 6AT',
                'country' => 'United Kingdom',
                'is_primary' => true,
                'is_real' => true,
                'created_by' => User::inRandomOrder()->first()?->id,
            ],

            // BAE Systems
            [
                'company_id' => $baeSystemsId,
                'type' => CompanyAddress::TYPE_OFFICE,
                'address_line_1' => 'Stirling Square',
                'address_line_2' => '6 Carlton Gardens',
                'city' => 'London',
                'county' => 'Greater London',
                'postal_code' => 'SW1Y 5AD',
                'country' => 'United Kingdom',
                'is_primary' => true,
                'is_real' => true,
                'created_by' => User::inRandomOrder()->first()?->id,
            ],

            /*
            |--------------------------------------------------------------------------
            | Consulting Companies
            |--------------------------------------------------------------------------
            */

            // PricewaterhouseCoopers UK
            [
                'company_id' => $pwcId,
                'type' => CompanyAddress::TYPE_OFFICE,
                'address_line_1' => '1 Embankment Place',
                'city' => 'London',
                'county' => 'Greater London',
                'postal_code' => 'WC2N 6RH',
                'country' => 'United Kingdom',
                'is_primary' => true,
                'is_real' => true,
                'created_by' => User::inRandomOrder()->first()?->id,
            ],

            // KPMG UK
            [
                'company_id' => $kpmgId,
                'type' => CompanyAddress::TYPE_OFFICE,
                'address_line_1' => '15 Canada Square',
                'city' => 'London',
                'county' => 'Greater London',
                'postal_code' => 'E14 5GL',
                'country' => 'United Kingdom',
                'is_primary' => true,
                'is_real' => true,
                'created_by' => User::inRandomOrder()->first()?->id,
            ],

            /*
            |--------------------------------------------------------------------------
            | Entertainment Companies
            |--------------------------------------------------------------------------
            */

            // BBC Studios
            [
                'company_id' => $bbcId,
                'type' => CompanyAddress::TYPE_OFFICE,
                'address_line_1' => '1 Television Centre',
                'address_line_2' => '101 Wood Lane',
                'city' => 'London',
                'county' => 'Greater London',
                'postal_code' => 'W12 7FA',
                'country' => 'United Kingdom',
                'is_primary' => true,
                'is_real' => true,
                'created_by' => User::inRandomOrder()->first()?->id,
            ],
            [
                'company_id' => $bbcId,
                'type' => CompanyAddress::TYPE_OFFICE,
                'address_line_1' => 'MediaCityUK',
                'address_line_2' => 'Salford Quays',
                'city' => 'Salford',
                'county' => 'Greater Manchester',
                'postal_code' => 'M50 2QH',
                'country' => 'United Kingdom',
                'is_primary' => false,
                'is_real' => true,
                'created_by' => User::inRandomOrder()->first()?->id,
            ],

            // Sky UK
            [
                'company_id' => $skyId,
                'type' => CompanyAddress::TYPE_OFFICE,
                'address_line_1' => 'Grant Way',
                'city' => 'Isleworth',
                'county' => 'Greater London',
                'postal_code' => 'TW7 5QD',
                'country' => 'United Kingdom',
                'is_primary' => true,
                'is_real' => true,
                'created_by' => User::inRandomOrder()->first()?->id,
            ],
            [
                'company_id' => $skyId,
                'type' => CompanyAddress::TYPE_OFFICE,
                'address_line_1' => 'Sky Central',
                'city' => 'Livingston',
                'county' => 'West Lothian',
                'postal_code' => 'EH54 7DD',
                'country' => 'United Kingdom',
                'is_primary' => false,
                'is_real' => true,
                'created_by' => User::inRandomOrder()->first()?->id,
            ],
        ];

        $created = 0;

        foreach ($addressesData as $addressData) {
            $address = CompanyAddress::firstOrCreate(
                [
                    'company_id' => $addressData['company_id'],
                    'type' => $addressData['type'],
                    'address_line_1' => $addressData['address_line_1'],
                    'postal_code' => $addressData['postal_code'],
                ],
                $addressData
            );

            if ($address->wasRecentlyCreated) {
                $created++;
            }
        }

        $this->command->info("Created {$created} company addresses.");
    }
}