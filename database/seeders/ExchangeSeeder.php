<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ExchangeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('exchanges')->insert([
            [
                'ExchangeID' => 'E001',
                'SenderID' => 'U005',
                'PostID' => 'P003',
                'OfferedBookID' => 'B004',
                'Message' => 'Toi muon doi sach nay voi English Grammar in Use',
                'ExchangeStatus' => 'Completed',
                'CreatedAt' => '2026-09-24 10:00:00',
            ],
        ]);
    }
}
