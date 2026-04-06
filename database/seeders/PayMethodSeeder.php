<?php

namespace Database\Seeders;

use App\Models\Paymethods;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PayMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Paymethods::create([ 'paymethod_name' => 'Cash' ]);
        Paymethods::create([ 'paymethod_name' => 'Card' ]);
        Paymethods::create([ 'paymethod_name' => 'Bank Transfer' ]);
        Paymethods::create([ 'paymethod_name' => 'Apple Pay' ]);
        Paymethods::create([ 'paymethod_name' => 'Google Pay' ]);
        Paymethods::create([ 'paymethod_name' => 'Manual Entry' ]);
    }
}
