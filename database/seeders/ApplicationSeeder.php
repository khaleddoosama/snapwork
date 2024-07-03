<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ApplicationSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('applications')->insert([
            [
                'slug' => 'job-1-freelancer-3',
                'bid' => 1000,
                'duration' => 30,
                'cover_letter' => 'This is a sample cover letter for the job application.',
                'attachments' => json_encode(["resume.pdf", "portfolio.pdf"]),
                'job_id' => 1,
                'freelancer_id' => 3
            ],
            [
                'slug' => 'job-2-freelancer-1',
                'bid' => 1500,
                'duration' => 45,
                'cover_letter' => 'I am excited to apply for this job opportunity.',
                'attachments' => null,
                'job_id' => 2,
                'freelancer_id' => 1
            ],
            [
                'slug' => 'job-3-freelancer-2',
                'bid' => 2000,
                'duration' => 60,
                'cover_letter' => 'I have the necessary skills and experience for this job.',
                'attachments' => null,
                'job_id' => 3,
                'freelancer_id' => 2
            ]
        ]);
    }
}
