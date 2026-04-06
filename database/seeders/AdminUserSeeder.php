<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => "Chinthana",
            'email' => 'chinthana144@gmail.com',
            'password' => '$2y$12$zoSCnmKYGIJEFz6Ixa1xW.3oHBGOx2aYKe8awQamJyehsyZQQve56',
            'role_id' => '1',
        ]);
    }
}
