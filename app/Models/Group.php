<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role;

class Group extends Model
{
    use HasFactory,HasUuids;

    protected $fillable = ['group_name'];

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'group_has_permissions', 'group_id', 'permission_id');
    }

    public function roles()
    {
        return $this->hasMany(Role::class);
    }

    public function parent_sub_permissions_by_group_id($group_id)
    {
        $permissionIds = $this->permissions()->pluck('id')->toArray();
        $parents = Permission::whereIn('id', $permissionIds)
            ->whereNull('parent_id')
            // ->where('component', 'general_settings')
            // ->orderByRaw("
            //     CASE
            //         WHEN component = 'general_settings' THEN 1
            //         WHEN component = 'livelihood' THEN 2
            //         WHEN component = 'irrigation' THEN 3
            //         WHEN component = 'roads' THEN 4
            //         WHEN component = 'water_supplies_and_drainage' THEN 5
            //         WHEN component = 'project_management_and_operational_cost' THEN 6
            //         WHEN component = 'grm' THEN 7
            //         WHEN component = 'rescue_1122' THEN 8
            //         WHEN name      = 'Dashboards' THEN 9
            //         WHEN component = 'pid' THEN 10
            //         WHEN component = 'sid' THEN 11
            //         WHEN component IS NULL THEN 12
            //         ELSE 13
            //     END
            // ")
            ->orderBy('sort_by', 'asc') // Add additional sorting if needed
            ->get(['id', 'name', 'parent_id', 'sort_by']);

        $subPermissions = Permission::whereIn('parent_id', $permissionIds)
            ->get(['id', 'name', 'parent_id']);

        $result = [];

        foreach ($parents as $parentPermission) {
            $parentData = [
                'id'                   => $parentPermission->id,
                'name'                 => $parentPermission->name,
                'parent_id'            => $parentPermission->parent_id ?? '',
                'component'            => $parentPermission->component ?? '',
                'sort_by'              => $parentPermission->sort_by,
                'subMenus'             => [],
            ];

            foreach ($parentPermission->subMenus as $midLevelPermission) {
                $midLevelData = [
                    'id'              => $midLevelPermission->id,
                    'name'            => $midLevelPermission->name,
                    'parent_id'       => $midLevelPermission->parent_id ?? '',
                    'component'       => $midLevelPermission->component ?? '',
                    'permission_type' => $midLevelPermission->permission_type,
                    'sort_by'         => $midLevelPermission->sort_by,
                    'subMenus'        => [],
                ];

                foreach ($midLevelPermission->subMenus as $childPermission) {
                    $childData = [
                        'id'              => $childPermission->id,
                        'name'            => $childPermission->name,
                        'parent_id'       => $childPermission->parent_id ?? '',
                        'component'       => $childPermission->component ?? '',
                        'permission_type' => $childPermission->permission_type,
                        'sort_by'         => $childPermission->sort_by,
                    ];

                    $midLevelData['subMenus'][] = $childData;
                }

                usort($midLevelData['subMenus'], function ($a, $b) {
                    return $a['sort_by'] <=> $b['sort_by'];
                });

                $parentData['subMenus'][] = $midLevelData;
            }

            usort($parentData['subMenus'], function ($a, $b) {
                return $a['sort_by'] <=> $b['sort_by'];
            });

            $result[] = $parentData;
        }

        return $result;
    }
}
