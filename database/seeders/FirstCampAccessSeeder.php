<?php

namespace Database\Seeders;

use App\Models\CampUsers;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FirstCampAccessSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CampUsers::create([
            'camp_id' => 1,
            'user_id' => 1,
        ]);
    }
}
