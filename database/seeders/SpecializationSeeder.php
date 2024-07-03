<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SpecializationSeeder extends Seeder
{
    public function run(): void
    {
        $specializations = [
            'Web Development', 'Front end development', 'Back end development',
            'Mobile development', 'Game Development', 'Kotlin development',
            'Flutter development', 'Desktop development', 'Machine learning development',
            'UI/UX development', 'UI designer', 'UI development', 'Instructor for programming',
            'Graphics designer', 'Graphics development', 'Photoshop development',
            'Teacher for primary level', 'Teacher for preparatory level', 'Teacher for secondary level',
            'Chef', 'Plumber', 'Carpenter', 'Maid', 'Babysitter',
            'Content creation', 'Writing articles', 'Social media administrators',
            'Video maker', 'Video editing'
        ];

        foreach ($specializations as $specialization) {
            DB::table('specializations')->insert([
                'name' => $specialization,
                'slug' => Str::slug($specialization),
            ]);
        }
    }
}
