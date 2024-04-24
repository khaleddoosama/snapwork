<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JobSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('jobs')->insert([
            [
                'title' => 'Web Development Project',
                'slug' => 'web-development-project',
                'description' => 'Looking for a web developer to create a responsive website.',
                'required_skills' => json_encode(["HTML", "CSS", "JavaScript", "PHP"]),
                'expected_budget' => 3000,
                'expected_duration' => 45,
                'attachments' => json_encode(["project_details.pdf"]),
                'client_id' => 1,
                'specialization_id' => 3,
                'type' => 'open',
                'location_type' => 'remote',
            ],
        ]);
        DB::table('jobs')->insert([
            [
                'title' => 'Mobile App Development',
                'slug' => 'mobile-app-development',
                'description' => 'Developing an iOS and Android app for our company.',
                'required_skills' => json_encode(["Swift", "Kotlin", "Firebase", "React Native"]),
                'expected_budget' => 5000,
                'expected_duration' => 60,
                'attachments' => json_encode(["app_wireframes.pdf", "design_specifications.docx"]),
                'client_id' => 2,
                'specialization_id' => 2,
                'type' => 'closed',
                'location_type' => 'on-site',
                'longitude' => -118.2437,
                'latitude' => 34.0522,
                'address' => 'Los Angeles, CA, USA'
            ],

        ]);

        DB::table('jobs')->insert([
            [
                'title' => 'Graphic Design Project',
                'slug' => 'graphic-design-project',
                'description' => 'Designing a company logo and branding materials.',
                'required_skills' => json_encode(["Adobe Illustrator", "Adobe Photoshop", "Typography"]),
                'expected_budget' => 1500,
                'expected_duration' => 30,
                'client_id' => 3,
                'specialization_id' => 3,
            ]
        ]);
    }
}
