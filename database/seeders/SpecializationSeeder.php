<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SpecializationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('specializations')->insert([
            'name' => 'Web Development',
            'slug' => 'web-development',
        ]);

        DB::table('specializations')->insert([
            'name' => 'Mobile Development',
            'slug' => 'mobile-development',
        ]);

        DB::table('specializations')->insert([
            'name' => 'Game Development',
            'slug' => 'game-development',
        ]);
    }
}
