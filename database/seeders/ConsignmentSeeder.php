<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ConsignmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('consignments')->insert([
            [
                'ConsignmentID' => 'CS001',
                'UserID' => 'U005',
                'PostID' => 'P004',
                'ContactInfo' => '0901000005',
                'Description' => 'Nho website ho tro ban sach English Grammar in Use',
                'ConsignmentStatus' => 'Completed',
                'CreatedAt' => '2026-09-18 08:30:00',
            ],
        ]);
    }
}
