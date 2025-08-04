<?php

namespace Database\Seeders;

use Spatie\Permission\Models\Permission;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class MenuSequenceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('permissions')->update(['sort_by' => NULL]);

        $permissionsToUpdate = [
            'Dashboard'    => 1,
            //User Management - Meta Data
            'User Management'    => 2,
            'Manage Meta Data'   => 3,

            'Manage District'       => 1,
            'Manage Courts'         => 2,
            
            'Manage Courts Case'         => 4,
        ];

        foreach ($permissionsToUpdate as $permissionName => $sortBy) {

            $permission = Permission::where('name', $permissionName)->first();

            if ($permission) {
                $permission->update(['sort_by' => $sortBy]);
            }
        }
    }
}
