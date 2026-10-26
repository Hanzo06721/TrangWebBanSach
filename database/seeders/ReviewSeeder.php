<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('reviews')->insert([
            [
                'ReviewID' => 'RV001',
                'TransactionID' => 'T001',
                'ReviewerID' => 'U003',
                'ReviewedUserID' => 'U002',
                'Rating' => 5,
                'Comment' => 'Nguoi ban nhiet tinh, sach dung mo ta',
                'CreatedAt' => '2026-09-22 10:00:00',
            ],
            [
                'ReviewID' => 'RV002',
                'TransactionID' => 'T002',
                'ReviewerID' => 'U004',
                'ReviewedUserID' => 'U003',
                'Rating' => 4,
                'Comment' => 'Sach dep, giao dich nhanh',
                'CreatedAt' => '2026-09-24 09:30:00',
            ],
        ]);
    }
}
