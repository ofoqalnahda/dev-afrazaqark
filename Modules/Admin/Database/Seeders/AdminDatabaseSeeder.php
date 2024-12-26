<?php

namespace Modules\Admin\Database\Seeders;

use Carbon\Carbon;
use Modules\Admin\Database\Seeders\PermissionTableSeeder;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Modules\Admin\App\Models\Admin;

class AdminDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $user_data = [
            'name' => 'superAdmin',
            'phone' => '0500000000',
            'password' => bcrypt("12345678"),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ];

        Admin::create($user_data);

        $this->call(
            PermissionTableSeeder::class
        );
    }
}
