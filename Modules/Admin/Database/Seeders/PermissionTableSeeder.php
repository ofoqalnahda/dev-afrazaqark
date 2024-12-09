<?php

namespace Modules\Admin\Database\Seeders;

use Modules\Admin\App\Models\Admin;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Schema;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // for testing purpose only
        // Schema::disableForeignKeyConstraints();
        // Permission::truncate();
        // Schema::enableForeignKeyConstraints();
        // for testing purpose only
        $modulePermissionArray = Admin::modulePermissionArray();
        $subModulePermissionArray = Admin::subModulePermissionArray();
        $specialModulePermissionArray = Admin::specialModulePermissionArray();

        $data = [];
        foreach ($modulePermissionArray as $key_module => $moudle) {
            if (!empty($subModulePermissionArray[$key_module])) {
                foreach ($subModulePermissionArray[$key_module] as $key_sub_module =>  $sub_module) {
                    if(!in_array($key_sub_module,$specialModulePermissionArray)){
                        $data[] = ['name' => $key_module . '.' . $key_sub_module . '.view'];
                        $data[] = ['name' => $key_module . '.' . $key_sub_module . '.create'];
                        $data[] = ['name' => $key_module . '.' . $key_sub_module . '.edit'];
                        $data[] = ['name' => $key_module . '.' . $key_sub_module . '.delete'];
                    }else{
                        $data[] = ['name' => $key_module . '.' . $key_sub_module . '.special'];

                    }

                }
            }
        }

        $insert_data = [];
        $time_stamp = Carbon::now()->toDateTimeString();
        foreach ($data as $d) {
            $d['guard_name'] = 'admin';
            $d['created_at'] = $time_stamp;
            $insert_data[] = $d;
        }
        Permission::insert($insert_data);
        $permissions= Permission::pluck('name');
        $admin=Admin::first();
        $admin->syncPermissions($permissions);

    }
}
