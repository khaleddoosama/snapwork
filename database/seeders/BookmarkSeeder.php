<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BookmarkSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('bookmarks')->insert([
            [
                'job_id' => 1,
                'user_id' => 2
            ],
            [
                'job_id' => 3,
                'user_id' => 2
            ],
            [
                'job_id' => 3,
                'user_id' => 1
            ]
        ]);
    }
}
