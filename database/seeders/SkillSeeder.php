<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SkillSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {


        DB::table('skills')->insert([
            [
                'name' => 'PHP',
            ],
            [
                'name' => 'Laravel',
            ],
            [
                'name' => 'SQL',
            ],
        ]);

        
    }
}
