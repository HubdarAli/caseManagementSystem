<?php

namespace App\Http\Controllers\Livelihood;

use App\Http\Controllers\Controller;
use App\Http\Requests\DistrictRequest;
use App\Models\District;
use App\Models\Taluka;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Validator;


class DistrictController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:district-list', ['only' => ['index']]);
        $this->middleware('permission:district-show', ['only' => ['show']]);
        $this->middleware('permission:district-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:district-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:district-delete', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {

        try {
            // $districts = District::latest()->get();
            // echo '<pre>'; print_r($districts->toArray()); echo '</pre>'; die('----CALL----');
            
            $user = Auth::user();
            // Check if the user has any assigned districts
            $districts = $user->districts()->latest()->get();
    
            //If the user has no assigned districts, fetch all districts
            if ($districts->isEmpty()) {
                $districts = District::latest()->get();
            }
            
            if ($request->ajax()) {

                //$districts = District::latest()->get();
                return DataTables::of($districts)
                    ->addIndexColumn()
                    ->addColumn('action', function ($district) {
                        $classes = 'fs-sm fw-semibold  py-1 px-3';
                        $actions = '<div class="dropdown">
                                <button type="button" class="btn btn-dark btn-sm dropdown-toggle" id="dropdown-default-alt-success" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                Action
                                </button>
                                <div class="dropdown-menu" aria-labelledby="dropdown-default-alt-success" style="position: absolute; inset: auto auto 0px 0px; margin: 0px; transform: translate(0px, -40px);" data-popper-placement="top-start" data-popper-reference-hidden="">';


                        if (Auth::user()->can('district-edit'))
                            $actions .=
                                "<a class='dropdown-item' href=' " . route('district.edit', $district) . " '>
                            <span class='" . $classes . "'>
                            
                                Edit
                            </span>
                        </a>";

                        if (Auth::user()->can('district-show'))
                        $actions .=
                            "<a class='dropdown-item' href=' " . route('district.show', $district) . " '>
                        <span class='" . $classes . "'>
                        
                            Show
                        </span>
                    </a>";


                        if (Auth::user()->can('district-delete'))
                            //     $actions .=
                            //         "<a class='dropdown-item' href=' " . route('district.delete', $district) . " '>
                            //     <span class='" . $classes . " text-danger'>
                            //         Delete
                            //     </span>
                            // </a>";
                            $actions .= "
                                <a class='dropdown-item'
                                href='#'
                                data-bs-toggle='modal'
                                data-bs-target='#deleteModal'
                                data-row-id='" . $district->id . "'>
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
                    ->addColumn('formatted_shortcode', function ($district) {
                        return $district->formatted_shortcode;
                    })
                    ->rawColumns(['action', 'formatted_shortcode'])
                    ->make(true);
            }
        } catch (Exception $e) {
            return response()->json(['error' => env('APP_ENV') == 'local' ? $e->getMessage() : 'Something went wrong']);
        }
        return view('district.index');
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        try {
            return view('district.create');
        } catch (Exception $e) {
            return back()->with('alert-danger', Env('APP_ENV') == 'local' ? $e->getMessage() : 'Something went wrong');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $request->validate([
            'name' => 'unique:districts,name',
        ]);
        // dd('After if');
        $district_code =   District::count();

        // $count = padAndIncrement($district_code);
        $count = check_district_code();


        $input = $request->all();
        $input['name'] = ucwords($request->name);

        $input['coordinates'] = json_encode([
            'latitude' => $input['latitude'],
            'longitude' => $input['longitude'],
        ]);

        if(empty($input['latitude']) || empty($input['longitude'])){
            unset($input['coordinates']);
        }

        $input['code'] = $count;
        $input['created_by'] = Auth::user()->id;

        try {
            District::create($input);
            return redirect('district')->with('alert-success', 'District has been added successfully!');
        } catch (Exception $e) {
            return back()->with('alert-danger', Env('APP_ENV') == 'local' ? $e->getMessage() : 'Something went wrong');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(District $district)
    {
        // $coordinates = json_decode($district->gps_coordinates, true);
   
        $coordinates = json_decode($district->coordinates, true);


        $district->latitude  = $coordinates['latitude'] ?? '';
        $district->longitude = $coordinates['longitude'] ?? '';

        try {
            return view('district.show', compact('district'));
        } catch (Exception $e) {
            return back()->with('alert-danger', Env('APP_ENV') == 'local' ? $e->getMessage() : 'Something went wrong');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(District $district)
    {
        // $coordinates = json_decode($district->gps_coordinates, true);
   
        $coordinates = json_decode($district->coordinates, true);


        $district->latitude  = $coordinates['latitude'] ?? '';
        $district->longitude = $coordinates['longitude'] ?? '';

        try {
            return view('district.edit', compact('district'));
        } catch (Exception $e) {
            return back()->with('alert-danger', Env('APP_ENV') == 'local' ? $e->getMessage() : 'Something went wrong');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(DistrictRequest $request, District $district)
    {
        $request->validate([
            'name' => 'unique:districts,name,' . $district->id,
        ]);
        $input = $request->all();
        $input['name'] = ucwords($request->name);

        $input['coordinates'] = json_encode([
            'latitude' => $input['latitude'],
            'longitude' => $input['longitude'],
        ]);

        if(empty($input['latitude']) || empty($input['longitude'])){
            unset($input['coordinates']);
        }

        $input['updated_by'] = Auth::user()->id;
        try {
            $district->update($input);
            return redirect('district')->with('alert-success', 'District has been updated successfully!');
        } catch (Exception $e) {
            // dd($e->getMessage());
            return back()->with('alert-danger', Env('APP_ENV') == 'local' ? $e->getMessage() : 'Something went wrong');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        try {
            $district = District::find($id);
            $taluka = Taluka::where('district_id', $id)->first();
            if ($taluka) {
                return back()->with('alert-danger', 'First Delete Taluka of this district');
            }
            $district->delete();
            $district->deleted_by = Auth::user()->id;
            $district->save();
            return redirect('district')->with('alert-success', 'District has been deleted successfully!');
        } catch (Exception $e) {
            return back()->with('alert-danger', Env('APP_ENV') == 'local' ? $e->getMessage() : 'Something went wrong');
        }
    }



    public function getTalukasByDistrict($district_id)
    {
        $talukas = Taluka::where('district_id', $district_id)->get();
        return response()->json($talukas);
    }
}
