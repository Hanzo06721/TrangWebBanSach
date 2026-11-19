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
        $consignments = [
            [
                'ConsignmentID' => 'CS001',
                'UserID' => 'U005',
                'PostID' => 'P004',
                'ContactInfo' => '0901000005',
                'Description' => 'Nhờ website hỗ trợ bán sách English Grammar in Use',
                'ConsignmentStatus' => 'Completed',
                'CreatedAt' => '2026-09-18 08:30:00',
            ],
            [
                'ConsignmentID' => 'CS002',
                'UserID' => 'U002',
                'PostID' => 'P009',
                'ContactInfo' => '0901000002',
                'Description' => 'Ký gửi sách Nhập môn trí tuệ nhân tạo',
                'ConsignmentStatus' => 'Completed',
                'CreatedAt' => '2026-09-23 09:00:00',
            ],
            [
                'ConsignmentID' => 'CS003',
                'UserID' => 'U003',
                'PostID' => 'P014',
                'ContactInfo' => '0901000003',
                'Description' => 'Ký gửi sách Vợ nhặt',
                'ConsignmentStatus' => 'Completed',
                'CreatedAt' => '2026-09-28 16:10:00',
            ],
            [
                'ConsignmentID' => 'CS004',
                'UserID' => 'U004',
                'PostID' => 'P019',
                'ContactInfo' => '0901000004',
                'Description' => 'Ký gửi sách English Vocabulary in Use',
                'ConsignmentStatus' => 'Completed',
                'CreatedAt' => '2026-10-03 14:00:00',
            ],
            [
                'ConsignmentID' => 'CS005',
                'UserID' => 'U005',
                'PostID' => 'P024',
                'ContactInfo' => '0901000005',
                'Description' => 'Ký gửi sách 7 Thói quen hiệu quả',
                'ConsignmentStatus' => 'Completed',
                'CreatedAt' => '2026-10-08 14:20:00',
            ],
        ];

        foreach ($consignments as $cs) {
            DB::table('consignments')->updateOrInsert(
                ['ConsignmentID' => $cs['ConsignmentID']],
                $cs
            );
        }
    }
}
