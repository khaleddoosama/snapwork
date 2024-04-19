<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

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
