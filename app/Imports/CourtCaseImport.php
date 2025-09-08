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

class CourtCaseImport implements  ToModel, WithStartRow , WithCalculatedFormulas
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {

        dd($row);
        // If the row is an instance of \Maatwebsite\Excel\Row, get calculated values
        // Handle formulas in columns 2 (applicant), 3 (respondent), and 8 (districtName)
        $caseNumber = $row[0];
        $title = $row[1];

        $applicant = (isset($row[2]) && is_object($row[2]) && $row[2] instanceof Cell && $row[2]->isFormula()) ? $row[2]->getCalculatedValue() : ($row[2] ?? null);
        $respondent = (isset($row[3]) && is_object($row[3]) && $row[3] instanceof Cell && $row[3]->isFormula()) ? $row[3]->getCalculatedValue() : ($row[3] ?? null);

        $caseType = $row[4];
        $status = $row[5] ?? 'In Progress';
        $hearingDate = $row[6] ?? null;
        $notes = $row[7] ?? null;

        $districtName = (isset($row[8]) && $row[8] instanceof Cell && $row[8]->isFormula()) ? $row[8]->getCalculatedValue() : ($row[8] ?? null);
        $courtName = $row[9];

        dd($applicant, $respondent, $districtName);
        $district = District::firstOrCreate(
            ['name' => $districtName],
            ['slug' => \Str::slug($districtName)]
        );
        $court = Court::firstOrCreate(
            ['name' => $courtName, 'district_id' => $district->id],
            ['district_id' => $district->id]
        );

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
        ]);
    }

    public function startRow(): int
    {
        return 2; // Start from the second row to skip the header
    }
}
