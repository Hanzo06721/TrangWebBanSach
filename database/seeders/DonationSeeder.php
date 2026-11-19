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
        $donations = [
            [
                'DonationID' => 'D001',
                'UserID' => 'U002',
                'PostID' => 'P005',
                'ContactInfo' => '0901000002',
                'DonationStatus' => 'Completed',
                'CreatedAt' => '2026-09-19 14:00:00',
            ],
            [
                'DonationID' => 'D002',
                'UserID' => 'U003',
                'PostID' => 'P010',
                'ContactInfo' => '0901000003',
                'DonationStatus' => 'Completed',
                'CreatedAt' => '2026-09-24 10:40:00',
            ],
            [
                'DonationID' => 'D003',
                'UserID' => 'U004',
                'PostID' => 'P015',
                'ContactInfo' => '0901000004',
                'DonationStatus' => 'Completed',
                'CreatedAt' => '2026-09-29 08:30:00',
            ],
            [
                'DonationID' => 'D004',
                'UserID' => 'U005',
                'PostID' => 'P020',
                'ContactInfo' => '0901000005',
                'DonationStatus' => 'Completed',
                'CreatedAt' => '2026-10-04 15:15:00',
            ],
            [
                'DonationID' => 'D005',
                'UserID' => 'U002',
                'PostID' => 'P025',
                'ContactInfo' => '0901000002',
                'DonationStatus' => 'Completed',
                'CreatedAt' => '2026-10-09 15:50:00',
            ],
        ];

        foreach ($donations as $dn) {
            DB::table('donations')->updateOrInsert(
                ['DonationID' => $dn['DonationID']],
                $dn
            );
        }
    }
}
