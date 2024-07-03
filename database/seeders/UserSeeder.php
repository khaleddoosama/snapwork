<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $folderPath = public_path('uploads/user/pictures');
        $folderPath2 = public_path('uploads/admin/pictures');

        if (File::isDirectory($folderPath)) {
            File::cleanDirectory($folderPath);
        }

        if (File::isDirectory($folderPath2)) {
            File::cleanDirectory($folderPath2);
        }

        DB::table('users')->insert([
            [
                'name' => 'name admin',
                'slug' => 'admin',
                'email' => 'admin@gmail.com',
                'password' => bcrypt('111'),
                'role' => 'admin',
                'status' => 1,
            ],
            [
                'name' => 'name client',
                'slug' => 'client',
                'email' => 'client@gmail.com',
                'password' => bcrypt('111'),
                'role' => 'client',
                'status' => 1,
            ],
            [
                'name' => 'name freelancer',
                'slug' => 'freelancer',
                'email' => 'freelancer@gmail.com',
                'password' => bcrypt('111'),
                'role' => 'freelancer',
                'status' => 1,
            ],
        ]);
    }
}
