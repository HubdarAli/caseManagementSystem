<?php

namespace Database\Seeders;

use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //MENU
        $menus = [
            
            //dashboard menu
            [
                'name'            => 'Dashboard',
                'permission_type' => 'menu',
                'slug'            => Str::slug('Dashboard'),
                'permission_link' => '/dashboard',
                'icon_name'       => 'si si-speedometer',
                'is_web'          => 1,
            ],
            //user management
            [
                'name'            => 'User Management',
                'permission_type' => 'menu',
                'slug'            => Str::slug('User Management'),
                'permission_link' => '#',
                'icon_name'       => 'si si-user',
                'is_web'          => 1,
                'sub_menus'       => [
                    [
                        'name'            => 'Manage Users',
                        'permission_type' => 'menu',
                        'slug'            => Str::slug('Manage Users'),
                        'permission_link' => '/users',
                        'is_web'          => 1,
                        'parent_name'     => 'User Management',
                    ],
                    [
                        'name'            => 'Manage Permissions',
                        'permission_type' => 'menu',
                        'slug'            => Str::slug('Manage Permissions'),
                        'permission_link' => '/permissions',
                        'is_web'          => 1,
                        'parent_name'     => 'User Management',
                    ],
                    [
                        'name'            => 'Manage Groups',
                        'permission_type' => 'menu',
                        'slug'            => Str::slug('Manage Groups'),
                        'permission_link' => '/groups',
                        'is_web'          => 1,
                        'parent_name'     => 'User Management',
                    ],
                    [
                        'name'            => 'Manage Roles',
                        'permission_type' => 'menu',
                        'slug'            => Str::slug('Manage Roles'),
                        'permission_link' => '/roles',
                        'is_web'          => 1,
                        'parent_name'     => 'User Management',
                    ],
                    // Other submenu items...
                ],
            ],
            //Meta data
            [
                'name'            => 'Manage Meta Data',
                'permission_type' => 'menu',
                'slug'            => Str::slug('Manage Meta Data'),
                'permission_link' => '#',
                'icon_name'       => 'si si-user',
                'is_web'          => 1,
                'sub_menus'        => [
                    [
                        'name'            => 'Manage District',
                        'permission_type' => 'menu',
                        'slug'            => Str::slug('Manage District'),
                        'permission_link' => '/district',
                        'is_web'          => 1,
                        'parent_name'     => 'Manage Meta Data',
                    ],
                    [
                        'name'            => 'Manage Courts',
                        'permission_type' => 'menu',
                        'slug'            => Str::slug('Manage Courts'),
                        'permission_link' => '/court',
                        'is_web'          => 1,
                        'parent_name'     => 'Manage Meta Data',
                    ],
                    
                    // Other submenu items...
                ],
            ],
            // end meta data

            //dashboard menu
            [
                'name'            => 'Manage Courts Case',
                'permission_type' => 'menu',
                'slug'            => Str::slug('Manage Courts Case'),
                'permission_link' => '/courts-cases',
                'icon_name'       => 'si si-speedometer',
                'is_web'          => 1,
            ],
        ];

        foreach ($menus as $menu) {
            $parent = null;

            if (Permission::where('name', $menu['name'])->exists()) {
                $createdMenu = Permission::where('name', $menu['name'])->first();
                if (isset($menu['sub_menus'])) {
                    foreach ($menu['sub_menus'] as $subMenu) {
                        if (Permission::where('name', $subMenu['name'])->exists()) {
                            continue;
                        }

                        Permission::create([
                            'name'            => $subMenu['name'],
                            'permission_type' => $subMenu['permission_type'],
                            'slug'            => $subMenu['slug'],
                            'permission_link' => $subMenu['permission_link'],
                            'is_web'          => $subMenu['is_web'],
                            'parent_id'       => $createdMenu->id,
                        ]);
                    }
                }
                continue;
            }


            if (isset($menu['parent_name'])) {
                $parent = Permission::where('name', $menu['parent_name'])->first();
            }

            $createdMenu = Permission::create([
                'name'            => $menu['name'],
                'permission_type' => $menu['permission_type'],
                'slug'            => $menu['slug'],
                'permission_link' => $menu['permission_link'],
                'icon_name'       => $menu['icon_name'],
                'is_web'          => $menu['is_web'],
                'parent_id'       => $parent?->id,
            ]);

            if (isset($menu['sub_menus'])) {
                foreach ($menu['sub_menus'] as $subMenu) {
                    Permission::create([
                        'name'            => $subMenu['name'],
                        'permission_type' => $subMenu['permission_type'],
                        'slug'            => $subMenu['slug'],
                        'permission_link' => $subMenu['permission_link'],
                        'is_web'          => $subMenu['is_web'],
                        'parent_id'       => $createdMenu->id,
                    ]);
                }
            }
        }
    }
}
