<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ApplicationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('applications')->insert([
            [
                'bid' => 1000,
                'duration' => 30,
                'cover_letter' => 'This is a sample cover letter for the job application.',
                'attachments' => json_encode(["resume.pdf", "portfolio.pdf"]),
                'job_id' => 1,
                'freelancer_id' => 3
            ],
            [
                'bid' => 1500,
                'duration' => 45,
                'cover_letter' => 'I am excited to apply for this job opportunity.',
                'attachments' => null, // or json_encode([]) if empty array is preferred
                'job_id' => 2,
                'freelancer_id' => 3
            ],
            [
                'bid' => 2000,
                'duration' => 60,
                'cover_letter' => 'I have the necessary skills and experience for this job.',
                'attachments' => null, // or json_encode([]) if empty array is preferred
                'job_id' => 3,
                'freelancer_id' => 1
            ]
        ]);
    }
}
