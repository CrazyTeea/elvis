<?php

namespace Database\Seeders;

use App\Models\File;
use App\Models\Monkey;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        //Monkey::factory()->count(5)->create();
        File::factory()->count(10)->create();
    }
}
