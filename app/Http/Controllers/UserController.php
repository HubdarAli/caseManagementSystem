<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Group;
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
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function __construct()
    {
        // $this->middleware('permission:user-list', ['only' => ['index']]);
        // $this->middleware('permission:user-show', ['only' => ['show']]);
        // $this->middleware('permission:user-create', ['only' => ['create', 'store']]);
        // $this->middleware('permission:user-edit', ['only' => ['edit', 'update']]);
        // $this->middleware('permission:user-delete', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {

        if ($request->ajax()) {



            $users = User::with(['group', 'roles'])
                ->latest()
                // ->where('group_id', '=', auth()->user()->group_id)
                ->where('id', '!=', auth()->user()->id);

            if (!empty($request->group_id)) {

                $users->where('group_id', $request->group_id);
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
                ->rawColumns(['action', 'roles', 'group', 'is_active'])
                ->make(true);
        }

        $user = Auth::user();


        $group = Group::all();

        return view('users.index', compact('group'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $groups = Group::select('id', 'group_name')->get(); //->where('id', '=', auth()->user()->group_id)
        return view('users.create', compact('groups'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required',
                'email' => 'required|email|unique:users,email',
                'password' => 'required',
                'confirm_password' => 'required|same:password',
                'groups' => 'required',
                'roles' => 'required'
            ]
        );

        $input = $request->all();
        $input['password'] = Hash::make($input['password']);

        // Create the user

        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'group_id' => $request->input('groups'),
            'password' => $input['password'],
            'is_active' => $request->input('is_active') ?? 1,
        ]);

        // Assign roles
        // $user->assignRole($request->input('roles'));
        $roleIds = $request->input('roles');

        // If multiple roles selected (array), fetch their names
        $roleNames = Role::whereIn('id', (array) $roleIds)->pluck('name')->toArray();

        $user->assignRole($roleNames);

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

        $groups = Group::select('id', 'group_name')->where('id', '=', auth()->user()->group_id)->get();




        if (auth()->user()->getRoleNames()[0] == 'Admin') {
            $roles = Role::select(['id', 'name'])
                ->where('group_id', $user->group_id)
                ->get();
        } else {
            $roles = Role::select(['id', 'name'])
                ->where('group_id', $user->group_id)
                ->whereNot('name', auth()->user()->getRoleNames()[0])
                ->get();
        }




        return view('users.show', compact('groups', 'user', 'roles'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {

        $groups = Group::select('id', 'group_name')->where('id', '=', auth()->user()->group_id)->get();




        if (auth()->user()->getRoleNames()[0] == 'Admin') {
            $roles = Role::select(['id', 'name'])
                ->where('group_id', $user->group_id)
                ->get();
        } else {
            $roles = Role::select(['id', 'name'])
                ->where('group_id', $user->group_id)
                ->whereNot('name', auth()->user()->getRoleNames()[0])
                ->get();
        }


        return view('users.edit', compact('groups', 'user', 'roles'));
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
            'is_active' => $request->input('is_active') ?? 1,
        ]);

        $user->syncRoles($input['roles']);


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

    public function get_roles(Request $request)
    {
        $roles = Role::select('id', 'name')->where('group_id', $request->group_id)->get();
        return response()->json($roles);
    }


    function export(Request $request)
    {
        $smp_id = $request->input('smp_id');
        $group_id = $request->input('group_id');

        return Excel::download(new UsersExport($smp_id, $group_id), 'users.xlsx');
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
