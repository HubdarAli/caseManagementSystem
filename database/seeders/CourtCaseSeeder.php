<?php

namespace Database\Seeders;

use App\Models\Court;
use Illuminate\Database\Seeder;
use App\Models\CourtCase;
use App\Models\District;
use Carbon\Carbon;

class CourtCaseSeeder extends Seeder
{
    public function run(): void
    {
        $hearingDate = Carbon::create(2025, 7, 28);

        $cases = [
            [
                'title' => 'Jamil Ahmed Arejo vs Sabir Ali Rajput',
                'case_number' => 'Summary Suit (05) 2020',
                'case_type' => 'Summary Suit',
                'status' => 'In Progress',
                'hearing_date' => $hearingDate,
                'notes' => 'For Arguments',
                'district_id' => 'South',
                'court_id' => 'IIND ADJ South',
                'applicant' => 'Jamil Ahmed Arejo',
                'respondent' => 'Sabir Ali Rajput',
            ],

            [
                'title' => 'Bravo Enterprises vs Muhammad Ayub & others',
                'case_number' => 'Civil Appeal No. 379/2023',
                'case_type' => 'Civil Appeal',
                'status' => 'In Progress',
                'hearing_date' => $hearingDate,
                'notes' => 'For Orders',
                'district_id' => 'South',
                'court_id' => 'XIIITH SCJ South',
                'applicant' => 'Bravo Enterprises',
                'respondent' => 'Muhammad Ayub & others',
            ],

            [
                'title' => 'Mehmood Sheikhani vs POS & Ors',
                'case_number' => 'Suit No. 1004/2025',
                'case_type' => 'Civil Suit',
                'status' => 'In Progress',
                'hearing_date' => $hearingDate,
                'notes' => 'For Confirmation of Status Quo',
                'district_id' => 'South',
                'court_id' => 'IIND SCJ South',
                'applicant' => 'Mehmood Sheikhani',
                'respondent' => 'POS & Ors',
            ],

            [
                'title' => 'Yaseen Hassan vs Mukhtiar (Deceased & Ors)',
                'case_number' => 'Suit No. 529/2017',
                'case_type' => 'Civil Suit',
                'status' => 'In Progress',
                'hearing_date' => $hearingDate,
                'notes' => 'For Evidence',
                'district_id' => 'South',
                'court_id' => 'IIND SCJ South',
                'applicant' => 'Yaseen Hassan',
                'respondent' => 'Mukhtiar (Deceased & Ors)',
            ],

            [
                'title' => 'David vs Bashir M/S Rafiq & Bashir',
                'case_number' => 'Suit No. 1170/2019',
                'case_type' => 'Civil Suit',
                'status' => 'In Progress',
                'hearing_date' => $hearingDate,
                'notes' => 'For Orders',
                'district_id' => 'South',
                'court_id' => 'VITH SCJ South',
                'applicant' => 'David',
                'respondent' => 'Bashir M/S Rafiq & Bashir',
            ],

            [
                'title' => 'Geo Entertainment vs Arjum Shazad',
                'case_number' => 'Suit No. 2413/2018',
                'case_type' => 'Civil Suit',
                'status' => 'In Progress',
                'hearing_date' => $hearingDate,
                'notes' => 'For Arguments',
                'district_id' => 'South',
                'court_id' => 'XTH SCJ South',
                'applicant' => 'Geo Entertainment',
                'respondent' => 'Arjum Shazad',
            ],

            [
                'title' => 'M. Saleem Kasbati vs Abdul Waheed',
                'case_number' => 'Civil No. 762/2025',
                'case_type' => 'Civil Suit',
                'status' => 'In Progress',
                'hearing_date' => $hearingDate,
                'notes' => 'For Arguments',
                'district_id' => 'South',
                'court_id' => 'XTH SCJ South',
                'applicant' => 'M. Saleem Kasbati',
                'respondent' => 'Abdul Waheed',
            ],

            [
                'title' => 'Chase Data Software Services Pvt Ltd vs M/S Global Atexim Pvt Ltd',
                'case_number' => 'Suit No. 826/2025',
                'case_type' => 'Civil Suit',
                'status' => 'In Progress',
                'hearing_date' => $hearingDate,
                'notes' => 'For Arguments',
                'district_id' => 'South',
                'court_id' => 'XIVTH SCJ South',
                'applicant' => 'Chase Data Software Services Pvt Ltd',
                'respondent' => 'M/S Global Atexim Pvt Ltd',
            ],

            [
                'title' => 'International Metal Industries vs FOP & Ors',
                'case_number' => 'Suit No. 289/2025',
                'case_type' => 'Civil Suit',
                'status' => 'In Progress',
                'hearing_date' => $hearingDate,
                'notes' => 'For Evidence',
                'district_id' => 'South',
                'court_id' => 'XIITH SCJ South',
                'applicant' => 'International Metal Industries',
                'respondent' => 'FOP & Ors',
            ],

            [
                'title' => 'Danish Elahi vs Kamalia Sugar Mills & another',
                'case_number' => 'Suit No. 380/2025',
                'case_type' => 'Civil Suit',
                'status' => 'In Progress',
                'hearing_date' => $hearingDate,
                'notes' => 'For Evidence',
                'district_id' => 'South',
                'court_id' => 'XIITH SCJ South',
                'applicant' => 'Danish Elahi',
                'respondent' => 'Kamalia Sugar Mills & another',
            ],

            [
                'title' => 'Qamar Mehmood vs Salman Arshad & Ors',
                'case_number' => 'Suit No. 440/2025',
                'case_type' => 'Civil Suit',
                'status' => 'In Progress',
                'hearing_date' => $hearingDate,
                'notes' => 'For Arguments',
                'district_id' => 'South',
                'court_id' => 'XIVTH SCJ South',
                'applicant' => 'Qamar Mehmood',
                'respondent' => 'Salman Arshad & Ors',
            ],

            [
                'title' => 'Bankers Equity Ltd (BEL) vs Dawood Jan Muhammad',
                'case_number' => 'Suit No. 6/2003',
                'case_type' => 'Suit',
                'status' => 'In Progress',
                'hearing_date' => $hearingDate,
                'notes' => 'For Plaintiff Evidence',
                'district_id' => 'South',
                'court_id' => 'XIVTH Civil Judge South',
                'applicant' => 'Bankers Equity Ltd (BEL)',
                'respondent' => 'Dawood Jan Muhammad',
            ],
            [

                'title' => 'Saeed vs Province of Sindh & Others',
                'case_number' => 'Civil 1543/2025',
                'case_type' => 'Civil Suit',
                'status' => 'In Progress',
                'hearing_date' => $hearingDate,
                'notes' => 'For Hearing',
                'district_id' => 'East',
                'court_id' => 'IST SCJ East',
                'applicant' => 'Saeed',
                'respondent' => 'Province of Sindh & Others',
            ],
            [

                'title' => 'Syed Zaid vs Shams Effendi & Ors',
                'case_number' => 'Civil No. 7173/2024',
                'case_type' => 'Civil Suit',
                'status' => 'In Progress',
                'hearing_date' => $hearingDate,
                'notes' => 'For Orders',
                'district_id' => 'East',
                'court_id' => 'IVTH SCJ East',
                'applicant' => 'Syed Zaid',
                'respondent' => 'Shams Effendi & Ors',
            ],

            [
                'title' => 'Muhammad Saleem vs POS & Ors',
                'case_number' => 'Suit No. 2102/2025',
                'case_type' => 'Civil Suit',
                'status' => 'In Progress',
                'hearing_date' => $hearingDate,
                'notes' => 'For Evidence',
                'district_id' => 'East',
                'court_id' => 'VIII TH SCJ East',
                'applicant' => 'Muhammad Saleem',
                'respondent' => 'POS & Ors',
            ],

            [
                'title' => 'Muhammad Arshad Bhojani vs POS & Ors',
                'case_number' => 'Suit No. 2303/2025',
                'case_type' => 'Civil Suit',
                'status' => 'In Progress',
                'hearing_date' => $hearingDate,
                'notes' => 'For Hearing',
                'district_id' => 'East',
                'court_id' => 'VIII TH SCJ East',
                'applicant' => 'Muhammad Arshad Bhojani',
                'respondent' => 'POS & Ors',
            ],

            [
                'title' => 'Zafar Hussain vs Muhammad Zahur & Ors',
                'case_number' => 'Suit No. 3268/2023',
                'case_type' => 'Civil Suit',
                'status' => 'In Progress',
                'hearing_date' => $hearingDate,
                'notes' => 'For Evidence',
                'district_id' => 'East',
                'court_id' => 'VIII TH SCJ East',
                'applicant' => 'Zafar Hussain',
                'respondent' => 'Muhammad Zahur & Ors',
            ],

            [
                'title' => 'Muhammad Faraz & Others vs POS & Others',
                'case_number' => 'Suit No. 6013/2025',
                'case_type' => 'Civil Suit',
                'status' => 'In Progress',
                'hearing_date' => $hearingDate,
                'notes' => 'For Evidence',
                'district_id' => 'East',
                'court_id' => 'VIII TH SCJ East',
                'applicant' => 'Muhammad Faraz & Others',
                'respondent' => 'POS & Others',
            ],
            [

                'title' => 'Suhail Ahmed Chandna & Others vs POS & Others',
                'case_number' => 'Suit No. 6067/2025',
                'case_type' => 'Civil Suit',
                'status' => 'In Progress',
                'hearing_date' => $hearingDate,
                'notes' => 'For Hearing',
                'district_id' => 'East',
                'court_id' => 'VIII TH SCJ East',
                'applicant' => 'Suhail Ahmed Chandna & Others',
                'respondent' => 'POS & Others',
            ],

            [
                'title' => 'Abdul Ghaffar vs POS & Ors',
                'case_number' => 'Civil No. 692/2025',
                'case_type' => 'Civil Suit',
                'status' => 'In Progress',
                'hearing_date' => $hearingDate,
                'notes' => 'For Service C&M',
                'district_id' => 'West',
                'court_id' => 'IIIRD SCJ West',
                'applicant' => 'Abdul Ghaffar',
                'respondent' => 'POS & Ors',
            ],

            [
                'title' => 'Muhammad Altaf vs POS & Ors',
                'case_number' => 'Civil No. 1009/2025',
                'case_type' => 'Civil Suit',
                'status' => 'In Progress',
                'hearing_date' => $hearingDate,
                'notes' => 'Exh WS',
                'district_id' => 'West',
                'court_id' => 'VITH SCJ West',
                'applicant' => 'Muhammad Altaf',
                'respondent' => 'POS & Ors',
            ],

            [
                'title' => 'Muhammad Bux vs POS & Ors',
                'case_number' => 'Civil 2064/2025',
                'case_type' => 'Civil Suit',
                'status' => 'In Progress',
                'hearing_date' => $hearingDate,
                'notes' => 'For Eating & Publication',
                'district_id' => 'West',
                'court_id' => 'XVIIITH SCJ West',
                'applicant' => 'Muhammad Bux',
                'respondent' => 'POS & Ors',
            ],

            [
                'title' => 'Muhammad Anis Qadir vs Khair Un Nisa & Ors',
                'case_number' => 'Suit No. 220/2025',
                'case_type' => 'Civil Suit',
                'status' => 'In Progress',
                'hearing_date' => $hearingDate,
                'notes' => 'For Evidence',
                'district_id' => 'Central',
                'court_id' => 'VTH SCJ Central',
                'applicant' => 'Muhammad Anis Qadir',
                'respondent' => 'Khair Un Nisa & Ors',
            ],
            [

                'title' => 'Ibrahim Nawazir & Bhramanis vs POS & Ors',
                'case_number' => 'Suit No. 3056/2025',
                'case_type' => 'Civil Suit',
                'status' => 'In Progress',
                'hearing_date' => $hearingDate,
                'notes' => 'For Evidence',
                'district_id' => 'Malir',
                'court_id' => 'IST SCJ Malir',
                'applicant' => 'Ibrahim Nawazir & Bhramanis',
                'respondent' => 'POS & Ors',
            ],
            [

                'title' => 'Muhammad Sheeraz vs POS & Ors',
                'case_number' => 'Suit No. 3202/2025',
                'case_type' => 'Civil Suit',
                'status' => 'In Progress',
                'hearing_date' => $hearingDate,
                'notes' => 'For Evidence',
                'district_id' => 'Malir',
                'court_id' => 'IIND SCJ Malir',
                'applicant' => 'Muhammad Sheeraz',
                'respondent' => 'POS & Ors',
            ],

            [
                'title' => 'Sania Irfan vs POS',
                'case_number' => 'Suit No. 702/2025',
                'case_type' => 'Civil Suit',
                'status' => 'In Progress',
                'hearing_date' => $hearingDate,
                'notes' => 'For Advocate to be heard/Say',
                'district_id' => 'Malir',
                'court_id' => 'IIIRD SCJ Malir',
                'applicant' => 'Sania Irfan',
                'respondent' => 'POS',
            ],

            [
                'title' => 'Hakim Ali vs POS',
                'case_number' => 'Suit No. 2448/2025',
                'case_type' => 'Civil Suit',
                'status' => 'In Progress',
                'hearing_date' => $hearingDate,
                'notes' => 'For Advocate to be heard/Say',
                'district_id' => 'Malir',
                'court_id' => 'VTH SCJ Malir',
                'applicant' => 'Hakim Ali',
                'respondent' => 'POS',
            ],

            [
                'title' => 'Hussain Industries vs KDA',
                'case_number' => 'Suit No. 1927/2025',
                'case_type' => 'Civil Suit',
                'status' => 'In Progress',
                'hearing_date' => $hearingDate,
                'notes' => 'For Advocate to be heard/Say',
                'district_id' => 'Malir',
                'court_id' => 'IIIRD ADJ Malir',
                'applicant' => 'Hussain Industries',
                'respondent' => 'KDA',
            ],

            [
                'title' => 'S.M Rashid Hassan vs Martynal Builders',
                'case_number' => 'Suit No. 394/2025',
                'case_type' => 'Civil Suit',
                'status' => 'In Progress',
                'hearing_date' => $hearingDate,
                'notes' => 'For Advocate to be heard/Say',
                'district_id' => 'Malir',
                'court_id' => 'IIIRD ADJ Malir',
                'applicant' => 'S.M Rashid Hassan',
                'respondent' => 'Martynal Builders',
            ],

            [
                'title' => 'Syed Zohain Parvaiz vs Syed Zaid Ahmed',
                'case_number' => 'PPC 253/2024',
                'case_type' => 'PPC',
                'status' => 'In Progress',
                'hearing_date' => $hearingDate,
                'notes' => 'For Evidence as well as hearing on application',
                'district_id' => 'Special Courts',
                'court_id' => '1',
                'applicant' => 'Syed Zohain Parvaiz',
                'respondent' => 'Syed Zaid Ahmed',
            ],

            [
                'title' => 'Muhammad Younus vs The State',
                'case_number' => 'Spl. Case No. 234/2024',
                'case_type' => 'Special Case',
                'status' => 'In Progress',
                'hearing_date' => $hearingDate,
                'notes' => 'For Confirmation',
                'district_id' => 'Special Courts',
                'court_id' => 'Custom Court-II, S. No.11',
                'applicant' => 'Muhammad Younus',
                'respondent' => 'The State',
            ],
            [

                'title' => 'DJM Builders',
                'case_number' => 'Suit No. 1377/2013',
                'case_type' => 'Arbitration',
                'status' => 'In Progress',
                'hearing_date' => $hearingDate,
                'notes' => 'Arbitration Matter',
                'district_id' => 'Special Courts',
                'court_id' => 'Arbitration',
                'applicant' => '',
                'respondent' => '',
            ],

        ];

        // CourtCase::insert($cases);
        foreach ($cases as $case) {
            $district = District::where('name', $case['district_id'])->first();
            if (!District::where('name', $case['district_id'])->exists()) {
                $district = District::first();
            }
            $case['district_id'] = $district->id;

            $court = Court::where('name', $case['court_id'])->first();
            if (!Court::where('name', $case['court_id'])->exists()) {
                $court = Court::first();
            }

            $case['court_id'] = $court->id;

            CourtCase::create($case);
        }
    }
}
