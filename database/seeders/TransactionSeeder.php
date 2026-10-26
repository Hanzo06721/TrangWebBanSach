<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('transactions')->insert([
            [
                'TransactionID' => 'T001',
                'PostID' => 'P001',
                'BuyerID' => 'U003',
                'SellerID' => 'U002',
                'TransactionStatus' => 'Completed',
                'CreatedAt' => '2026-09-20 09:30:00',
                'CompletedAt' => '2026-09-21 14:00:00',
            ],
            [
                'TransactionID' => 'T002',
                'PostID' => 'P002',
                'BuyerID' => 'U004',
                'SellerID' => 'U003',
                'TransactionStatus' => 'Completed',
                'CreatedAt' => '2026-09-22 10:00:00',
                'CompletedAt' => '2026-09-23 16:30:00',
            ],
        ]);
    }
}
