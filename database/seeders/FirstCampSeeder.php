<?php

namespace Database\Seeders;

use App\Models\Camps;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FirstCampSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Camps::create([
            'name' => 'First Camp',
            'location' => 'First location',
            'contactPerson' => 'contact person',
            'contactPhone' => 'contact phone',
            'contactEmail' => 'contactperson@gmail.com',
            'mikritikIP' => '192.168.22.1',
            'mikritikPort' => '8728',
            'mikrotikUsername' => 'admin',
            'mikrotikPassword' => 'pwd@123',
            'radiusSecret' => '0',
            'radiusIP' => '0',
            'monthly_target' => '0.00',
            'status' => '1',
        ]);
    }
}
