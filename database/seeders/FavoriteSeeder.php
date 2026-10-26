<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FavoriteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('favorites')->insert([
            [
                'FavoriteID' => 'F001',
                'UserID' => 'U003',
                'PostID' => 'P001',
                'CreatedAt' => '2026-09-10 10:00:00',
            ],
            [
                'FavoriteID' => 'F002',
                'UserID' => 'U004',
                'PostID' => 'P001',
                'CreatedAt' => '2026-09-11 11:30:00',
            ],
            [
                'FavoriteID' => 'F003',
                'UserID' => 'U005',
                'PostID' => 'P002',
                'CreatedAt' => '2026-09-12 14:00:00',
            ],
            [
                'FavoriteID' => 'F004',
                'UserID' => 'U002',
                'PostID' => 'P003',
                'CreatedAt' => '2026-09-13 09:00:00',
            ],
            [
                'FavoriteID' => 'F005',
                'UserID' => 'U003',
                'PostID' => 'P004',
                'CreatedAt' => '2026-09-14 16:00:00',
            ],
        ]);
    }
}
