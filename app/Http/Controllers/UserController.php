<?php

namespace App\Http\Controllers;

use App\Exports\UsersExport;
use App\Models\Smp;
use App\Models\User;
use App\Models\Group;
use App\Models\Taluka;
use App\Models\District;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:user-list', ['only' => ['index']]);
        $this->middleware('permission:user-show', ['only' => ['show']]);
        $this->middleware('permission:user-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:user-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:user-delete', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {

        if ($request->ajax()) {

            if (auth()->user()->group_id == config('global.allowed_groups')['admin']) {
                $users = User::with(['group'])->latest()
                    ->where('id', '!=', Auth::user()->id);
                  //  ->get();
            } else {

                $users = User::with(['group', 'roles', 'smp','districts','talukas'])
                    ->latest()
                    ->where('group_id', '=', auth()->user()->group_id)
                    ->where('smp_id', '=', auth()->user()->smp_id)
                    ->whereHas('roles', function ($query) {
                        if (strtolower(auth()->user()->getRoleNames()[0]) == strtolower(config('global.smp_roles.1')))
                            $query->where('name', '!=', strtolower(config('global.smp_roles.1')));
                        else if (strtolower(auth()->user()->getRoleNames()[0]) == strtolower(config('global.smp_roles.3'))) {
                            $query->where('name', '!=', strtolower(config('global.smp_roles.1')));
                            $query->where('name', '!=', strtolower(config('global.smp_roles.3')));
                        }
                    })
                    ->where('id', '!=', auth()->user()->id);
                    // ->where(function ($query) {
                    //     $query->whereHas('districts', function ($subQuery) {
                    //         $subQuery->whereIn('district_id', auth()->user()->districts->pluck('id'));
                    //     });
                    // })
                  //  ->get();
            }

            if (!empty($request->smp_id)) {
                $users->where('smp_id',$request->smp_id);
            }

            if (!empty($request->group_id)) {

                $users->where('group_id',$request->group_id);
            }

            $users =  $users->get();

            return  DataTables::of($users)
                ->addIndexColumn()
                ->editColumn('roles', function ($user) {
                    $class = "fs-xs fw-semibold d-inline-block py-1 px-3 rounded-pill bg-success-light text-success";

                    $userRoles = $user->getRoleNames();

                    $roles = '';

                    foreach ($userRoles ?? [] as $userRole) {
                        $roles .= "
                        <span
                        class='$class'>
                            $userRole
                        </span>";
                    }

                    return $roles;
                })
                ->editColumn('group', function ($user) {
                    $class = "fs-xs fw-semibold d-inline-block py-1 px-3 rounded-pill bg-info-light text-info";
                    $groupName = isset($user->group->group_name) ? $user->group->group_name : '-';

                    return "<span
                        class='$class'>
                            $groupName
                        </span>";
                })
                ->editColumn('district', function ($user) {
                    $classes = 'class="fs-xs fw-semibold d-inline-block py-1 px-3 rounded-pill bg-danger-light text-danger"';
                    $districts = $user->districts?->pluck('name');
                    $pills = '<span ' . $classes . '>Null</span>';
                    if (!empty($districts)) {
                        $pills = '';
                        foreach ($districts as $row) {
                            $pills .= '<span ' . $classes . '>' . $row . '</span>';
                        }
                    }
                    return $pills;

                })
                ->editColumn('taluka', function ($user) {
                    $classes = 'class="fs-xs fw-semibold d-inline-block py-1 px-3 rounded-pill bg-warning-light text-warning"';
                    $talukas = $user->talukas?->pluck('name');
                    $pills = '<span ' . $classes . '>Null</span>';
                    if (!empty($talukas)) {
                        $pills = '';
                        foreach ($talukas as $row) {
                            $pills .= '<span ' . $classes . '>' . $row . '</span>';
                        }
                    }
                    return $pills;

                })
                ->editColumn('smp_name', function ($user) {
                    $talukaName = isset($user->smp->name) ? $user->smp->name : '-';
                    return $talukaName;

                })
                ->editColumn('is_active', function ($user) {
                    if ($user->is_active) {
                        $classes = 'class="fs-xs fw-semibold d-inline-block py-1 px-3 rounded-pill bg-success-light text-success"';
                        $status = '<span ' . $classes . '>Active</span>';
                    } else {
                        $classes = 'class="fs-xs fw-semibold d-inline-block py-1 px-3 rounded-pill bg-danger-light text-danger"';
                        $status = '<span ' . $classes . '>Inactive</span>';
                    }

                    return $status;
                })
                ->addColumn('action', function ($user) {
                    $classes = 'fs-sm fw-semibold  py-1 px-3';

                    $actions = '<div class="dropdown">
                    <button type="button" class="btn btn-dark btn-sm dropdown-toggle" id="dropdown-default-alt-success" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                      Action
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdown-default-alt-success" style="position: absolute; inset: auto auto 0px 0px; margin: 0px; transform: translate(0px, -40px);" data-popper-placement="top-start" data-popper-reference-hidden="">';

                    if (Auth::user()->can('user-show'))
                        $actions .=
                            "<a class='dropdown-item' href=' " . route('users.show', $user) . " '>
                            <span class='" . $classes . "'>
                                Show
                            </span>
                        </a>";

                    if (Auth::user()->can('user-edit'))
                        $actions .=
                            "<a class='dropdown-item' href=' " . route('users.edit', $user) . " '>
                        <span class='" . $classes . "'>
                            Edit
                        </span>
                    </a>";

                    if (Auth::user()->can('user-delete'))
                        $actions .= "
                            <a class='dropdown-item'
                            href='#'
                            data-bs-toggle='modal'
                            data-bs-target='#deleteModal'
                            data-row-id='" . $user->id . "'>
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
                ->rawColumns(['action', 'roles', 'group','district','taluka', 'is_active'])
                ->make(true);
        }

        $user = Auth::user();

        if ($user->smp_id!=null) {

            $smps = Smp::where('id', $user->smp_id)->get();
        } else {
            $smps = Smp::all();
        }
        if ($user->smp_id!=null) {
            $group = Group::where('id',$user->group_id)->get();

        } else {
            $group = Group::all();
        }

        return view('users.index',compact('smps','group'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $smps = Smp::select('id', 'name')->get();

        if (auth()->user()->group_id == config('global.allowed_groups')['admin']) {
            $smps = Smp::select('id', 'name')->get();
        } else {
            $smps = Smp::select('id', 'name')->where('id', '=', auth()->user()->smp_id)->get();
        }

        if (auth()->user()->group_id == config('global.allowed_groups')['admin']) {
            $groups = Group::select('id', 'group_name')->get();
        } else {
            $groups = Group::select('id', 'group_name')->where('id', '=', auth()->user()->group_id)->get();
        }
        $districts = District::select('id', 'name')->get();

        return view('users.create', compact('groups', 'districts', 'smps'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required',
            'confirm_password' => 'required|same:password',
            'groups' => 'required',
            'roles' => 'required'
        ]);

        $input = $request->all();
        $input['password'] = Hash::make($input['password']);

        // Create the user

        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'smp_id' => $request->input('smp_id'),
            'group_id' => $request->input('groups'),
            'password' => $input['password'],
            'is_active' => $request->input('is_active') ?? 1,
        ]);

        // Assign roles
        $user->assignRole($request->input('roles'));

        // Handle district and taluka associations
        if ($request->filled('district_id')) {
            $user->districts()->attach($request->input('district_id'));

            // If talukas are provided, associate them
            if ($request->filled('taluka_id')) {
                $user->talukas()->attach($request->input('taluka_id'));
            }
        }

        // Handle groups association
        // $user->groups()->attach($request->input('groups'));

        return redirect()->route('users.index')
            ->with('alert-success', 'User created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        if (auth()->user()->group_id == config('global.allowed_groups')['admin']) {
            $groups = Group::select('id', 'group_name')->get();
        } else {
            $groups = Group::select('id', 'group_name')->where('id', '=', auth()->user()->group_id)->get();
        }

        if (auth()->user()->group_id == config('global.allowed_groups')['admin']) {
            $smps = Smp::select('id', 'name')->get();
        } else {
            $smps = Smp::select('id', 'name')->where('id', '=', auth()->user()->smp_id)->get();
        }

        $districts = DB::table('smp_district')
            ->join('districts', 'smp_district.district_id', '=', 'districts.id')
            ->where('smp_district.smp_id', $user->smp_id)
            ->select('smp_district.district_id', 'districts.name')
            ->get();

        if(auth()->user()->getRoleNames()[0] == 'Admin'){
            $roles = Role::select(['id', 'name'])
                ->where('group_id', $user->group_id)
                ->get();
        } else {
            $roles = Role::select(['id', 'name'])
                ->where('group_id', $user->group_id)
                ->whereNot('name', auth()->user()->getRoleNames()[0])
                ->get();
        }


        if (Auth::user()->group['group_name'] == 'SMP') {
            if (strtolower(auth()->user()->getRoleNames()[0]) == strtolower(config('global.smp_roles.3'))) {
                $roles = Role::select(['id', 'name'])
                    ->where('group_id', $user->group_id)
                    ->where('name', '!=', strtolower(config('global.smp_roles.3')))
                    ->where('name', '!=', strtolower(config('global.smp_roles.1')))
                    ->get();
            } else {
                $roles = Role::select(['id', 'name'])
                    ->where('group_id', $user->group_id)
                    ->where('name', '!=', strtolower(config('global.smp_roles.1')))
                    ->get();
            }
        }

        return view('users.show', compact('groups', 'user', 'roles', 'smps', 'districts'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        if (auth()->user()->group_id == config('global.allowed_groups')['admin']) {
            $groups = Group::select('id', 'group_name')->get();
        } else {
            $groups = Group::select('id', 'group_name')->where('id', '=', auth()->user()->group_id)->get();
        }

        if (auth()->user()->group_id == config('global.allowed_groups')['admin']) {
            $smps = Smp::select('id', 'name')->get();
        } else {
            $smps = Smp::select('id', 'name')->where('id', '=', auth()->user()->smp_id)->get();
        }

        $districts = DB::table('smp_district')
            ->join('districts', 'smp_district.district_id', '=', 'districts.id')
            ->where('smp_district.smp_id', $user->smp_id)
            ->select('smp_district.district_id', 'districts.name')
            ->get();

        if(auth()->user()->getRoleNames()[0] == 'Admin'){
            $roles = Role::select(['id', 'name'])
                ->where('group_id', $user->group_id)
                ->get();
        } else {
            $roles = Role::select(['id', 'name'])
                ->where('group_id', $user->group_id)
                ->whereNot('name', auth()->user()->getRoleNames()[0])
                ->get();
        }


        if (Auth::user()->group['group_name'] == 'SMP') {
            if (strtolower(auth()->user()->getRoleNames()[0]) == strtolower(config('global.smp_roles.3'))) {
                $roles = Role::select(['id', 'name'])
                    ->where('group_id', $user->group_id)
                    ->where('name', '!=', strtolower(config('global.smp_roles.3')))
                    ->where('name', '!=', strtolower(config('global.smp_roles.1')))
                    ->get();
            } else {
                $roles = Role::select(['id', 'name'])
                    ->where('group_id', $user->group_id)
                    ->where('name', '!=', strtolower(config('global.smp_roles.1')))
                    ->get();
            }
        }

        return view('users.edit', compact('groups', 'user', 'roles', 'smps', 'districts'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $rules = [
            'name'   => 'required',
            'email'  => 'required|email|unique:users,email,' . $id,
            'groups' => 'required',
            'roles'  => 'required'
        ];

        if ($request->has('password') && $request->input('password')) {
            $rules['password'] = 'required';
        }

        $this->validate($request, $rules);

        $input = $request->all();

        if (!(Group::find($request->groups)?->group_name == 'SMP')) {
            $input['smp_id'] = NULL;
        };

        if ($request->has('password') && $request->input('password')) {
            $input['password'] = Hash::make($input['password']);
        } else {
            $input['password'] = $user->password;
        }

        $user->update([
            'name'      => $input['name'],
            'group_id'  => $input['groups'],
            'email'     => $input['email'],
            'password'  => $input['password'],
            'smp_id'    => $input['smp_id'] ?? null,
            'is_active' => $request->input('is_active') ?? 1,
        ]);

        $user->syncRoles($input['roles']);

        // Update associated districts
        if ($request->filled('district_id')) {
            $user->districts()->sync($request->input('district_id'));
        } else {
            // Detach all districts if none are selected
            $user->districts()->detach();
        }

        // Update associated talukas
        if ($request->filled('taluka_id')) {
            $user->talukas()->sync($request->input('taluka_id'));
        } else {
            // Detach all talukas if none are selected
            $user->talukas()->detach();
        }

        return redirect()->route('users.index')
            ->with('alert-success', 'User updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        User::find($id)->delete();
        return redirect()->route('users.index')
            ->with('alert-success', 'User deleted successfully');
    }

    public function get_talukas(Request $request)
    {
        // $talukas = Taluka::select('id', 'name','code')->where('district_id', $request->district_id)->get();
        // $user_talukas = DB::table('user_taluka')
        //     ->select('user_id', 'taluka_id')
        //     ->where('user_id', $request->user_id)
        //     ->get()
        //     ->toArray();
        $user = Auth::user();
        $talukas = [];

        if ($user->talukas->isNotEmpty()) {
            $talukas = $user->talukas->where('district_id', $request->district_id)->all();
        } else {
            $talukas = Taluka::where('district_id', $request->district_id)
                ->orderBy('name', 'asc')
                ->get()
                ->all();
        }

        return response()->json($talukas);
    }
    // public function get_talukas(Request $request)
    // {
    //     $talukas = Taluka::select('id', 'name')->where('district_id', $request->district_id)->get();
    //     $user_talukas = DB::table('user_taluka')
    //         ->select('user_id', 'taluka_id')
    //         ->where('user_id', $request->user_id)
    //         ->get()
    //         ->toArray();

    //     $checkedTalukaIds = array_column($user_talukas, 'taluka_id');

    //     foreach ($talukas as $taluka) {
    //         $taluka->checked = in_array($taluka->id, $checkedTalukaIds) ? 'selected' : false;
    //     }
    //     return response()->json($talukas);
    // }
    public function get_districts()
    {
        $user = Auth::user();
        $districts = [];

        if ($user->districts->isNotEmpty()) {
            $districts = $user->districts;
        } else {
            $districts = District::all(['id', 'name']);
        }
        return response()->json($districts);
    }
    public function get_smp_districts()
    {
        $user = Auth::user();
        $districts = [];

        if ($user->districts->isNotEmpty()) {
            $districts = $user->districts;
        } else {
            $userDistrictIds = DB::table('user_district')->pluck('district_id')->all();
            $districts = DB::table('districts')
                ->whereNotIn('id', $userDistrictIds)
                ->get(['id', 'name']);
        }
        return response()->json($districts);
    }
    public function get_roles(Request $request)
    {
        $roles = Role::select('id', 'name')->where('group_id', $request->group_id)->get();
        return response()->json($roles);
    }

    function get_district_smp(Request $request)
    {

        $smpDistricts = District::all();
        $user = Auth::user();
        // if ($request->has('smp_id') && !empty($request->smp_id)) {
         if (!$user->districts->isNotEmpty()) {

            $smpDistricts = District::join('smp_district', 'smp_district.district_id', '=', 'districts.id')
                ->select('smp_district.district_id as id', 'districts.name')
                ->where('smp_district.smp_id', $request->smp_id)
                ->get();

            if (strtolower(auth()->user()->getRoleNames()[0]) == strtolower(config('global.smp_roles.3'))) {
                $smpDistricts = auth()->user()->districts;
            }

            if (
                strtolower(auth()->user()->getRoleNames()[0]) == strtolower(config('global.smp_roles.1')) ||
                strtolower(auth()->user()->getRoleNames()[0]) == 'admin'
            ) {
                $smpDistricts = District::join('smp_district', 'smp_district.district_id', '=', 'districts.id')
                ->select('smp_district.district_id as id', 'districts.name')
                ->where('smp_district.smp_id', $request->smp_id)
                ->get();
            }
        }elseif ($user->districts->isNotEmpty()) {
            $smpDistricts = auth()->user()->districts;
        }else{
            $smpDistricts = District::has('smp')->get();

        }

        return response()->json($smpDistricts);
    }


    function get_taluka_smp(Request $request)
    {
        $userTalukasIds = DB::table('smp_taluka')
            ->where('smp_id', $request->smp_id)
            ->pluck('taluka_id')
            ->all();
        $talukas = DB::table('talukas')
            ->where('district_id', $request->district_id)
            ->whereIn('id', $userTalukasIds)
            ->get(['id', 'name']);
        return response()->json($talukas);
    }

    function export(Request $request)
    {
        $smp_id = $request->input('smp_id');
        $group_id = $request->input('group_id');

        return Excel::download(new UsersExport($smp_id,$group_id), 'users.xlsx');
    }




    public function UserStatusUpdate(Request $request)
{
    $request->validate([
        'ids' => 'required|array',
        'ids.*' => 'exists:users,id',
        'status' => 'required|in:active,inactive',
    ]);

    $status = ($request->status == 'active') ? 1 : 0;

    User::whereIn('id', $request->ids)->update(['is_active' => $status]);

   // $usersAfterUpdate = User::whereIn('id', $request->ids)->get();

    return response()->json([
                'message' => 'Status updated successfully for selected users.',
    ]);
}


}
