<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Str;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Clear cache
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create Permissions
        $permissions = [
            [
                'parent_name'     => 'Manage Users',
                'permission_link'  => '/users',
                'sub_permissions' => [
                    'user-list',
                    'user-show',
                    'user-create',
                    'user-edit',
                    'user-delete',
                    'user-status',
                ],
            ],
            [
                'parent_name'     => 'Manage Permissions',
                'permission_link' => '/permissions',
                'sub_permissions' => [
                    'permission-list',
                    'permission-show',
                    'permission-create',
                    'permission-edit',
                    'permission-delete',
                ],
            ],
            [
                'parent_name' => 'Manage Groups',
                'permission_link'  => '/groups',
                'sub_permissions' => [
                    'group-list',
                    'group-show',
                    'group-create',
                    'group-edit',
                    'group-delete',
                ],
            ],
            [
                'parent_name' => 'Manage Roles',
                'permission_link'  => '/roles',
                'sub_permissions' => [
                    'role-list',
                    'role-show',
                    'role-create',
                    'role-edit',
                    'role-delete',
                    'role-export',
                    'role-manage-export-columns',
                ],
            ],
            [
                'parent_name' => 'Manage District',
                'permission_link'  => '/district',
                'sub_permissions' => [
                    'district-list',
                    'district-show',
                    'district-create',
                    'district-edit',
                    'district-delete',
                ],
            ],

            [
                'parent_name' => 'Manage Courts',
                'permission_link'  => '/courts',
                'sub_permissions' => [
                    'court-list',
                    'court-show',
                    'court-create',
                    'court-edit',
                    'court-delete',
                ],
            ],
            
            [
                'parent_name' => 'Manage Courts Case',
                'permission_link'  => '/courts-cases',
                'sub_permissions' => [
                    'court-case-list',
                    'court-case-show',
                    'court-case-create',
                    'court-case-edit',
                    'court-case-delete',
                    'court-case-export',
                ],
            ],
        ];

        foreach ($permissions as $permissionGroup) {
            // Create or get parent permission
            $parent = Permission::firstOrCreate(
                ['name' => $permissionGroup['parent_name']],
                [
                    'permission_type' => 'menu',
                    'slug'            => Str::slug($permissionGroup['parent_name']),
                    'permission_link' => $permissionGroup['permission_link'],
                    'is_web'          => 1,
                    'is_mobile'       => 0,
                ]
            );

            foreach ($permissionGroup['sub_permissions'] as $subPermissionName) {
                Permission::firstOrCreate(
                    ['name' => $subPermissionName],
                    [
                        'permission_type' => 'permission',
                        'slug'            => Str::slug($subPermissionName),
                        'permission_link' => $permissionGroup['permission_link'],
                        'parent_id'       => $parent->id,
                        'is_web'          => 1,
                        'is_mobile'       => 1,
                    ]
                );
            }
        }

        
        // Create Roles
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $advocate = Role::firstOrCreate(['name' => 'advocate']);

        // Assign Permissions to Roles
        $admin->givePermissionTo(Permission::all());
        // $advocate->givePermissionTo(['manage cases', 'view cases', 'upload documents']);
        // $clerk->givePermissionTo(['view cases']);
    }
}
