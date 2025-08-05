<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Group;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\QueryException;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;

class GroupController extends Controller
{
    function __construct()
    {
        // $this->middleware('permission:group-list', ['only' => ['index']]);
        // $this->middleware('permission:group-show', ['only' => ['show']]);
        // $this->middleware('permission:group-create', ['only' => ['create', 'store']]);
        // $this->middleware('permission:group-edit', ['only' => ['edit', 'update']]);
        // $this->middleware('permission:group-delete', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $groups = Group::latest()
                ->get();

            return  DataTables::of($groups)
                ->addIndexColumn()
                ->addColumn('action', function ($group) {
                    $classes = 'fs-sm fw-semibold  py-1 px-3';

                    $actions = '<div class="dropdown">
                    <button type="button" class="btn btn-dark btn-sm dropdown-toggle" id="dropdown-default-alt-success" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                      Action
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdown-default-alt-success" style="position: absolute; inset: auto auto 0px 0px; margin: 0px; transform: translate(0px, -40px);" data-popper-placement="top-start" data-popper-reference-hidden="">';

                    if (Auth::user()->can('group-show'))
                        $actions .=
                            "<a class='dropdown-item' href=' " . route('groups.show', $group) . " '>
                            <span class='" . $classes . "'>
                                Show
                            </span>
                        </a>";

                    if (Auth::user()->can('group-edit'))
                        $actions .=
                            "<a class='dropdown-item' href=' " . route('groups.edit', $group) . " '>
                        <span class='" . $classes . "'>
                            Edit
                        </span>
                    </a>";

                    if (Auth::user()->can('group-delete'))
                        $actions .= "
                            <a class='dropdown-item'
                            href='#'
                            data-bs-toggle='modal'
                            data-bs-target='#deleteModal'
                            data-row-id='" . $group->id . "'>
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
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('groups.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $permissions = Permission::get()->where('permission_type', 'menu')->where('parent_id', NULL);
        return view('groups.create', compact('permissions'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        //write validation rules
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:groups,group_name',
            'parent_permission' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        } 

        $data = $request->all();
        try {
            // Assuming you have retrieved data from the form or any other source
            $data = [
                'name' => $data['name'],
                'parent_permission' => $data['parent_permission'], // Array of permission IDs
            ];

            // Create a new group
            $group = Group::create([
                'group_name' => $data['name'],
            ]);

            // Associate the group with permissions
            $group->permissions()->attach($data['parent_permission']);

            // Redirect or return a response
            return redirect()->route('groups.index')->with('alert-success', 'Group created Successfully.');
        } catch (QueryException $e) {
            // Handle the exception here, e.g., log the error or provide an error message
            return redirect()->back()->with('error', 'An error occurred while creating the group.');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $group = Group::findOrFail($id);
        $groupMenuPermissions = Permission::join("group_has_permissions", "group_has_permissions.permission_id", "=", "permissions.id")
            ->where("group_has_permissions.group_id", $id)
            ->get();
        return view('groups.show', compact('group', 'groupMenuPermissions'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $group = Group::findorFail($id);
        $permissions = Permission::get()->where('permission_type', 'menu')->where('parent_id', NULL);
        $groupMenuPermissions = DB::table("group_has_permissions")->where("group_has_permissions.group_id", $id)
            ->pluck('group_has_permissions.permission_id', 'group_has_permissions.permission_id')
            ->all();
        return view('groups.edit', compact('group', 'groupMenuPermissions', 'permissions'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $group = Group::findOrFail($id);
        
        //write validation rules
        $validator = Validator::make($request->all(), [
            'name'          => 'required|unique:groups,group_name,' . $group->id,
            'parent_permission' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        } 

        $data = $request->all();
        try {

            // Assuming you have retrieved data from the form or any other source
            $data = [
                'name' => $data['name'],
                'parent_permission' => $data['parent_permission'], // Array of permission IDs
            ];

            // Create a new group
            $group->group_name = $data['name'];
            $group->save();
            $group->permissions()->sync($data['parent_permission']);



            // $group = Group::find($id);
            // $old_permission = $group->permissions->pluck('id')->toArray();
            // $removed_permissions = array_diff($old_permission, $data['parent_permission']);
            // $roles = Role::select('id')->where('group_id', $id)->get();
            // $roleIds = $roles->pluck('id')->toArray();

            // if (!empty($removed_permissions)) {
            //     $permissionsInRoles = DB::table('role_has_permissions')
            //         ->whereIn('permission_id', $removed_permissions)
            //         ->whereIn('role_id', $roleIds)
            //         ->exists();
            //     if ($permissionsInRoles) {
            //         return redirect()->route('groups.index')
            //         ->with('alert-danger', 'Some permissions are still associated with roles you can not delete them.');
            //     }
            // }

            // $group->group_name = $data['name'];
            // $group->save();

            // $group->permissions()->sync($data['parent_permission']);

            return redirect()->route('groups.index')->with('alert-success', 'Group Updated Successfully.');
        } catch (QueryException $e) {
            return redirect()->back()->with('error', 'An error occurred while updating the group.');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $group = Group::findOrFail($id);
        if ($group->roles()->count() > 0) {
            return redirect()->route('groups.index')
                ->with('alert-danger', 'Group cannot be deleted as it has associated roles.');
        }

        $group->delete();

        return redirect()->route('groups.index')
            ->with('alert-success', 'Group deleted successfully.');
    }
}
