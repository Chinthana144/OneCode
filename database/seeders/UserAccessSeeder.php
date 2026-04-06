<?php

namespace Database\Seeders;

use App\Models\PageAccess;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserAccessSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PageAccess::create([ 'user_id'=>1, 'page_id'=>1, 'camp_id'=>1, 'create'=>1, 'view'=>1, 'edit'=>1, 'delete'=>1 ]);
        PageAccess::create([ 'user_id'=>1, 'page_id'=>2, 'camp_id'=>1, 'create'=>1, 'view'=>1, 'edit'=>1, 'delete'=>1 ]);
        PageAccess::create([ 'user_id'=>1, 'page_id'=>3, 'camp_id'=>1, 'create'=>1, 'view'=>1, 'edit'=>1, 'delete'=>1 ]);
        PageAccess::create([ 'user_id'=>1, 'page_id'=>4, 'camp_id'=>1, 'create'=>1, 'view'=>1, 'edit'=>1, 'delete'=>1 ]);
        PageAccess::create([ 'user_id'=>1, 'page_id'=>5, 'camp_id'=>1, 'create'=>1, 'view'=>1, 'edit'=>1, 'delete'=>1 ]);
        PageAccess::create([ 'user_id'=>1, 'page_id'=>6, 'camp_id'=>1, 'create'=>1, 'view'=>1, 'edit'=>1, 'delete'=>1 ]);
        PageAccess::create([ 'user_id'=>1, 'page_id'=>7, 'camp_id'=>1, 'create'=>1, 'view'=>1, 'edit'=>1, 'delete'=>1 ]);
        PageAccess::create([ 'user_id'=>1, 'page_id'=>8, 'camp_id'=>1, 'create'=>1, 'view'=>1, 'edit'=>1, 'delete'=>1 ]);
        PageAccess::create([ 'user_id'=>1, 'page_id'=>9, 'camp_id'=>1, 'create'=>1, 'view'=>1, 'edit'=>1, 'delete'=>1 ]);
        PageAccess::create([ 'user_id'=>1, 'page_id'=>10, 'camp_id'=>1, 'create'=>1, 'view'=>1, 'edit'=>1, 'delete'=>1 ]);
        PageAccess::create([ 'user_id'=>1, 'page_id'=>11, 'camp_id'=>1, 'create'=>1, 'view'=>1, 'edit'=>1, 'delete'=>1 ]);
        PageAccess::create([ 'user_id'=>1, 'page_id'=>12, 'camp_id'=>1, 'create'=>1, 'view'=>1, 'edit'=>1, 'delete'=>1 ]);
        PageAccess::create([ 'user_id'=>1, 'page_id'=>13, 'camp_id'=>1, 'create'=>1, 'view'=>1, 'edit'=>1, 'delete'=>1 ]);
    }
}
