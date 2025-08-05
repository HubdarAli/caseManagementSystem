<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Auth;

class PermissionController extends Controller
{
    function __construct()
    {
        // $this->middleware('permission:permission-list', ['only' => ['index']]);
        // $this->middleware('permission:permission-show', ['only' => ['show']]);
        // $this->middleware('permission:permission-create', ['only' => ['create', 'store']]);
        // $this->middleware('permission:permission-edit', ['only' => ['edit', 'update']]);
        // $this->middleware('permission:permission-delete', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $permissions = Permission::with('parent')
                ->latest()
                ->get();

            return  Datatables::of($permissions)
                ->addIndexColumn()
                ->editColumn('permission_type', function ($permission) {
                    return Str::ucfirst($permission->permission_type);
                })
                ->editColumn('parent_id', function ($permission) {
                    return $permission?->parent?->name;
                })
                ->editColumn('is_web', function ($permission) {
                    $class = "fs-xs fw-semibold d-inline-block py-1 px-3 rounded-pill " . ($permission->is_web ? 'bg-success-light text-success' : 'bg-danger-light text-danger'
                    );

                    return "<span class='$class'> " . ($permission->is_web ? 'Yes' : 'No') . "</span>";
                })
                ->editColumn('is_mobile', function ($permission) {
                    $class = "fs-xs fw-semibold d-inline-block py-1 px-3 rounded-pill " . ($permission->is_mobile ? 'bg-success-light text-success' : 'bg-danger-light text-danger'
                    );

                    return "<span class='$class'> " . ($permission->is_mobile ? 'Yes' : 'No') . "</span>";
                })
                ->addColumn('action', function ($permission) {
                    $classes = 'fs-sm fw-semibold  py-1 px-3';

                    $actions = '<div class="dropdown">
                    <button type="button" class="btn btn-dark btn-sm dropdown-toggle" id="dropdown-default-alt-success" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                      Action
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdown-default-alt-success" style="position: absolute; inset: auto auto 0px 0px; margin: 0px; transform: translate(0px, -40px);" data-popper-placement="top-start" data-popper-reference-hidden="">';

                    if (Auth::user()->can('permission-show'))
                        $actions .=
                            "<a class='dropdown-item' href=' " . route('permissions.show', $permission) . " '>
                            <span class='" . $classes . "'>
                                Show
                            </span>
                        </a>";

                    if (Auth::user()->can('permission-edit'))
                        $actions .=
                            "<a class='dropdown-item' href=' " . route('permissions.edit', $permission) . " '>
                        <span class='" . $classes . "'>
                            Edit
                        </span>
                    </a>";

                    if (Auth::user()->can('permission-delete'))
                        $actions .= "
                            <a class='dropdown-item'
                            href='#'
                            data-bs-toggle='modal'
                            data-bs-target='#deleteModal'
                            data-row-id='" . $permission->id . "'>
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
                ->rawColumns(['action', 'is_web', 'is_mobile'])
                ->make(true);
        }

        return view('permissions.index');
    }

    public function show(Permission $permission)
    {
        $parents['when_menu'] = Permission::where('permission_type', '=', 'menu')
            ->whereNull('parent_id')
            ->get()
            ->pluck('name', 'id')
            ->toArray();

        $parents['when_permission'] = Permission::where('permission_type', '=', 'menu')
            // ->whereNotNull('parent_id')
            ->get()
            ->pluck('name', 'id')
            ->toArray();

        $components = config('global.dashboard_components') ?? [];

        $modifiedArray['all'] = 'All';

        foreach ($components as $key => $value) {
            $formattedKey = ucwords(str_replace('_', ' ', $key));

            if (isset($value['name'])) {
                $modifiedArray[$formattedKey][$key] = $value['name'];
            } else {
                $modifiedArray[$formattedKey][$key] = '';
            }

            if (isset($value['sub_components'])) {
                foreach ($value['sub_components'] as $subKey => $subValue) {
                    $formattedSubKey = ucwords(str_replace('_', ' ', $subKey));
                    if (isset($subValue['name'])) {
                        $modifiedArray[$formattedKey]['Sub Components'][$formattedSubKey][$subKey] = $subValue['name'];
                    } else {
                        $modifiedArray[$formattedKey]['Sub Components'][$formattedSubKey][$subKey] = '';
                    }
                }
            }
        }

        return view('permissions.show', [
            'parents'    => $parents,
            'permission' => $permission,
            'components' => $modifiedArray,
        ]);
    }

    public function create()
    {
        $components = config('global.dashboard_components') ?? [];

        foreach ($components as $key => $value) {
            $formattedKey = ucwords(str_replace('_', ' ', $key));

            if (isset($value['name'])) {
                $modifiedArray[$formattedKey][$key] = $value['name'];
            } else {
                $modifiedArray[$formattedKey][$key] = '';
            }

            if (isset($value['sub_components'])) {
                foreach ($value['sub_components'] as $subKey => $subValue) {
                    $formattedSubKey = ucwords(str_replace('_', ' ', $subKey));
                    if (isset($subValue['name'])) {
                        $modifiedArray[$formattedKey]['Sub Components'][$formattedSubKey][$subKey] = $subValue['name'];
                    } else {
                        $modifiedArray[$formattedKey]['Sub Components'][$formattedSubKey][$subKey] = '';
                    }
                }
            }
        }

        $parents['when_menu'] = Permission::where('permission_type', '=', 'menu')
            ->whereNull('parent_id')
            ->get()
            ->pluck('name', 'id')
            ->toArray();

        $parents['when_permission'] = Permission::where('permission_type', '=', 'menu')
            // ->whereNotNull('parent_id')
            ->get()
            ->pluck('name', 'id')
            ->toArray();

        return view('permissions.create', [
            'parents'    => $parents,
            'components' => $modifiedArray,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate(
            [
                'name'            => 'required|unique:permissions,name',
                'permission_link' => 'required',
                'permission_type' => 'required',
                'access_type'     => 'required',
            ],
            [
                'name.required' => 'Please Enter Permission',
                'permission_link.required' => 'Please Enter Permission Link',
                'permission_type.required' => 'Please Choose Permission Type'
            ]
        );

        Permission::create([
            'name'            => $request->name,
            'slug'            => Str::slug($request->name),
            'component'       => $request->component,
            'permission_link' => $request->permission_link,
            'parent_id'       => !empty($request->parent_id) ? $request->parent_id : NULL,
            'icon_name'       => !empty($request->icon_name) ? $request->icon_name : NULL,
            'description'     => !empty($request->description) ? $request->description : NULL,
            'is_web'          => in_array('for_web', $request->access_type) ? 1 : 0,
            'is_mobile'       => in_array('for_mobile', $request->access_type) ? 1 : 0,
            'permission_type' => $request->permission_type,
        ]);

        return redirect()->route('permissions.index')
            ->with('alert-success', 'Permission created successfully');
    }

    public function edit(Permission $permission)
    {
        $parents['when_menu'] = Permission::where('permission_type', '=', 'menu')
            ->whereNull('parent_id')
            ->get()
            ->pluck('name', 'id')
            ->toArray();

        $parents['when_permission'] = Permission::where('permission_type', '=', 'menu')
            // ->whereNotNull('parent_id')
            ->get()
            ->pluck('name', 'id')
            ->toArray();

        $components = config('global.dashboard_components') ?? [];

        $modifiedArray['all'] = 'All';

        foreach ($components as $key => $value) {
            $formattedKey = ucwords(str_replace('_', ' ', $key));

            if (isset($value['name'])) {
                $modifiedArray[$formattedKey][$key] = $value['name'];
            } else {
                $modifiedArray[$formattedKey][$key] = '';
            }

            if (isset($value['sub_components'])) {
                foreach ($value['sub_components'] as $subKey => $subValue) {
                    $formattedSubKey = ucwords(str_replace('_', ' ', $subKey));
                    if (isset($subValue['name'])) {
                        $modifiedArray[$formattedKey]['Sub Components'][$formattedSubKey][$subKey] = $subValue['name'];
                    } else {
                        $modifiedArray[$formattedKey]['Sub Components'][$formattedSubKey][$subKey] = '';
                    }
                }
            }
        }

        return view('permissions.edit', [
            'parents'    => $parents,
            'permission' => $permission,
            'components' => $modifiedArray,
        ]);
    }

    public function update(Request $request, Permission $permission)
    {
        $request->validate([
            'name'            => 'required|unique:permissions,name,' . $permission->id,
            'permission_link' => 'required',
            'permission_type' => 'required',
            'access_type'     => 'required',
        ]);

        $permission->update([
            'name'            => $request->name,
            'slug'            => Str::slug($request->name),
            'component'       => $request->component,
            'permission_link' => $request->permission_link,
            'parent_id'       => !empty($request->parent_id) ? $request->parent_id : NULL,
            'icon_name'       => !empty($request->icon_name) ? $request->icon_name : NULL,
            'description'     => !empty($request->description) ? $request->description : NULL,
            'is_web'          => in_array('for_web', $request->access_type) ? 1 : 0,
            'is_mobile'       => in_array('for_mobile', $request->access_type) ? 1 : 0,
            'permission_type' => $request->permission_type,
        ]);

        return redirect()->route('permissions.index')
            ->with('alert-success', 'Permission update successfully');
    }

    public function destroy(Permission $permission)
    {
        if ($permission->roles()->count() > 0) {
            return redirect()->route('permissions.index')
                ->with('alert-danger', 'Permission cannot be deleted as it is assigned to one or more roles.');
        }

        $permission->delete();

        return redirect()->route('permissions.index')
            ->with('alert-success', 'Permission deleted successfully.');
    }
}
