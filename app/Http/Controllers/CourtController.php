<?php

namespace App\Http\Controllers;

use App\Models\Court;
use App\Models\District;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Validator;

class CourtController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            
            if ($request->ajax()) {

                $court = Court::latest()->get();
                return DataTables::of($court)
                    ->addIndexColumn()
                    ->editColumn('district_id', function ($court) {
                        return $court->district->name ?? 'N/A';
                    })
                    ->addColumn('action', function ($court) {
                        $classes = 'fs-sm fw-semibold  py-1 px-3';
                        $actions = '<div class="dropdown">
                                <button type="button" class="btn btn-dark btn-sm dropdown-toggle" id="dropdown-default-alt-success" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                Action
                                </button>
                                <div class="dropdown-menu" aria-labelledby="dropdown-default-alt-success" style="position: absolute; inset: auto auto 0px 0px; margin: 0px; transform: translate(0px, -40px);" data-popper-placement="top-start" data-popper-reference-hidden="">';


                        if (Auth::user()->can('court-edit'))
                            $actions .=
                                "<a class='dropdown-item' href=' " . route('court.edit', $court) . " '>
                            <span class='" . $classes . "'>
                            
                                Edit
                            </span>
                        </a>";

                        if (Auth::user()->can('court-show'))
                        $actions .=
                            "<a class='dropdown-item' href=' " . route('court.show', $court) . " '>
                        <span class='" . $classes . "'>
                        
                            Show
                        </span>
                    </a>";


                        if (Auth::user()->can('court-delete'))
                            //     $actions .=
                            //         "<a class='dropdown-item' href=' " . route('court.delete', $court) . " '>
                            //     <span class='" . $classes . " text-danger'>
                            //         Delete
                            //     </span>
                            // </a>";
                            $actions .= "
                                <a class='dropdown-item'
                                href='#'
                                data-bs-toggle='modal'
                                data-bs-target='#deleteModal'
                                data-row-id='" . $court->id . "'>
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
                    ->addColumn('formatted_shortcode', function ($court) {
                        return $court->formatted_shortcode;
                    })
                    ->rawColumns(['action', 'formatted_shortcode'])
                    ->make(true);
            }
        } catch (Exception $e) {
            return response()->json(['error' => env('APP_ENV') == 'local' ? $e->getMessage() : 'Something went wrong']);
        }
        return view('court.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //write logic to get districts for dropdown
        $districts = District::all();
        return view('court.create', compact('districts'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //write validation rules
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'district_id' => 'required|exists:districts,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }   

        try {
            $court = new Court();
            $court->name = $request->name;
            $court->district_id = $request->district_id;
            $court->save();

            return redirect()->route('court.index')->with('success', 'Court created successfully.');
        } catch (Exception $e) {
            return back()->with('alert-danger', env('APP_ENV') == 'local' ? $e->getMessage() : 'Something went wrong');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //write logic to show court details
        $court = Court::findOrFail($id);
        return view('court.show', compact('court'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //write logic to get court details for editing
        $court = Court::findOrFail($id);
        $districts = District::all();
        return view('court.edit', compact('court', 'districts'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //write validation rules
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'district_id' => 'required|exists:districts,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $court = Court::findOrFail($id);
            $court->name = $request->name;
            $court->district_id = $request->district_id;
            $court->save();

            return redirect()->route('court.index')->with('success', 'Court updated successfully.');
        } catch (Exception $e) {
            return back()->with('alert-danger', env('APP_ENV') == 'local' ? $e->getMessage() : 'Something went wrong');
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //write logic to delete court
        try {
            $court = Court::findOrFail($id);
            $court->delete();
            return redirect()->route('court.index')->with('success', 'Court deleted successfully.');
        } catch (Exception $e) {
            return back()->with('alert-danger', env('APP_ENV') == 'local' ? $e->getMessage() : 'Something went wrong');
        }
    }
}
