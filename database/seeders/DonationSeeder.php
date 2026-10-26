<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DonationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('donations')->insert([
            [
                'DonationID' => 'D001',
                'UserID' => 'U002',
                'PostID' => 'P005',
                'ContactInfo' => '0901000002',
                'DonationStatus' => 'Completed',
                'CreatedAt' => '2026-09-19 16:00:00',
            ],
        ]);
    }
}
