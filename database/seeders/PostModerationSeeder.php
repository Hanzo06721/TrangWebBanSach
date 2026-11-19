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
        $records = [];
        for ($i = 1; $i <= 25; $i++) {
            $num = str_pad($i, 3, '0', STR_PAD_LEFT);
            $records[] = [
                'ModerationID' => 'MD' . $num,
                'PostID' => 'P' . $num,
                'AdminID' => 'U001',
                'Status' => 'Approved',
                'Reason' => null,
            ];
        }

        foreach ($records as $record) {
            DB::table('postmoderation')->updateOrInsert(
                ['ModerationID' => $record['ModerationID']],
                $record
            );
        }
    }
}
