<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InvitationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('invitations')->insert([
            [
                'job_id' => 1,
                'freelancer_id' => 3
            ],
            [
                'job_id' => 1,
                'freelancer_id' => 1
            ],
            [
                'job_id' => 2,
                'freelancer_id' => 3
            ]
        ]);
    }
}
