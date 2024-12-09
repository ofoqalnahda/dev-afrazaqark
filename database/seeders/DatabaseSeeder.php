<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Modules\Home\Database\Seeders\HomeDatabaseSeeder;
use Modules\Info\Database\Seeders\InfoDatabaseSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            HomeDatabaseSeeder::class,
            InfoDatabaseSeeder::class,
        ]);
    }
}
