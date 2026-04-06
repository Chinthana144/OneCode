<?php

namespace Database\Seeders;

use App\Models\Roles;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserRolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Roles::create([ 'name' => 'Admin' ]);
        Roles::create([ 'name' => 'Owner' ]);
        Roles::create([ 'name' => 'Manager' ]);
        Roles::create([ 'name' => 'Client' ]);
        Roles::create([ 'name' => 'Assistant Manager' ]);
        Roles::create([ 'name' => 'Engineer' ]);
        Roles::create([ 'name' => 'Accountant' ]);
        Roles::create([ 'name' => 'Technician' ]);
        Roles::create([ 'name' => 'Salesman' ]);
        Roles::create([ 'name' => 'Intern' ]);
    }
}
