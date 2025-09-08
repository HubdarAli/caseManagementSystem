<?php

namespace App\Http\Controllers;

use App\Models\Court;
use App\Models\CourtCase;
use App\Models\District;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\CourtCaseImport;

class CourtCaseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //add logic to retrieve and display court cases
        try {

            if ($request->ajax()) {

                $cases = CourtCase::with(['user', 'district', 'court'])->latest()->get();
                return DataTables::of($cases)
                    ->addIndexColumn()
                    ->editColumn('district_id', function ($cases) {
                        return $cases->district->name ?? 'N/A';
                    })
                    ->editColumn('hearing_date', function ($cases) {
                        if (empty($cases->hearing_date) || \Carbon\Carbon::parse($cases->hearing_date)->lt(now())) {
                            return '<span class="badge bg-danger">Not Set</span>';
                        }
                        return \Carbon\Carbon::parse($cases->hearing_date)->format('d M, Y');
                    })
                    ->editColumn('status', function ($cases) {
                        
                        //status
                        $statusClasses = [
                            'Open' => 'badge bg-warning',
                            'In Progress' => 'badge bg-info',
                            // 'Resolved' => 'badge bg-success',
                            'Closed' => 'badge bg-success',
                        ];
                        $statusClass = $statusClasses[$cases->status] ?? 'badge bg-secondary';
                        return '<span class="' . $statusClass . '">' . $cases->status . '</span>';
                    })
                    ->addColumn('action', function ($cases) {
                        $classes = 'fs-sm fw-semibold  py-1 px-3';
                        $actions = '<div class="dropdown">
                                <button type="button" class="btn btn-dark btn-sm dropdown-toggle" id="dropdown-default-alt-success" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                Action
                                </button>
                                <div class="dropdown-menu" aria-labelledby="dropdown-default-alt-success" style="position: absolute; inset: auto auto 0px 0px; margin: 0px; transform: translate(0px, -40px);" data-popper-placement="top-start" data-popper-reference-hidden="">';


                        if (Auth::user()->can('court-case-edit'))
                            $actions .=
                                "<a class='dropdown-item' href=' " . route('courts-cases.edit', $cases) . " '>
                            <span class='" . $classes . "'>
                            
                                Edit
                            </span>
                        </a>";

                        if (Auth::user()->can('court-case-show'))
                            $actions .=
                                "<a class='dropdown-item' href=' " . route('courts-cases.show', $cases) . " '>
                        <span class='" . $classes . "'>
                        
                            Show
                        </span>
                    </a>";


                        if (Auth::user()->can('court-case-delete'))
                            //     $actions .=
                            //         "<a class='dropdown-item' href=' " . route('courts-cases.delete', $cases) . " '>
                            //     <span class='" . $classes . " text-danger'>
                            //         Delete
                            //     </span>
                            // </a>";
                            $actions .= "
                                <a class='dropdown-item'
                                href='#'
                                data-bs-toggle='modal'
                                data-bs-target='#deleteModal'
                                data-row-id='" . $cases->id . "'>
                                    <span class='" . $classes . " text-danger'>
                                        Delete
                                    </span>
                                </a>
                                ";
                        $actions .= '
                                </div>
                            </div>';
                        return $actions;
                    })
                    ->rawColumns(['action', 'status','hearing_date'])
                    ->make(true);
            }
        } catch (Exception $e) {
            return response()->json(['error' => env('APP_ENV') == 'local' ? $e->getMessage() : 'Something went wrong']);
        }
        return view('court_cases.index'); // Ensure this view exists
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $districts = District::all();
        $courts = Court::all();
        return view('court_cases.create', compact('districts', 'courts'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'case_number' => 'required|string|max:255',
            'case_type' => 'required|string|max:255',
            'status' => 'required|string|max:255',
            'hearing_date' => 'nullable|date',
            'notes' => 'nullable|string',
            'district_id' => 'required|exists:districts,id',
            'court_id' => 'required|exists:courts,id',
        ]);

        $validated['user_id'] = Auth::id();
        $validated['created_by'] = Auth::id();

        CourtCase::create($validated);

        return redirect()->route('courts-cases.index')->with('alert-success', 'Court Case created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($court_case)
    {
        $court_case = CourtCase::findOrFail($court_case);
        return view('court_cases.show', compact('court_case'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($court_case)
    {
        $court_case = CourtCase::findOrFail($court_case);
        $districts = District::all();
        $courts = Court::all();
        return view('court_cases.edit', compact('court_case', 'districts', 'courts'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $court_case)
    {
        $court_case = CourtCase::findOrFail($court_case);
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'case_number' => 'required|string|max:255',
            'case_type' => 'required|string|max:255',
            'status' => 'required|string|max:255',
            'hearing_date' => 'nullable|date',
            'notes' => 'nullable|string',
            'district_id' => 'required|exists:districts,id',
            'court_id' => 'required|exists:courts,id',
        ]);

        try {
            $validated['updated_by'] = Auth::id();

            $court_case->update($validated);
        } catch (Exception $e) {
            // dd($e);
            return back()->with('alert-danger', Env('APP_ENV') == 'local' ? $e->getMessage() : 'Something went wrong');
        }

        return redirect()->route('courts-cases.index')->with('alert-success', 'Court Case updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CourtCase $court_case)
    {
        $court_case->deleted_by = Auth::id();
        $court_case->save();
        $court_case->delete();

        return redirect()->route('courts-cases.index')->with('alert-success', 'Court Case deleted successfully.');
    }

    public function generatePdf(Request $request)
    {
        // $courtCases = CourtCase::with(['court', 'district'])->get();

        $from = $request->input('from_date');
        $to   = $request->input('to_date');

        $courtCases = CourtCase::with(['court', 'district'])
            ->when($from, fn($q) => $q->whereDate('hearing_date', '=', $from))
            ->when($to, fn($q) => $q->whereDate('hearing_date', '<=', $to))
            ->get();

        // Grouping by region (based on court name or region column)
        $groupedCases = $courtCases->groupBy(function ($case) {
            return strtoupper($case->court->district->name ?? 'Other'); // or a `region` column
        });

        // return View('court_cases.pdf', compact('groupedCases','from','to'));
        $pdf = Pdf::loadView('court_cases.pdf', compact('groupedCases','from','to'))->setPaper('a4');

        return $pdf->download('court-cases.pdf');
    }

    public function importForm()
    {
        return view('court_cases.import');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);

        try {
            Excel::import(new CourtCaseImport, $request->file('file'));
            return redirect()->route('courts-cases.index')->with('alert-success', 'Court Cases imported successfully.');
        } catch (Exception $e) {
            return back()->with('alert-danger', env('APP_ENV') == 'local' ? $e->getMessage() : 'Import failed');
        }
    }
}
