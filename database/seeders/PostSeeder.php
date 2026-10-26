<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       DB::table('posts')->insert([
            [
                'PostID' => 'P001',
                'BookID' => 'B001',
                'UserID' => 'U002',
                'Title' => 'Ban sach Lap trinh C#',
                'Price' => 85000,
                'PostType' => 'Bán',
                'Condition' => 'Tốt',
                'Description' => 'Sach con dep, co mot vai ghi chu nho',
                'PostStatus' => 'Đã duyệt',
            ],
            [
                'PostID' => 'P002',
                'BookID' => 'B002',
                'UserID' => 'U003',
                'Title' => 'Ban De men phieu luu ky',
                'Price' => 50000,
                'PostType' => 'Bán',
                'Condition' => 'Như mới',
                'Description' => 'Sach gan nhu chua su dung',
                'PostStatus' => 'Đã duyệt',
            ],
            [
                'PostID' => 'P003',
                'BookID' => 'B003',
                'UserID' => 'U004',
                'Title' => 'Trao doi sach Khoi nghiep tinh gon',
                'Price' => null,
                'PostType' => 'Trao đổi',
                'Condition' => 'Tốt',
                'Description' => 'Muon doi voi sach lap trinh hoac cong nghe',
                'PostStatus' => 'Đã duyệt',
            ],
            [
                'PostID' => 'P004',
                'BookID' => 'B004',
                'UserID' => 'U005',
                'Title' => 'Ky gui sach English Grammar in Use',
                'Price' => 120000,
                'PostType' => 'Ký gửi',
                'Condition' => 'Mới',
                'Description' => 'Sach moi, nho website ho tro ban',
                'PostStatus' => 'Đã duyệt',
            ],
            [
                'PostID' => 'P005',
                'BookID' => 'B005',
                'UserID' => 'U002',
                'Title' => 'Quyen gop sach Dac nhan tam',
                'Price' => null,
                'PostType' => 'Quyên góp',
                'Condition' => 'Ổn',
                'Description' => 'Sach con su dung tot, muon tang cho nguoi can',
                'PostStatus' => 'Đã duyệt',
            ],
        ]);
    }
}
