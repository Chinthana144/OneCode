<?php

namespace Database\Seeders;

use App\Models\CustomerType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CustomerTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CustomerType::create(['customerType' => 'Labor']);
        CustomerType::create(['customerType' => 'Cleaner']);
        CustomerType::create(['customerType' => 'Security']);
        CustomerType::create(['customerType' => 'Staff']);
        CustomerType::create(['customerType' => 'Salesman']);
        CustomerType::create(['customerType' => 'Technician']);
        CustomerType::create(['customerType' => 'Manager']);
    }
}
