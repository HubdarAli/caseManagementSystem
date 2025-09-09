<?php

namespace App\Imports;

use App\Models\CourtCase;
use App\Models\Court;
use App\Models\District;
use Illuminate\Queue\Middleware\Skip;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToModel;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;
use Illuminate\Support\Str;

class CourtCaseImport implements  ToModel, WithStartRow , WithCalculatedFormulas
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {

        if (empty($row[0])) {
            return null; // Skip rows where the first column is empty
        }

        // If the row is an instance of \Maatwebsite\Excel\Row, get calculated values
        // Handle formulas in columns 2 (applicant), 3 (respondent), and 8 (districtName)
        $caseNumber = isset($row[0]) ? trim($row[0]) : null;
        $title = isset($row[1]) ? trim($row[1]) : ' ';
        $applicant = isset($row[2]) ? trim($row[2]) : ' ';
        $respondent = isset($row[3]) ? trim($row[3]) : ' ';
        $caseType = isset($row[4]) ? trim($row[4]) : null;
        $status = isset($row[5]) ? trim($row[5]) : "In Progress";
        $hearingDate = isset($row[6]) && !empty($row[6]) ? date('Y-m-d',strtotime(trim($row[6]))) : null;
        $notes = isset($row[7]) ? trim($row[7]) : null;
        $districtName = isset($row[8]) ? trim($row[8]) : '';
        $courtName = isset($row[9]) ? trim(explode('/', $row[9])[0]) : '';
        // Find or create district and court

        $district = null;
        $court = null;
        if(!empty($districtName)){

            $district = District::firstOrCreate(
                ['name' => $districtName],
                ['slug' => Str::slug($districtName)]
            );
            $court = Court::firstOrCreate(
                ['name' => $courtName, 'district_id' => $district->id],
                ['district_id' => $district->id]
            );
        }

        return new CourtCase([
            'case_number' => $caseNumber,
            'title' => $title,
            'applicant' => $applicant,
            'respondent' => $respondent,
            'case_type' => $caseType,
            'status' => $status,
            'hearing_date' => $hearingDate,
            'notes' => $notes,
            'district_id' => $district ? $district->id : null,
            'court_id' => $court ? $court->id : null,
            'user_id' => Auth::id(),
            'created_by' => Auth::id(),
            'is_migrated' => 1,
        ]);
    }

    public function startRow(): int
    {
        return 2; // Start from the second row to skip the header
    }
}
