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
        // Define your data
        $specializations = [
            // Web Development
            'Web Development',
            'Front end development',
            'Back end development',
            'Mobile development',
            'Game Development',
            'Kotlin development',
            'Flutter development',
            'Desktop development',
            'Machine learning development',
            'UI/UX development',
            'UI designer',
            'UI development',
            'Instructor for programming',
            // Graphics
            'Graphics designer',
            'Graphics development',
            'Photoshop development',
            // Teaching
            'Teacher for primary level',
            'Teacher for preparatory level',
            'Teacher for secondary level',
            // Services
            'Chef',
            'Plumber',
            'Carpenter',
            'Maid',
            'Babysitter',
            // Content Creation
            'Content creation',
            'Writing articles',
            'Social media administrators',
            'Video maker',
            'Video editing',
        ];

        // Insert data into the database
        foreach ($specializations as $specialization) {
            DB::table('specializations')->insert([
                'name' => $specialization,
                'slug' => \Illuminate\Support\Str::slug($specialization), // Generate slug
            ]);
        }
    }
}
