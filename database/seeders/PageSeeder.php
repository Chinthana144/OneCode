<?php

namespace Database\Seeders;

use App\Models\Pages;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Pages::create([ 'pagename' => 'Home' ]);
        Pages::create([ 'pagename' => 'Invoice' ]);
        Pages::create([ 'pagename' => 'Customers' ]);
        Pages::create([ 'pagename' => 'Packages' ]);
        Pages::create([ 'pagename' => 'Subscriptions' ]);
        Pages::create([ 'pagename' => 'Reports' ]);
        Pages::create([ 'pagename' => 'Controls' ]);
        Pages::create([ 'pagename' => 'Settings' ]);
        Pages::create([ 'pagename' => 'Camps' ]);
        Pages::create([ 'pagename' => 'Camp Users' ]);
        Pages::create([ 'pagename' => 'Users' ]);
        Pages::create([ 'pagename' => 'User Access' ]);
        Pages::create([ 'pagename' => 'Access Plans' ]);
    }
}
