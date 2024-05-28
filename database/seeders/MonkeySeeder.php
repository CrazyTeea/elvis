<?php

namespace Database\Seeders;

use App\Models\Monkey;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MonkeySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Monkey::factory(5)->create();
    }
}
