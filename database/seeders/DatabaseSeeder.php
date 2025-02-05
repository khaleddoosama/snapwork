<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            PermissionSeeder::class,
            RoleSeeder::class,
            SpecializationSeeder::class,
            SkillSeeder::class,
            UserSeeder::class,
            JobSeeder::class,
            ApplicationSeeder::class,
            InvitationSeeder::class,
            BookmarkSeeder::class
        ]);
    }
}
