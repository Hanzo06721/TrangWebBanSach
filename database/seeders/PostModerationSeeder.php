<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PostModerationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('postmoderation')->insert([
            ['ModerationID' => 'MD001', 'PostID' => 'P001', 'AdminID' => 'U001', 'Status' => 'Approved', 'Reason' => null],
            ['ModerationID' => 'MD002', 'PostID' => 'P002', 'AdminID' => 'U001', 'Status' => 'Approved', 'Reason' => null],
            ['ModerationID' => 'MD003', 'PostID' => 'P003', 'AdminID' => 'U001', 'Status' => 'Approved', 'Reason' => null],
            ['ModerationID' => 'MD004', 'PostID' => 'P004', 'AdminID' => 'U001', 'Status' => 'Approved', 'Reason' => null],
            ['ModerationID' => 'MD005', 'PostID' => 'P005', 'AdminID' => 'U001', 'Status' => 'Approved', 'Reason' => null],
        ]);
    }
}
