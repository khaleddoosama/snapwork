<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define the path to the folder you want to delete
        $folderPath = public_path('uploads/user/pictures');
        $folderPath2 = public_path('uploads/admin/pictures');

        // Check if the folder exists
        if (File::isDirectory($folderPath)) {
            File::cleanDirectory($folderPath);
        }

        // Check if the folder exists
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
        $admin = User::find(1);
        $admin->assignRole('Super Admin');

        DB::table('user_skills')->insert([
            [
                'user_id' => 1,
                'skill_id' => 1,
            ],
            [
                'user_id' => 1,
                'skill_id' => 2,
            ],
            [
                'user_id' => 1,
                'skill_id' => 3,
            ],
            [
                'user_id' => 2,
                'skill_id' => 1,
            ],
            [
                'user_id' => 2,
                'skill_id' => 2,
            ],
            [
                'user_id' => 2,
                'skill_id' => 3,
            ],
        ]);
    }
}
