<?php

namespace App\Http\Controllers;


use App\Models\Role;
use App\Models\Group;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Permission;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\RolesExport;
use App\Models\ExportColumnPermissions;
use Illuminate\Support\Facades\Validator;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        // $this->middleware('permission:role-list', ['only' => ['index']]);
        // $this->middleware('permission:role-show', ['only' => ['show']]);
        // $this->middleware('permission:role-create', ['only' => ['create', 'store']]);
        // $this->middleware('permission:role-edit', ['only' => ['edit', 'update']]);
        // $this->middleware('permission:role-delete', ['only' => ['destroy']]);
        // $this->middleware('permission:role-manage-export-columns', ['only' => ['manageExportColumns', 'RoleExportSave']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $roles = Role::latest()
                ->get();

            return  DataTables::of($roles)
                ->addIndexColumn()
                ->addColumn('group', function ($role) {
                    $class = "fs-xs fw-semibold d-inline-block py-1 px-3 rounded-pill bg-success-light text-success";
                    $groupName = isset($role->group->group_name) ? $role->group->group_name : '-';

                    return "
                        <span
                        class='$class'>
                            $groupName
                        </span>";
                })
                ->editColumn('name', function ($role) {
                    return "<span style='white-space: normal; word-wrap: break-word; display: block; max-width: 200px;'>
                                $role->name
                            </span>";
                })
                ->addColumn('action', function ($role) {
                    $classes = 'fs-sm fw-semibold  py-1 px-3';

                    $actions = '<div class="dropdown">
                    <button type="button" class="btn btn-dark btn-sm dropdown-toggle" id="dropdown-default-alt-success" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                      Action
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdown-default-alt-success" style="position: absolute; inset: auto auto 0px 0px; margin: 0px; transform: translate(0px, -40px);" data-popper-placement="top-start" data-popper-reference-hidden="">';

                    if (Auth::user()->can('role-show'))
                        $actions .=
                            "<a class='dropdown-item' href=' " . route('roles.show', $role) . " '>
                            <span class='" . $classes . "'>
                                Show
                            </span>
                        </a>";

                    if (Auth::user()->can('role-edit'))
                        $actions .=
                            "<a class='dropdown-item' href=' " . route('roles.edit', $role) . " '>
                        <span class='" . $classes . "'>
                            Edit
                        </span>
                    </a>";

                    if (Auth::user()->can('role-delete'))
                        $actions .= "
                            <a class='dropdown-item'
                            href='#'
                            data-bs-toggle='modal'
                            data-bs-target='#deleteModal'
                            data-row-id='" . $role->id . "'>
                            <span class='" . $classes . " text-danger'>
                                Delete
                                </span>
                            </a>
                            ";


                    return $actions;
                })
                ->rawColumns(['action', 'group', 'name'])
                ->make(true);
        }

        return view('roles.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $groups = Group::get();
        return view('roles.create', compact('groups'));
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
                'name' => 'required|unique:roles,name',
                'group_id' => 'required',
                'permission' => 'required',
            ],
            [
                'name.required' => 'Enter Role Name',
                'group_id.required' => 'Please Select Group',
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $role = Role::create([
            'name' => $request->input('name'),
            'group_id' => $request->input('group_id')
        ]);

        $permissionIds = $request->input('permission');
        $permissionNames = Permission::whereIn('id', $permissionIds)->pluck('name')->toArray();

        $role->syncPermissions($permissionNames);

        return redirect()->route('roles.index')
            ->with('alert-success', 'Role created successfully');
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Role $role)
    {
        $groups = Group::get();
        $rolePermissions = Permission::join("role_has_permissions", "role_has_permissions.permission_id", "=", "permissions.id")
            ->where("role_has_permissions.role_id", $role->id)
            ->get();
        return view('roles.show', compact('role', 'groups', 'rolePermissions'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Role $role)
    {
        $groups = Group::get();
        $rolePermissions = Permission::join("role_has_permissions", "role_has_permissions.permission_id", "=", "permissions.id")
            ->where("role_has_permissions.role_id", $role->id)
            ->get();
        return view('roles.edit', compact('role', 'groups', 'rolePermissions'));
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
        $role = Role::find($id);
        $validator = Validator::make(
            $request->all(),
            [
                'name'          => 'required|unique:roles,name,' . $role->id,
                'group_id'      => 'required',
                'permission'    => 'required',
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $role->name     = $request->input('name');
        $role->group_id = $request->input('group_id');
        $role->save();

        $permissionIds = $request->input('permission');
        $permissionNames = Permission::whereIn('id', $permissionIds)->pluck('name')->toArray();

        $role->syncPermissions($permissionNames);

        return redirect()->route('roles.index')
            ->with('alert-success', 'Role updated successfully');
    }

    public function destroy($id)
    {
        $role = Role::findOrFail($id);

        if ($role->users()->count() > 0) {
            return redirect()->route('roles.index')
                ->with('alert-danger', 'Role cannot be deleted as it is assigned to one or more users.');
        }

        $role->delete();

        return redirect()->route('roles.index')
            ->with('alert-success', 'Role deleted successfully.');
    }

    public function get_permission_with_group_id(Request $request)
    {
        $group_id = $request->input('group_id');
        $role_id = $request->input('role_id');
        $group = Group::findOrFail($group_id);
        $permissionsData = $group->parent_sub_permissions_by_group_id($group_id);
        $role_permissions = Permission::select('id', 'role_id')
            ->join("role_has_permissions", "role_has_permissions.permission_id", "=", "permissions.id")
            ->where("role_has_permissions.role_id", $role_id)
            ->get()
            ->toArray();

        $result = [];

        foreach ($permissionsData as $key => $parentPermission) {
            $parentData = [
                'id'        => $parentPermission['id'],
                'name'      => $parentPermission['name'],
                'parent_id' => $parentPermission['parent_id'],
                // 'component' => $parentPermission['component'],
                'subMenus'  => [],
                'checked'   => in_array($parentPermission['id'], array_column($role_permissions, 'id')),
            ];

            foreach ($parentPermission['subMenus'] as $midLevelPermission) {
                $midLevelData = [
                    'id'              => $midLevelPermission['id'],
                    'name'            => $midLevelPermission['name'],
                    'parent_id'       => $midLevelPermission['parent_id'],
                    // 'component'       => $midLevelPermission['component'],
                    'permission_type' => $midLevelPermission['permission_type'],
                    'subMenus'        => [],
                    'checked'         => in_array($midLevelPermission['id'], array_column($role_permissions, 'id')),
                ];

                foreach ($midLevelPermission['subMenus'] as $childPermission) {
                    $childData = [
                        'id'              => $childPermission['id'],
                        'name'            => $childPermission['name'],
                        'parent_id'       => $childPermission['parent_id'],
                        // 'component'       => $childPermission['component'],
                        'permission_type' => $childPermission['permission_type'],
                        'checked'         => in_array($childPermission['id'], array_column($role_permissions, 'id')),
                    ];
                    $midLevelData['subMenus'][] = $childData;
                }

                $parentData['subMenus'][] = $midLevelData;
            }

            $result[] = $parentData;
        }

        return response()->json($result);
    }

    public function RoleExport()
    {

        return Excel::download(new RolesExport, 'roles-data-' . date('is') . '.xlsx');
    }


    public function manageExportColumns(Role $role)
    {

        // Get all permissions assigned to the role
        $permissions = $role->permissions->whereIn('name', config('global.reports_permissions'))->pluck('name'); //(config('global.reports_permissions'));
        $reports_config = config('export_columns');

        // Filter reports_config to include only the permissions the role has
        $filtered_reports_config = collect($reports_config)->filter(function ($value, $key) use ($permissions) {
            return $permissions->contains($key); // Check if the role has permission for the key
        });


        return view('roles.manage-export-columns', [
            'role' => $role,
            'reports' => $filtered_reports_config,
        ]);
    }

    public function RoleExportSave(Request $request)
    {
        # code...
        // dd($request->all());


        try {

            $role_id = $request->role_id;
            $user_id = Auth::user()->id;
            if ($request->filled('report_name') && is_array($request->report_name)) {
                foreach ($request->report_name  as $key => $report_name) {

                    if (isset($request->columns[$key])) {
                        if (ExportColumnPermissions::where('role_id', $role_id)->where('report_name', $report_name)->exists()) {

                            ExportColumnPermissions::where('role_id', $role_id)->where('report_name', $report_name)->update([

                                'report_columns'    => json_encode($request->columns[$key]),
                                'updated_by'        => $user_id,
                            ]);
                        } else {

                            ExportColumnPermissions::create([
                                'role_id'           => $role_id,
                                'report_name'       => $report_name,
                                'report_columns'    => json_encode($request->columns[$key]),
                                'created_by'        => $user_id,
                            ]);
                        }
                    } elseif (empty($request->columns[$key])) {

                        ExportColumnPermissions::where('role_id', $role_id)->where('report_name', $report_name)->update([

                            'report_columns'    => json_encode([]),
                            'updated_by'        => $user_id,
                        ]);
                    }
                }
            }

            //code...
        } catch (\Throwable $th) {
            //throw $th;
            // dd($th->getMessage());
            return back()->with('alert-danger', Env('APP_ENV') == 'local' ? $th->getMessage() : 'Something went wrong');
        }

        return redirect()->route('roles.index')->with('alert-success', 'Reports Configuration successfully');
    }
}
